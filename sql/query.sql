-- function to return station name corresponding to a station code
create or replace function station_code_name(station_code varchar(5))
returns varchar(50)
reads sql data
begin
    return (select station_name 
            from station as T
            where T.station_code = station_code);
end;

-- function to return train name corresponding to a train number
create or replace function train_no_name(train_no int unsigned)
returns varchar(50)
reads sql data
begin
    return (select train_name 
            from train as T 
            where T.train_no = train_no);
end;

-- function that returns the total fare of a train from a given station to another station
create or replace function calculate_fare(train_no int unsigned, src varchar(5), dest varchar(5))
returns real unsigned
reads sql data
begin
    declare fare_per_km real unsigned;
    declare dist_src real unsigned;
    declare dist_dest real unsigned;
    set fare_per_km = (select T.fare_per_km from train as T where T.train_no = train_no);
    set dist_src = (select T.distance from path as T where T.train_no = train_no and T.station_code = src);
    set dist_dest = (select T.distance from path as T where T.train_no = train_no and T.station_code = dest);
    if(fare_per_km is null or dist_src is null or dist_dest is null) then 
        signal sqlstate '45000' set message_text = 'Invalid data';
    else 
        return (dist_dest - dist_src) * fare_per_km;
    end if;
end;

-- function that returns the stoppage index of a station for a particular train
create or replace function station_code_index(train_no int, station_code varchar(5))
returns integer
reads sql data
begin
    return (select stoppage_idx from path as T where T.train_no = train_no and T.station_code = station_code);
end;

-- procedure to display all the stations in a particular city
create or replace procedure city_stations(in city varchar(50))
reads sql data
begin
    select T.station_code, T.station_name
    from station as T
    where T.city = city;
end;

-- procedure to display the details of a particular train
create or replace procedure train_details(in tr_no int)
reads sql data
begin
    if(not exists(select * from train where train_no = tr_no)) then
        signal sqlstate '45500' set message_text = 'Invalid train number';
    else 
        select stoppage_idx + 1, station_code, station_name, sched_arr, sched_dept, distance
        from path natural join station
        where train_no = tr_no
        order by stoppage_idx;
    end if;
end;

-- procedure to display trains between any two particular stations
create or replace procedure train_between_stations(in src_st varchar(5), in dest_st varchar(5))
reads sql data
begin
    if(src_st = dest_st) then
        signal sqlstate '45000' set message_text = 'Source and destination stations cannot be the same';
    else 
        select V.train_no, V.train_name, T.sched_dept, U.sched_arr, (U.distance - T.distance) as distance_travelled, ((U.distance - T.distance) * V.fare_per_km) as total_fare
        from (path as T) join (path as U) using (train_no) natural join (train as V)
        where T.station_code = src_st and U.station_code = dest_st and T.stoppage_idx < U.stoppage_idx and T.train_no = V.train_no
        order by T.sched_dept;
    end if;
end;

-- function to check whether a seat is available between any two given stations for a given date and train
create or replace function check_seat_available(train_no int, journey_date date, seat_no int, src_st varchar(5), dest_st varchar(5))
returns int
reads sql data
begin
    declare src_idx int;
    declare dest_idx int;
    set src_idx = station_code_index(train_no, src_st);
    set dest_idx = station_code_index(train_no, dest_st);
    if(not exists (select * from train as T where T.train_no = train_no)) then
        signal sqlstate '45000' set message_text = 'Invalid train number';
    elseif(src_idx is null) then
        signal sqlstate '45000' set message_text = 'Source station not valid for the given train';
    elseif(dest_idx is null) then
        signal sqlstate '45000' set message_text = 'Destination station not valid for the given train';
    elseif(src_idx >= dest_idx) then 
        signal sqlstate '45000' set message_text = 'Source cannot come after destination';
    elseif(exists (select *
                from reservation as T
                where T.train_no = train_no and T.journey_date = journey_date and T.seat_no = seat_no and
                        ((T.src_idx < dest_idx and T.src_idx > src_idx) or (T.dest_idx < dest_idx and T.dest_idx > src_idx) or
                        (T.src_idx <= src_idx and T.dest_idx >= dest_idx)))) then
                return 0;
    else
        return 1;
    end if;
end;

