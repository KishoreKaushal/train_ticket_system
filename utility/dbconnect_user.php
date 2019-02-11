<?php
    $DBhost = 'localhost';
    $DBuser = $username;
    $DBpass = $password;
    $DBname = "train_ticket_system";
    $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
    // if ($DBcon->connect_errno) {
    //     //
    // }
    // //  else {
    //     // $query_result = $DBcon->query("show tables;");
    //     // while ($row = $query_result->fetch_array()){
    //     //     echo($row[0]."<br/>");
    //     // }
    // // }
?>
