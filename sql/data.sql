insert into station(station_name, station_code, city) values
( 'Thiruvananthapuram Central', 'TVC', 'Thiruvananthapuram' ),
( 'Kanyakumari Junction', 'CAPE', 'Kanyakumari' ),
( 'Madurai', 'MDU', 'Madurai' ),
( 'Palakkad Junction', 'PGT', 'Palakkad' ),
( 'Coimbatore Junction', 'CBE', 'Coimbatore' ),
( 'Erode Junction', 'ED', 'Erode' ),
( 'Tiruchchirapalli Junction', 'TPJ', 'Tiruchchirapalli' ),
( 'Chennai Central', 'MAS', 'Chennai' ),
( 'Vijayawada Junction', 'BZA', 'Vijayawada' ),
( 'Visakhapatnam', 'VSKP', 'Visakhapatnam' ),
( 'Bhubaneswar Junction', 'BBS', 'Bhubaneswar' ),
( 'Ranchi', 'RNC', 'Ranchi' ),
( 'Patna Junction', 'PNBE', 'Patna' ),
( 'Kolkata', 'KOAA', 'Kolkata' ),
( 'Bangalore Junction', 'BNC', 'Bangalore' ),
( 'Hyderabad', 'HYB', 'Hyderabad' ),
( 'Warangal', 'WL', 'Warangal' ),
( 'Nagpur Junction', 'NGP', 'Nagpur' ),
( 'Jalgaon', 'JL', 'Jalgaon' ),
( 'Chhatrapati Shivaji Maharaj Terminus', 'CSMT', 'Mumbai' ),
( 'Pune', 'PUNE', 'Pune' ),
( 'Ahmedabad', 'ADI', 'Ahmedabad' ),
( 'Udaipur Junction', 'UDZ', 'Udaipur' ),
( 'Kota', 'KOTA', 'Kota' ),
( 'Jaipur', 'JP', 'Jaipur' ),
( 'Ujjain Junction', 'UJN', 'Ujjain' ),
( 'Bhopal Junction', 'BPL', 'Bhopal' ),
( 'Jhansi', 'JHS', 'Jhansi' ),
( 'New Delhi', 'NDLS', 'Delhi' ),
( 'Hazrat Nizamuddin', 'NZM', 'Delhi' ),
( 'Lucknow', 'LKO', 'Lucknow' ),
( 'Allahabad Junction', 'ALD', 'Allahabad' ),
( 'Varanasi Junction', 'BSB', 'Varanasi' );


