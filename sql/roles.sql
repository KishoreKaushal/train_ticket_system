MariaDB [(none)]> select user from mysql.user;
+------------------+
| user             |
+------------------+
| rl_admin         |
| rl_customer      |
| rl_public        |
| rl_tte           |
| root             |
| root             |
| admin            |
| debian-sys-maint |
| root             |
| tom              |
| usr_public       |
+------------------+
11 rows in set (0.026 sec)

MariaDB [(none)]> show grants for rl_admin;
+-----------------------------------------------------------------+
| Grants for rl_admin                                             |
+-----------------------------------------------------------------+
| GRANT CREATE USER ON *.* TO 'rl_admin'                          |
| GRANT ALL PRIVILEGES ON `train_ticket_system`.`*` TO 'rl_admin' |
+-----------------------------------------------------------------+
2 rows in set (0.000 sec)

MariaDB [(none)]> show grants for rl_customer;
+-------------------------------------+
| Grants for rl_customer              |
+-------------------------------------+
| GRANT USAGE ON *.* TO 'rl_customer' |
+-------------------------------------+
1 row in set (0.000 sec)

MariaDB [(none)]> show grants for rl_public;
+--------------------------------------------------------------------------------+
| Grants for rl_public                                                           |
+--------------------------------------------------------------------------------+
| GRANT USAGE ON *.* TO 'rl_public'                                              |
| GRANT SELECT ON `train_ticket_system`.`train` TO 'rl_public'                   |
| GRANT SELECT ON `train_ticket_system`.`path` TO 'rl_public'                    |
| GRANT SELECT ON `train_ticket_system`.`station` TO 'rl_public'                 |
| GRANT EXECUTE ON FUNCTION `train_ticket_system`.`calc_distance` TO 'rl_public' |
+--------------------------------------------------------------------------------+
5 rows in set (0.000 sec)

MariaDB [(none)]> show grants for rl_tte;
+--------------------------------+
| Grants for rl_tte              |
+--------------------------------+
| GRANT USAGE ON *.* TO 'rl_tte' |
+--------------------------------+
1 row in set (0.000 sec)

MariaDB [train_ticket_system]> create user usr_public@localhost identified by 'general_public';
Query OK, 0 rows affected (0.000 sec)

MariaDB [train_ticket_system]> grant rl_public to usr_public@localhost;
Query OK, 0 rows affected (0.000 sec)

MariaDB [train_ticket_system]> set default role rl_public for usr_public@localhost;
Query OK, 0 rows affected (0.020 sec)

MariaDB [(none)]> create role rl_user;
Query OK, 0 rows affected (0.000 sec)

MariaDB [(none)]> show grants for rl_user;
+-----------------------------------------------------------+
| Grants for rl_user                                        |
+-----------------------------------------------------------+
| GRANT USAGE ON *.* TO 'rl_user'                           |
| GRANT UPDATE ON `train_ticket_system`.`user` TO 'rl_user' |
+-----------------------------------------------------------+
2 rows in set (0.000 sec)

MariaDB [(none)]> show grants for admin@localhost;
+--------------------------------------------------------------------------------------------------------------+
| Grants for admin@localhost                                                                                   |
+--------------------------------------------------------------------------------------------------------------+
| GRANT rl_admin TO 'admin'@'localhost'                                                                        |
| GRANT rl_user TO 'admin'@'localhost' WITH ADMIN OPTION                                                       |
| GRANT USAGE ON *.* TO 'admin'@'localhost' IDENTIFIED BY PASSWORD '*4ACFE3202A5FF5CF467898FC58AAB1D615029441' |
| GRANT ALL PRIVILEGES ON `mysql`.* TO 'admin'@'localhost'                                                     |
+--------------------------------------------------------------------------------------------------------------+
4 rows in set (0.000 sec)


