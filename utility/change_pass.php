<?php
    session_start();

    if (!array_key_exists('username' , $_SESSION) || !isset($_SESSION['username'])) {
        // if user has not logged in
        header("Location: /train_ticket_system/");
    }

    echo $_POST['old-password']."<br/>";
    echo $_POST['new-password']."<br/>";
    echo $_POST['confirm-password']."<br/>";
    echo $_SESSION['password']."<br/>";


    if ($_POST['old-password']==$_SESSION['password'] && isset($_POST['new-password'], $_POST['confirm-password'])) {

        $username = $_SESSION['username'];
        $password = $_SESSION['password'];

        require_once "./dbconnect_user.php";


        if ($_POST['new-password'] != $_POST['confirm-password']){
            echo "passwords did not match;";
            exit();
        }

        $password = $DBcon->real_escape_string($_POST['new-password']);

        if ($password != $_POST['new-password']) {
            echo "These hacks won't work here";
            exit();
        }



        $sql = "set password for $username@localhost = PASSWORD('$password');";
        echo $sql."<br/>";

        if(!$DBcon->query($sql)){
            echo "Failed to change the password";
            $DBcon->close();
            exit();
        }
        $_SESSION['password'] = $password;
        $DBcon->close();
        header("Location: /train_ticket_system/profile.php");
    }
?>