<?php
    $DBhost = "localhost";
    $DBuser = "usr_public";
    $DBpass = "general_public";
    $DBname = "train_ticket_system";
    $DBcon_public = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
    if ($DBcon_public->connect_errno) {
        die("ERROR : -> ".$DBcon_public->connect_error);
    }
    //  else {
    //     $query_result = $DBcon_public->query("show tables;");
    //     while ($row = $query_result->fetch_array()){
    //         echo($row[0]."<br/>");
    //     }
    // }
?>
