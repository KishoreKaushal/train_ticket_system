<?php
    session_start();
    if (!array_key_exists('username' , $_SESSION) || !isset($_SESSION['username'])) {
        // if user has not logged in
        header("Location: /train_ticket_system/signinup.php");
    }



    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    require_once "../utility/dbconnect_user.php";

    $sql = "call booking_history('$username')";


    if(($query_result = $DBcon->query($sql)) == FALSE){
        echo $DBcon->error;
        echo $DBcon->close();
        exit();
    }

    $ticket_info = array();

    while($row = $query_result->fetch_array()){
        array_push($ticket_info, $row);
    }

    $query_result->free_result();
    $DBcon->next_result();

    $DBcon->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Train System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css?family=Chelsea+Market|Fredoka+One" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="/train_ticket_system/js/main.js" type="text/javascript"></script>

    <style>
        body,h1,h2,h3,h4,h5,h6 {font-family: 'Chelsea Market', cursive;}
        .chal-desc{
            font-size:14px;
        }
        @media (min-width: 768px){
            .container{
                max-width:90%;
            }
        }
        @media (min-width: 992px){
            .container{
                max-width:90%;
            }
        }

        .modal-padding{
            padding:16px;
        }
        .card-body{
            padding-top: 35px;
            padding-bottom: 35px;
        }
        p.card-text{
            font-size: larger;
        }

        .navigation .navbar-nav li a{
            color: white !important;
        }
        .title , .category,  .points {
            color : white;
        }
        .currentLink {
            color: red;
            /* background-color: #000000; */
        }
        body{
            color: white;
            background:linear-gradient(to right, rgb(29, 151, 108), rgb(147, 249, 185));
        }
        .navbar{
            background: linear-gradient(30deg, rgb(0, 0, 0), rgb(67, 67, 67));
        }
        .image-container {
            display: flex;
            justify-content: center;
        }
        #footer {
            position:absolute;
            bottom:0;
            width:100%;
            height:60px;   /* Height of the footer */
            background:#6cf;
        }

        .tab-content {
            color : black;
            font-size: large;
        }
        th {
            color: black !important;
        }
        table, th, td {
            border: 2px solid black;
            font-size: large;
            /* color: black !important; */
        }

    </style>

    <script>
        tblData = null;

        $(function(){
            var myRows = [];
            var $headers = $("th");
            var $rows = $("tbody tr").each(function(index) {
                $cells = $(this).find("td");
                myRows[index] = {};
                $cells.each(function(cellIndex) {
                    myRows[index][$($headers[cellIndex]).html()] = $(this).html();
                });
            });

            tblData = myRows;
            console.log(tblData[0]);
        });


        $(function(){
            $('table#tbl-my-tickets button').each(function(){
                $(this).click(function(){
                    var myid = $(this).attr("id");
                    var myidx = parseInt(myid.substr(myid.lastIndexOf('-') + 1 , myid.length ));
                    if(tblData[myidx].status === "CANCELLED") {
                        alert('Ticket is already cancelled.');
                    } else {
                        if (confirm("Cancel the ticket?")) {
                            console.log(tblData[myidx]);
                            var ticketData = {
                                "train_no" 	: parseInt(tblData[myidx].train_no),
                                "date" 	: tblData[myidx].date_journey,
                                "seat_no" 	: parseInt(tblData[myidx].seat_no),
                                "pnr" 	: tblData[myidx].pnr,
                                "status": tblData[myidx].status
                            };

                            console.log("ticket Data: ");
                            console.log(ticketData);

                            $.ajax({
                                type	: 'POST',
                                url		: 'http://localhost/train_ticket_system/utility/cancel_ticket.php',
                                data	: { cancel_credentials : JSON.stringify(ticketData) },
                                success	: function(response){

                                    console.log(response);
                                    response = JSON.parse(response);

                                    // if(response['status'] === false && response['msg'] === "NOT LOGGED IN"){
                                    //
                                    // } else {
                                    //
                                    // }
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>

</head>

<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Train System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navigation collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>


        </div>
    </nav>
</header>





<!-- !PAGE CONTENT! -->
<div class="container">

    <div style="margin-top:60px;"></div>

    <div align = "center"  style="overflow:auto">
        <table id="tbl-my-tickets" class="table table-dark"  style="overflow:auto">
            <thead class="thead-light">
            <tr>
                <th scope="col">pnr</th>
                <th scope="col">train_no</th>
                <th scope="col">source</th>
                <th scope="col">dest</th>
                <th scope="col">date_resv</th>
                <th scope="col">time_resv</th>
                <th scope="col">date_journey</th>
                <th scope="col">status</th>
                <th scope="col">seat_no</th>
                <th scope="">cancel</th>
            </tr>
            </thead>
            <tbody>

            <?php
                for ($i = 0; $i <sizeof($ticket_info) ; $i++) {
                    echo "<tr>".
                        "<td>" . $ticket_info[$i]['pnr'] . "</td> " .
                        "<td>" . $ticket_info[$i]['train_no'] . "</td> " .
                        "<td>" . $ticket_info[$i]['source'] . "</td>" .
                        "<td>" . $ticket_info[$i]['dest'] . "</td>" .
                        "<td>" . $ticket_info[$i]['date_resv'] . "</td>" .
                        "<td>" . $ticket_info[$i]['time_resv'] . "</td>" .
                        "<td>" . $ticket_info[$i]['date_journey'] . "</td>" .
                        "<td>" . $ticket_info[$i]['status'] . "</td>" .
                        "<td>" . $ticket_info[$i]['seat_no'] . "</td>" .
                        "<td>" . "<button id='btn-cancel-$i'>OK</button>" . "</td>" .
                        "</tr>";
                }
            ?>

            </tbody>
        </table>



    </div>

</div>


</body>
</html>
