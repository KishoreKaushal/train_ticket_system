-- procedure to display the details of a particular train
create or replace procedure train_details(in tr_no int)
reads sql data
begin
    select stoppage_idx + 1, station_code, station_name, sched_arr, sched_dept, distance
    from path natural join station
    where train_no = tr_no;
end;


-- procedure to display trains between any two particular stations
create or replace procedure train_between_stations(in src_st varchar(5), in dest_st varchar(5))
reads sql data
begin
    if(src_st = dest_st) then
        signal sqlstate '45000' set message_text = 'Source and destination stations cannot be the same';
    else 
        select V.train_no, V.train_name, T.sched_dept, U.sched_arr, (U.distance - T.distance) as distance_travelled, ((U.distance - T.distance) * V.fare_per_km) as total_fare
        from (path as T) join (path as U) on (train_no) natural join (train as V)
        where T.station_code = src_st and U.station_code = dest_st and T.stoppage_idx < U.stoppage_idx and T.train_no = V.train_no;
    end if;
end;

-- function to check whether a seat is available between any two given stations for a given date and train
create or replace function check_seat_available(train_no int, journey_date date, seat_no int, src_st varchar(5), dest_st varchar(5))
returns int
reads sql data
begin
    declare src_idx int;
    declare dest_idx int;
    set src_idx = select stoppage_idx from path as T where T.train_no = train_no, T.station_code = src_idx;
    set dest_idx = select stoppage_idx from path as T where T.train_no = train_no, T.station_code = dest_idx;
    if(not exist (select * from train as T where T.train_no = train_no)) then
        signal sqlstate '45000' set message_text ('Invalid train number');
    elseif(src_idx is null) then
        signal sqlstate '45000' set message_text ('Source station not valid for the given train');
    elseif(dest_idx is null) then
        signal sqlstate '45000' set message_text ('Destination station not valid for the given train');
    elseif(exist (select * 
                  from reservation as T
                  where T.train_no = train_no and T.journey_date = journey_date and T.seat_no = seat_no and
                        ((T.src_idx <= dest_idx and T.src_idx >= src_idx) or (T.dest_idx <= dest_idx and T.dest_idx >= src_idx))) then
                return 0;
    else 
        return 1;                        
    end if;
end;

-- procedure that displays status of a seat for a given train, journey date
create or replace procedure seat_info(train_no int, journey_date date, seat_no int)
returns int
reads sql data
begin
    if(not exist (select * from train as T where T.train_no = train_no)) then
        signal sqlstate '45000' set message_text ('Invalid train number');
    else
        select * 
        from reservation as T
        where T.train_no = train_no and T.journey_date = journey_date and T.seat_no = seat_no;
    end if;
end;