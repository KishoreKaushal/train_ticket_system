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


/* show trains from station 1 to station 2 */
select S.train_no as train_no, 
       S.source_st as source_station_code, 
       X.station_name as source_station_name, 
       S.dest_st as destination_station_code, 
       Y.station_name as destination_station_name,
       U.sched_arr as arrival_time, 
       V.sched_arr as destination_time, 
       calc_distance(<?php echo $source?>, <?php echo $desttination?>, S.train_no) as total_distance,
       (calc_distance(<?php echo $source?>, <?php echo $desttination?>, S.train_no) * S.fare_per_km) as fare
from train as S 
     join path as U on (S.train_no = U.train_no)
     join path as V on (S.train_no = V.train_no)
     join station as X on (S.source_st = X.station_code)
     join station as Y on (S.dest_st = Y.station_code)
where U.station_code = <?php echo $source?> and 
      V.station_code = <?php echo $destination> and
      U.stoppage_idx < V.stoppage_idx;


/* showing information of a train */
select S.station_code as station_code, 
       T.station_name as station_name, 
       S.sched_arr as arrival_time, 
       S.sched_dept as departure_time, 
       case
            when calc_distance((select source_st as src from train where train_no = <?php echo $train_no?>), 
                                    S.station_code, <?php echo $train_no?>) is not null then 
                 calc_distance((select source_st as src from train where train_no = <?php echo $train_no?>), 
                                    S.station_code, <?php echo $train_no?>)
            else 0
       end as distance_covered
from path as S 
     join station as T on (S.station_code = T.station_code)
where S.train_no = <?php echo $train_no?>
order by stoppage_idx;
                         

/* showing info of a pnr */
select pnr 
from ticket as T
where T.pnr = <?echo $query_pnr?> and T.userid = <>
