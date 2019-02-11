<?php
  $DBhost = "localhost";
  $DBuser = "usr_public";
  $DBpass = "general_public";
  $DBname = "train_ticket_system";
  $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
     if ($DBcon->connect_errno) {
        die("ERROR : -> ".$DBcon->connect_error);
     }
    //  else {
        // $query_result = $DBcon->query("show tables;");
        // while ($row = $query_result->fetch_array()){
        //     echo($row["Tables_in_ctf_iitpkd"]."<br/>");
        // }
    // }
?>
