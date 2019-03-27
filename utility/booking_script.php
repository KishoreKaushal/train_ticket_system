<?php

/** Assumes the following parameters in $_POST
* src_st
* train_no
* dest_st
* journey_date
* no_of_seats
*/

// available_seat_list(in train_no int, in journey_date date, in src_st varchar(5), in dest_st varchar(5))

session_start();
if (array_key_exists('username' , $_SESSION) && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $response['status'] = false;
    $response['msg'] = "FAILED";

    if (array_key_exists('booking_credentials' , $_POST) && isset($_POST['booking_credentials'])) {
        $booking_credentials = json_decode($_POST['booking_credentials'], true);

        require_once './dbconnect_admin.php';

        $src_st = $DBcon_admin->real_escape_string(strip_tags($booking_credentials['src']));
        $dest_st = $DBcon_admin->real_escape_string(strip_tags($booking_credentials['dest']));
        $train_no = $DBcon_admin->real_escape_string(strip_tags($booking_credentials['train_no']));
        $journey_date = $DBcon_admin->real_escape_string(strip_tags($booking_credentials['date']));
        $no_of_seats = $DBcon_admin->real_escape_string(strip_tags($booking_credentials['qty']));
        $fare = $DBcon_admin->real_escape_string(strip_tags($booking_credentials['fare']));



        $curr_time = '23:56:17';
        $sql = "select current_time";
        $query_result = $DBcon_admin->query($sql);
        $curr_time = $query_result->fetch_array();
        $curr_time = $curr_time[0];

        $query_result->free_result();
        $DBcon_admin->next_result();

//        $src_st = $_POST["src_st"];
//        $dest_st = $_POST["dest_st"];
//        $train_no = $_POST["train_no"];
//        $journey_date = $_POST["journey_date"];
//        $no_of_seats = $_POST["no_of_seats"];

        try {

            /* turn autocommit off */
            if (!$DBcon_admin->autocommit(FALSE)) {
                throw new Exception("FAILED TO SET AUTOCOMMIT OFF");
            }

            /* begin the transaction */
            if(!$DBcon_admin->begin_transaction(MYSQLI_TRANS_START_READ_WRITE)){
                throw new Exception("FAILED TO START TRANSACTION");
            }



            $sql = "call available_seat_list($train_no, '$journey_date', '$src_st', '$dest_st');";
            if (($query_result = $DBcon_admin->query($sql)) == FALSE) {
                throw new Exception("Error while calling available_seat_list - " . $DBcon_admin->error . " Sql: " . $sql);
            }


            $data = array();
            while ($row = $query_result->fetch_array()) {
                array_push($data, $row);
            }

            $query_result->free_result();
            $DBcon_admin->next_result();

            if (sizeof($data) < $no_of_seats) {
                throw new Exception("Only ". sizeof ($data) . " seats remain now.");
            } else {

                // book_ticket(in pnr bigint unsigned, in userid varchar(50), in src varchar(5), in dest varchar(5), in train_no int, in date_journey date, in seat_no int unsigned)

                for ($iter = 0; $iter < $no_of_seats; $iter = $iter + 1) {
                    $seat_no = $data[$iter]['seat_no'];
                    $jd = date("Ymd",strtotime($journey_date));
                    $curr_time = date("Hms", strtotime($curr_time));
                    $pnr = $jd . $train_no . sprintf ("%02d", $seat_no) . $curr_time;
                    $sql = "call book_ticket('$pnr', '$username', '$src_st', '$dest_st', $train_no, '$journey_date', $seat_no)";
//                    echo $sql;

                    if (!($query_result = $DBcon_admin->query($sql))) {
                        throw new Exception("Error while booking seats - " . $DBcon_admin->error . " PNR: " . $sql);
                    }
                }

            }



            /* check whether the work is committed successfully */
            if (!$DBcon_admin->commit()) {
                throw new Exception("FAILED TO COMMIT CHANGES");
            }

            $response['status'] = true;
            $response['msg'] = "Booking completed successfully!";

        } catch (Exception $e){
            $response['status'] = false;
            $response['msg'] = $e->getMessage();
            $DBcon_admin->rollback();
        }

        $DBcon_admin->close();
    } else {
        $response['status'] = false;
        $response['msg'] = 'BOOKING CREDENTIALS NOT FOUND';
    }

    echo json_encode($response);

} else {
    $response['status'] = false;
    $response['msg'] = "NOT LOGGED IN";
    echo json_encode($response);
}

?>