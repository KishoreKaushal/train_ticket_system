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
    // if user has already logged in
    require_once './dbconnect_admin.php';
    try {
    
        /* turn autocommit off */
        if (!$DBcon_admin->autocommit(FALSE)) {
            throw new Exception("FAILED TO SET AUTOCOMMIT OFF");
        }

        /* begin the transaction */
        if(!$DBcon_admin->begin_transaction(MYSQLI_TRANS_START_READ_WRITE)){
            throw new Exception("FAILED TO START TRANSACTION");
        }

        $src_st = $_POST["src_st"];
        $dest_st = $_POST["dest_st"];
        $train_no = $_POST["train_no"];
        $journey_date = $_POST["journey_date"];
        $no_of_seats = $_POST["no_of_seats"];

        $sql = "call available_seat_list($train_no, $journey_date, $src_st, $dest_st)";
        if (($query_result = $DBcon_admin->query($sql)) == FALSE) {
            throw new Exception("Error while calling available_seat_list - " . $mysqli->error);
        }
    
        $row = $query_result->fetch_array();

        if (sizeof($row[0]) < $no_of_seats) {
            throw new Exception("Only ". sizeof ($row[0]) . " seats remain now.");
        } else {
            $sql = "";
            // book_ticket(in pnr bigint unsigned, in userid varchar(50), in src varchar(5), in dest varchar(5), in train_no int, in date_journey date, in seat_no int unsigned)
            
            for ($iter = 0; $iter < $no_of_seats; $iter = $iter + 1) {
                $pnr = $journey_date . $train_no . sprintf ("%02d", $iter) . $src_st;
                $cq = "call book_ticket($pnr, $username, $src_st, $dest_st, $train_no, $journey_date, $iter;";
                $sql .= $cq;
            }

            if (($query_result = $DBcon_admin->multi_query($sql)) == FALSE) {
                throw new Exception("Error while booking seats - " . $mysqli->error);
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
    require_once '../signinup.php';
}

?>