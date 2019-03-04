
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

create procedure if not exists past_travels (in uid varchar (50))
begin
  select source, dest from ticket where userid = uid;
end;

//
delimiter;