-- procedure that displays status of a seat for a given train, journey date
create or replace procedure seat_info(in train_no int, in journey_date date, in seat_no int)
reads sql data
begin
    if(not exists (select * from train as T where T.train_no = train_no)) then
        signal sqlstate '45000' set message_text = 'Invalid train number';
    else
        select *
        from reservation as T
        where T.train_no = train_no and T.journey_date = journey_date and T.seat_no = seat_no
        order by T.src_idx;
    end if;
end;

-- procedure to display all waitlisted tickets for a given train on a given date
create or replace procedure waitlisted_status(in train_no int, in journey_date date) 
reads sql data
begin
    select pnr, source, dest
    from ticket as T 
    where T.train_no = train_no and T.date_journey = journey_date and status = 'WAITLISTED'
    order by date_resv, time_resv;
end;

-- procedure to display the details of a booked ticket
create or replace procedure pnr_info(in pnr bigint unsigned)
reads sql data
begin
    if(not exists(select * from ticket as T where T.pnr = pnr)) then
        signal sqlstate '45000' set message_text = 'Invalid PNR number';
    else
        select pnr, train_no, train_name, source, dest, date_resv, time_resv, date_journey, status, seat_no
        from (ticket as T) natural join (train as U)
        where T.pnr = pnr;
    end if;
end;

-- procedure to display the booking history of a user
create or replace procedure booking_history(in userid varchar(50))
reads sql data
begin
    if (not exists (select * 
                    from user as T 
                    where T.userid = userid)) then
        signal sqlstate '45000' set message_text = 'No such user exists';
    else
        select pnr, train_no, source, dest, date_resv, time_resv, date_journey, status, seat_no
        from ticket as T
        where T.userid = userid
        order by date_resv, time_resv;
    end if;
end;

-- procedure to show the details of a user
create or replace procedure user_info(in userid varchar(50))
reads sql data
begin
    if (not exists (select * 
                    from user as T 
                    where T.userid = userid)) then
        signal sqlstate '45000' set message_text = 'No such user exists';
    else
        select userid, name, aadhar_no, contact_no
        from user as T
        where T.userid = userid;
    end if;
end;

-- procedure to book a ticket
create or replace procedure book_ticket(in pnr bigint unsigned, in userid varchar(50), in src varchar(5), in dest varchar(5), in train_no int, in date_journey date, in seat_no int unsigned)
modifies sql data
begin
    if(date_journey <= current_date) then 
        signal sqlstate '45000' set message_text = 'Reservation should be done atleast one day before the date of journey';
    elseif(datediff(date_journey, current_date) > 10) then 
        signal sqlstate '45000' set message_text = 'Reservation is allowed for atmost 10 days in advance';
    elseif(seat_no <= 0 or seat_no > 20) then
        signal sqlstate '45000' set message_text = 'Seat number should be between 1 and 20';
    else 
        insert into ticket(pnr, userid, source, dest, train_no, date_journey, seat_no)
        values (pnr, userid, src, dest, train_no, date_journey, seat_no);
    end if;
end;

-- procedure to cancel a ticket
create or replace procedure cancel_ticket(in pnr bigint unsigned)
modifies sql data
begin
    if(not exists(select * from ticket as T where T.pnr = pnr)) then
        signal sqlstate '45000' set message_text = 'Invalid PNR number';
    elseif((select T.status from ticket as T where T.pnr = pnr) = 'CANCELLED') then
        signal sqlstate '45000' set message_text = 'Only CONFIRM/WAITLISTED tickets can be cancelled';
    else 
        update ticket as T
        set T.seat_no = NULL, T.status = 'CANCELLED'
        where T.pnr = pnr;
    end if;
end;

-- function to confirm a waitlisted ticket
create or replace procedure confirm_ticket(pnr int, seat_no int)
modifies sql data
begin
    if(not exists(select * from ticket as T where T.pnr = pnr)) then
        signal sqlstate '45000' set message_text = 'Invalid PNR number';
    elseif((select status from ticket as T where T.pnr = pnr) <> 'WAITLISTED') then
        signal sqlstate '45000' set message_text = 'Only WAITLISTED tickets can be confirmed';
    else 
        update ticket as T
        set T.status = 'CONFIRM', T.seat_no = seat_no
        where T.pnr = pnr;
    end if;
end;