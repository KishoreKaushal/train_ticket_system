-- trigger on inserting new train data in path table
create or replace trigger check_path_insert
before insert on path
for each row
begin
    if(new.stoppage_idx = 0) then
        if(not exists (select *
                       from train as T
                       where (T.train_no = new.train_no))) then
            signal sqlstate '45000' set message_text = 'No such train exist';
        elseif(new.station_code <> (select source_st from train as T where T.train_no = new.train_no)) then
            signal sqlstate '45000' set message_text = 'Source station invalid';
        else 
            set new.distance = 0;
        end if;
    else
        if(not exists (select *
                       from path as T
                       where T.train_no = new.train_no and T.stoppage_idx = (new.stoppage_idx - 1) and
                             exists (select *
                                     from neighbours as U
                                     where U.st_a = T.station_code and U.st_b = new.station_code))) then
            signal sqlstate '45000' set message_text = 'No previous station exist for this train';
        else
            set new.distance = ((select T.distance
                                 from path as T
                                 where (T.train_no = new.train_no and T.stoppage_idx = (new.stoppage_idx - 1)))
                                 +
                                (select U.distance
                                 from neighbours as U
                                 where (U.st_b = new.station_code and U.st_a = (select T.station_code from
                                                                                      path as T
                                                                                      where T.train_no = new.train_no and T.stoppage_idx = (new.stoppage_idx - 1)))));
        end if;
    end if;
end;

-- trigger on inserting new data in reservation table
create or replace trigger check_reservation_insert
before insert on reservation
for each row
begin
    if(exists (select *
               from reservation as T
               where T.train_no = new.train_no and T.journey_date = new.journey_date and T.seat_no = new.seat_no and
                     ((T.src_idx < new.dest_idx and T.src_idx > new.src_idx) or (T.dest_idx < new.dest_idx and T.dest_idx > new.src_idx) or
                      (T.src_idx <= new.src_idx and T.dest_idx >= new.dest_idx)))) then
        signal sqlstate '45000' set message_text = 'Seat already reserved';
    end if;
end;

-- trigger for inserting new data in ticket table
create or replace trigger check_ticket_insert
before insert on ticket
for each row
begin
    declare src_idx int unsigned;
    declare dest_idx int unsigned;
    set new.date_resv = (select current_date);
    set new.time_resv = (select current_time);
    set src_idx = station_code_index(new.train_no, new.source);
    set dest_idx = station_code_index(new.train_no, new.dest);
    if(src_idx is null or dest_idx is null or src_idx >= dest_idx) then 
        signal sqlstate '45000' set message_text = "Invalid source/destination/train no";
    elseif (new.seat_no is null) then
        set new.status = 'WAITLISTED';
    else
        insert into reservation(train_no, seat_no, journey_date, pnr, src_idx, dest_idx)
        values (new.train_no, new.seat_no, new.date_journey, new.pnr, src_idx, dest_idx);
    end if;
end;

-- trigger for update on ticket table
create or replace trigger check_ticket_update
before update on ticket
for each row
begin
    declare src_idx int unsigned;
    declare dest_idx int unsigned;
    if(new.status = 'CONFIRM' and old.status = 'WAITLISTED') then
        set src_idx = station_code_index(new.train_no, new.source);
        set dest_idx = station_code_index(new.train_no, new.dest);
        insert into reservation(train_no, seat_no, journey_date, pnr, src_idx, dest_idx)
        values (new.train_no, new.seat_no, new.date_journey, new.pnr, src_idx, dest_idx);
    elseif(new.status = 'CANCELLED' and old.status <> 'CANCELLED') then
        delete from reservation
        where reservation.pnr = new.pnr;
    else 
        signal sqlstate '45000' set message_text = 'Action not allowed';
    end if;
end;