insert into neighbours(st_a, st_b, distance) values
( 'TVC', 'CAPE', 87 ),
( 'CAPE', 'TVC', 87 ),
( 'PGT', 'TVC', 356 ),
( 'TVC', 'PGT', 356 ),
( 'PGT', 'CBE', 55 ),
( 'CBE', 'PGT', 55 ),
( 'CBE', 'MDU', 232 ),
( 'MDU', 'CBE', 232 ),
( 'MDU', 'CAPE', 245 ),
( 'CAPE', 'MDU', 245 ),
( 'MDU', 'TPJ', 160 ),
( 'TPJ', 'MDU', 160 ),
( 'CBE', 'ED', 82 ),
( 'ED', 'CBE', 82 ),
( 'CBE', 'BNC', 376 ),
( 'BNC', 'CBE', 376 ),
( 'ED', 'BNC', 276 ),
( 'BNC', 'ED', 276 ),
( 'ED', 'TPJ', 141 ),
( 'TPJ', 'ED', 141 ),
( 'ED', 'MAS', 395 ),
( 'MAS', 'ED', 395 ),
( 'MAS', 'TPJ', 337 ),
( 'TPJ', 'MAS', 337 ),
( 'MAS', 'BZA', 430 ),
( 'BZA', 'MAS', 430 ),
( 'BZA', 'VSKP', 350 ),
( 'VSKP', 'BZA', 350 ),
( 'VSKP', 'BBS', 442 ),
( 'BBS', 'VSKP', 442 ),
( 'BBS', 'RNC', 555 ),
( 'RNC', 'BBS', 555 ),
( 'RNC', 'PNBE', 407 ),
( 'PNBE', 'RNC', 407 ),
( 'BBS', 'KOAA', 440 ),
( 'KOAA', 'BBS', 440 ),
( 'PNBE', 'KOAA', 534 ),
( 'KOAA', 'PNBE', 534 ),
( 'BNC', 'HYB', 636 ),
( 'HYB', 'BNC', 636 ),
( 'HYB', 'WL', 151 ),
( 'WL', 'HYB', 151 ),
( 'WL', 'BZA', 207 ),
( 'BZA', 'WL', 207 ),
( 'BSB', 'PNBE', 228 ),
( 'PNBE', 'BSB', 228 ),
( 'BSB', 'ALD', 124 ),
( 'ALD', 'BSB', 124 ),
( 'BSB', 'LKO', 283 ),
( 'LKO', 'BSB', 283 ),
( 'ALD', 'LKO', 200 ),
( 'LKO', 'ALD', 200 ),
( 'JHS', 'BPL', 290 ),
( 'BPL', 'JHS', 290 ),
( 'JHS', 'LKO', 293 ),
( 'LKO', 'JHS', 293 ),
( 'JHS', 'NZM', 409 ),
( 'NZM', 'JHS', 409 ),
( 'NZM', 'NDLS', 5 ),
( 'NDLS', 'NZM', 5 ),
( 'BPL', 'ALD', 677 ),
( 'ALD', 'BPL', 677 ),
( 'NGP', 'BPL', 395 ),
( 'BPL', 'NGP', 395 ),
( 'NGP', 'WL', 453 ),
( 'WL', 'NGP', 453 ),
( 'NGP', 'JL', 416 ),
( 'JL', 'NGP', 416 ),
( 'JL', 'CSMT', 419 ),
( 'CSMT', 'JL', 419 ),
( 'CSMT', 'PUNE', 169 ),
( 'PUNE', 'CSMT', 169 ),
( 'JL', 'ADI', 540 ),
( 'ADI', 'JL', 540 ),
( 'ADI', 'UDZ', 633 ),
( 'UDZ', 'ADI', 633 ),
( 'UDZ', 'JP', 430 ),
( 'JP', 'UDZ', 430 ),
( 'JP', 'KOTA', 239 ),
( 'KOTA', 'JP', 239 ),
( 'KOTA', 'NZM', 308 ),
( 'NZM', 'KOTA', 308 ),
( 'KOTA', 'UJN', 280 ),
( 'UJN', 'KOTA', 280 ),
( 'UJN', 'BPL', 183 ),
( 'BPL', 'UJN', 183 ),
( 'UDZ', 'UJN', 399 ),
( 'UJN', 'UDZ', 399 );


insert into train(train_no, train_name, source_st, dest_st, fare_per_km) values
(12698, 'TVC-MAS Express', 'TVC', 'MAS', 1.4),
(12457, 'TVC-CAPE-MAS Superfast Express', 'TVC', 'MAS', 2.3);

insert into path(train_no, station_code, sched_arr, sched_dept, stoppage_idx) values
(12698, 'TVC', '03:30:00', '03:50:00', 0),
(12698, 'PGT', '07:15:00', '07:20:00', 1),
(12698, 'CBE', '07:45:00', '07:50:00', 2),
(12698, 'ED', '08:45:00', '08:50:00', 3),
(12698, 'MAS', '11:55:00', '12:25:00', 4),
(12457, 'TVC', '10:00:00', '10:20:00', 0),
(12457, 'CAPE', '10:55:00', '11:00:00',1),
(12457, 'MDU', '13:35:00', '13:40:00', 2),
(12457, 'TPJ', '15:10:00', '15:15:00', 3),
(12457, 'MAS', '19:00:00', '19:30:00', 4);

CREATE OR REPLACE TABLE seat_list (
  seat_no INT UNSIGNED NOT NULL
  primary key
);

DELIMITER //

CREATE OR REPLACE PROCEDURE proc_seat_insert(p1 INT)
  BEGIN
    SET @x = 0;
    REPEAT SET @x = @x + 1; insert into seat_list (seat_no) values (@x);  UNTIL @x >= p1 END REPEAT;
  END
//

CALL proc_seat_insert(20)//

delimiter ;

CREATE OR REPLACE temp_wait_table(
  pnr varchar(50) not null 
  primary key
);

