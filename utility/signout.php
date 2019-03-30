<?php
    session_start();

    session_destroy();
    unset($_SESSION['username']);
    unset($_SESSION['password']);

    $response['status'] = true;

    echo json_encode($response);
?>