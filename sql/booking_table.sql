
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



