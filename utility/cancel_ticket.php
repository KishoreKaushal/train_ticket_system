<?php

/** Assumes the following parameters in the Ajax query
* train_no
* journey_date
* pnr
* seat_no (0 if waitlisted ticket is cancelled.)
*/

session_start();
if (array_key_exists('username' , $_SESSION) && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $response['status'] = false;
    $response['msg'] = "FAILED";

    if (array_key_exists('cancel_credentials' , $_POST) && isset($_POST['cancel_credentials'])) {
        $cancel_credentials = json_decode($_POST['cancel_credentials'], true);

        require_once './dbconnect_admin.php';

        $train_no = $DBcon_admin->real_escape_string(strip_tags($cancel_credentials['train_no']));
        $journey_date = $DBcon_admin->real_escape_string(strip_tags($cancel_credentials['date']));
        $seat_no = $DBcon_admin->real_escape_string(strip_tags($cancel_credentials['seat_no']));
        $pnr = $DBcon_admin->real_escape_string(strip_tags($cancel_credentials['pnr']));

        try {

            /* turn autocommit off */
            if (!$DBcon_admin->autocommit(FALSE)) {
                throw new Exception("FAILED TO SET AUTOCOMMIT OFF");
            }

            /* begin the transaction */
            if(!$DBcon_admin->begin_transaction(MYSQLI_TRANS_START_READ_WRITE)){
                throw new Exception("FAILED TO START TRANSACTION");
            }

            $sql = "call cancel_ticket('$pnr')";

            if (($query_result = $DBcon_admin->query($sql)) == FALSE) {
                throw new Exception("Error while calling available_seat_list - " . $DBcon_admin->error . " Sql: " . $sql);
            }

            $query_result->free_result();
            $DBcon_admin->next_result();

            /* check whether the work is committed successfully */
            if (!$DBcon_admin->commit()) {
                throw new Exception("FAILED TO COMMIT CHANGES");
            }

            $max_limit = 40;
            while ($max_limit) {
                // (in train_no int, in journey_date date, in seat_no int)
                $sql = "call book_waitlisted_seats($train_no, '$journey_date', $seat_no)";
                
                if (($query_result = $DBcon_admin->query($sql)) == FALSE) {
                    throw new Exception("Error while confirming waiting tickets - " . $DBcon_admin->error . " Sql: " . $sql);
                }

                if ($DBcon_admin->affected_rows == 0) {
                    break;
                }

                $query_result->free_result();
                $DBcon_admin->next_result();

                $max_limit -= 1;
            }

            $wait2con = 40 - $max_limit;

            if (!$max_limit) {
                throw new Exception("INFINITE LOOP DETECTED WHILE WORKING WITH WAITLISTED TICKETS");
            }
        
            $response['msg'] = "Successfully cancelled the current ticket.";
            $response['status'] = true;

        } catch (Exception $e){
            $response['status'] = false;
            $response['msg'] = $e->getMessage();
            $DBcon_admin->rollback();
        }

        $DBcon_admin->close();
    } else {
        $response['status'] = false;
        $response['msg'] = 'cancel CREDENTIALS NOT FOUND';
    }

    echo json_encode($response);

} else {
    $response['status'] = false;
    $response['msg'] = "NOT LOGGED IN";
    echo json_encode($response);
}

?>