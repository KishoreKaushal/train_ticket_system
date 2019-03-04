-- this file contains definitions of tables used in our project


CREATE OR REPLACE TABLE user (
  userid VARCHAR(50) NOT NULL PRIMARY KEY ,
  password_hash VARCHAR(255) NOT NULL ,
  name VARCHAR(100) NOT NULL ,
  aadhar_no BIGINT UNSIGNED NOT NULL UNIQUE ,
  contact_no VARCHAR(20) NOT NULL
);


CREATE OR REPLACE TABLE station (
  station_code VARCHAR(5) NOT NULL PRIMARY KEY ,
  station_name VARCHAR(50) NOT NULL UNIQUE ,
  city VARCHAR(50) NOT NULL
);


CREATE OR REPLACE TABLE train (
  train_no INT UNSIGNED NOT NULL PRIMARY KEY ,
  train_name VARCHAR(50) NOT NULL UNIQUE ,
  source_st VARCHAR(5) NOT NULL UNIQUE ,
  dest_st VARCHAR(5) NOT NULL UNIQUE ,
  fare_per_km REAL UNSIGNED NOT NULL ,
  FOREIGN KEY (source_st) REFERENCES station(station_code) ,
  FOREIGN KEY (dest_st) REFERENCES station(station_code) ,
  CHECK (source_st <> dest_st and fare_per_km > 0)
);


CREATE OR REPLACE TABLE neighbours (
  st_a VARCHAR(5) NOT NULL ,
  st_b VARCHAR(5) NOT NULL ,
  distance REAL UNSIGNED NOT NULL ,
  PRIMARY KEY (st_a, st_b) ,
  FOREIGN KEY (st_a) REFERENCES station(station_code) ,
  FOREIGN KEY (st_b) REFERENCES station(station_code) ,
  CHECK (distance > 0)
);


CREATE OR REPLACE TABLE path (
  train_no INT UNSIGNED NOT NULL ,
  station_code VARCHAR(5) NOT NULL ,
  sched_arr TIME NOT NULL ,
  sched_dept TIME NOT NULL ,
  stoppage_idx INT NOT NULL ,
  distance REAL UNSIGNED NOT NULL ,
  PRIMARY KEY (train_no, station_code) ,
  FOREIGN KEY (train_no) REFERENCES train(train_no) ,
  FOREIGN KEY (station_code) REFERENCES station(station_code) ,
  CHECK (sched_arr < sched_dept and stoppage_idx >= 0 and distance >= 0)
);


CREATE OR REPLACE TABLE ticket (
  pnr BIGINT UNSIGNED NOT NULL PRIMARY KEY ,
  userid VARCHAR(50) NOT NULL ,
  source VARCHAR(5) NOT NULL ,
  dest VARCHAR(5) NOT NULL ,
  train_no INT UNSIGNED NOT NULL ,
  date_time_resv DATETIME NOT NULL ,
  date_time_trav DATETIME NOT NULL ,
  seat_no INT UNSIGNED NOT NULL ,
  FOREIGN KEY (userid) REFERENCES user(userid) ,
  FOREIGN KEY (source) REFERENCES station(station_code) ,
  FOREIGN KEY (dest) REFERENCES station(station_code) ,
  FOREIGN KEY (train_no) REFERENCES train(train_no) ,
  CHECK (date_time_resv < date_time_trav and seat_no > 0 and pnr > 0)
);

