<?php
    session_start();
    
    if (array_key_exists('username' , $_SESSION) && isset($_SESSION['username'])) {
        
        $response['status'] = false;
        $response['msg'] = "LOGGED IN";

    } else if (array_key_exists('login_credentials' , $_POST) && isset($_POST['login_credentials'])) {
        
        $login_credentials = json_decode($_POST['login_credentials'], true);

        require_once './dbconnect_public.php';

        $username = $DBcon_public->real_escape_string(strip_tags($login_credentials['username']));
        $password = $DBcon_public->real_escape_string(strip_tags($login_credentials['password']));

        $DBcon_public->close();

        try {
            require_once "./dbconnect_user.php";
            
            if ($DBcon->connect_errno) {
                throw new Exception("INVALID CREDENTIALS");
            } else {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $response['status'] = true;         // authentication succesful
            }
            $DBcon->close();
        } catch (Exception $e){
            $response['status'] = false;
            $response['msg'] = $e->getMessage();

            session_destroy();
            unset($_SESSION['username']);
        }

    } else {
        $response['status'] = false;
        $response['msg'] = '';
    }
    
    echo json_encode($response);
?>