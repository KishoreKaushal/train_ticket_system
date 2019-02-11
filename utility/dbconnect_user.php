<?php
    echo $_SERVER['PATH_INFO'];
    // header("Location: ".$_SERVER['DOCUMENT_ROOT']);
    $DBhost = $host;
    $DBuser = $user;
    $DBpass = $password;
    $DBname = "train_ticket_system";
    // $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
    if ($DBcon->connect_errno) {
        die("ERROR : -> ".$DBcon->connect_error);
    }
    //  else {
        // $query_result = $DBcon->query("show tables;");
        // while ($row = $query_result->fetch_array()){
        //     echo($row[0]."<br/>");
        // }
    // }
?>
