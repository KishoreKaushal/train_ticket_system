<?php
    session_start();
    echo "hello<br/>";
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    echo $username;
    echo $password;
    echo "<br/>world<br/>";
    require_once "../utility/dbconnect_user.php";

    $query_result = $DBcon->query("SELECT CURRENT_USER();");
    while ($row = $query_result->fetch_array()){
        echo($row[0]."<br/>");
    }

?>