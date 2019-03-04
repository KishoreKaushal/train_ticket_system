
alter table path
    add unique key (train_no, stoppage_idx);

drop table if exists seat_allocation;
create table seat_allocation (
  train_no INT UNSIGNED NOT NULL,
  running_date DATE NOT NULL,
  seat_no INT UNSIGNED NOT NULL,
  start_idx INT NOT NULL,
  end_idx INT NOT NULL,
  PRIMARY KEY (train_no, running_date, seat_no, start_idx),
  FOREIGN KEY (train_no, start_idx) REFERENCES path (train_no, stoppage_idx),
  FOREIGN KEY (train_no, end_idx) REFERENCES path (train_no, stoppage_idx),
  FOREIGN KEY (train_no) REFERENCES train(train_no)
);

/* function to check availability for a seat for starting and ending stoppage indexes for a specific train */
create function check_seat_avail(tno integer, rdate integer, seat_no integer, sid integer, eid integer)
  returns bool
  return (
    not exists(
      select * from seat_allocation as sa
      where train_no = tno and running_date = rdate and sa.seat_no = seat_no
      and ((sid >= sa.start_idx and sid < sa.end_idx) or (sid < sa.start_idx and eid > sa.start_idx))
      )
    );

/* function to return the available seat number for some specific train on some data */
/* returns -1 if not found */

DELIMITER //

create function find_avail_seat (tno integer, rdate integer, sid integer, eid integer)
  returns integer reads sql data
  begin
    for i in 1..10
    do
      if(check_seat_avail(tno, rdate, i, sid, eid) = true) then
        return i;
      end if;
    end for;
    return -1;
  end;
//
DELIMITER ;

delimiter //

create procedure past_travels (in uid varchar (50))
reads sql data
begin
  select source, dest from ticket where userid = uid;
end;

//
delimiter;

/* calculating distance from one station to other along a given route of given train */
create function calc_distance(src varchar(5), dst varchar(5), tno integer)
    returns integer
    return (select sum(distance)
            from path as S
                join neighbour as T on (S.station_code = T.st1)
            where S.train_no = tno and
                  S.stoppage_idx >= (select stoppage_idx
                                    from path as U
                                    where U.train_no = tno and
                                          U.station_code = src) and
                  S.stoppage_idx < (select stoppage_idx
                                    from path as V
                                    where V.train_no = tno and
                                        V.station_code = dst) and
                exists (select *
                        from path as W
                        where W.train_no = tno and
                                W.station_code = T.st2 and
                                W.stoppage_idx > S.stoppage_idx));

delimiter //

/* show trains from station 1 to station 2 */

create procedure all_trains (in source varchar (50), in destination varchar(50))
  begin
    select S.train_no as train_no,
       S.source_st as source_station_code,
       X.station_name as source_station_name,
       S.dest_st as destination_station_code,
       Y.station_name as destination_station_name,
       U.sched_arr as arrival_time,
       V.sched_arr as destination_time,
       calc_distance(source, destination, S.train_no) as total_distance,
       (calc_distance(source, destination, S.train_no) * S.fare_per_km) as fare
    from train as S
       join path as U on (S.train_no = U.train_no)
       join path as V on (S.train_no = V.train_no)
       join station as X on (S.source_st = X.station_code)
       join station as Y on (S.dest_st = Y.station_code)
    where U.station_code = source and
        V.station_code = destination and
        U.stoppage_idx < V.stoppage_idx;
  end;
//
delimiter;



delimiter //
/* showing information of a train */
create procedure train_info (in tno int unsigned)
begin
  select S.station_code as station_code,
       T.station_name as station_name,
       S.sched_arr as arrival_time,
       S.sched_dept as departure_time,
       case
            when calc_distance((select source_st as src from train where train_no = tno),
                                    S.station_code, tno) is not null then
                 calc_distance((select source_st as src from train where train_no = tno),
                                    S.station_code, tno)
            else 0
       end as distance_covered
  from path as S
     join station as T on (S.station_code = T.station_code)
  where S.train_no = tno
  order by stoppage_idx;
end
//
delimiter;

