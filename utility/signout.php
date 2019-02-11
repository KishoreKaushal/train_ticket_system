<?php
    session_start();
    
    // if (isset($_GET['logout']) || isset($_POST['logout'])) {
        session_destroy();
        unset($_SESSION['uid']);
        // successful
        $response['status'] = true;
    // }
    // else{
        // error
        // $response['status'] = false;
    // }
    echo json_encode($response);
?>