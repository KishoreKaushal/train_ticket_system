grant execute on procedure train_ticket_system.train_between_stations to rl_public;
grant execute on procedure train_details to rl_public;

grant create user on *.* TO rl_admin ;
grant all privileges on train_ticket_system.* TO rl_admin with grant option;

set default role rl_admin for admin@localhost;
grant select on train_ticket_system.user to rl_user;
grant execute on procedure available_seat_list to rl_user;
grant execute on procedure available_seat_list to rl_public;