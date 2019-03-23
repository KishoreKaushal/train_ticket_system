<?php
session_start();

if (array_key_exists('username' , $_SESSION) && isset($_SESSION['username'])) {

    $response['status'] = false;
    $response['msg'] = "LOGGED IN";

} else if (array_key_exists('signup_credentials' , $_POST) && isset($_POST['signup_credentials'])) {

    $signup_credentials = json_decode($_POST['signup_credentials'], true);

    /* admin login for creating user */
    require_once './dbconnect_admin.php';


    $username = $DBcon_admin->real_escape_string(strip_tags($signup_credentials['username']));
    $name = $DBcon_admin->real_escape_string(strip_tags($signup_credentials['name']));
    $aadhar = $DBcon_admin->real_escape_string(strip_tags($signup_credentials['aadhar']));
    $contact = $DBcon_admin->real_escape_string(strip_tags($signup_credentials['contact']));
    $password = $DBcon_admin->real_escape_string(strip_tags($signup_credentials['password']));

    try {
        $sql = "select count(*) as cnt from user where userid = '$username'";

        $query_result = $DBcon_admin->query($sql);
        $row = $query_result->fetch_array();

        /* username already exists */
        if ($row['cnt'] != 0) {
            throw new Exception("USERNAME ALREADY EXISTS");
        }

        /* turn autocommit off */
        if (!$DBcon_admin->autocommit(FALSE)) {
            throw new Exception("FAILED TO SET AUTOCOMMIT OFF");
        }

        /* begin the transaction */
        if(!$DBcon_admin->begin_transaction(MYSQLI_TRANS_START_READ_WRITE)){
            throw new Exception("FAILED TO START TRANSACTION");
        }

        $sql = "insert into user values('$username', '$name', $aadhar , '$contact') ";

        if (!$DBcon_admin->query($sql)) {
            throw new Exception("UNKNOWN ERROR");
        }

        /* check whether the work is committed successfully */
        if (!$DBcon_admin->commit()) {
            throw new Exception("FAILED TO COMMIT CHANGES");
        }

        $sql =  "create user $username@localhost identified by '".$password."' ;"
             .  "grant rl_user to $username@localhost ;"
             .  "set default role rl_user for $username@localhost";
        $DBcon_admin->multi_query($sql);

        $response['status'] = true;
    } catch (Exception $e){
        $response['status'] = false;
        $response['msg'] = $e->getMessage();
        $DBcon_admin->rollback();
    }
    $DBcon_admin->close();
} else {
    $response['status'] = false;
    $response['msg'] = '';
}

echo json_encode($response);
?>