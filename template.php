<?php
    require_once "/train_ticket_system/utility/dbconnect.php";
	session_start();
	if (array_key_exists('uid' , $_SESSION) && isset($_SESSION['uid'])) {
        // if user has already logged in
        header("Location: /train_ticket_system/user/home.php");
    }

    $DBcon->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="./images/icons/"/>
        
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
        <link rel="stylesheet" href="/train_ticket_system/css/main.css">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script src="/train_ticket_system/js/main.js" type="text/javascript"></script>

        <title>TTS | DBMS</title>
        

    </head>

    <body>
    
    </body>
</html>
