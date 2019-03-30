<?php
    require_once "./utility/dbconnect_public.php"; 
    $sql = "select distinct station_code , station_name from station;";

    $st_code_arr = array();
    $st_name_arr = array();
    $train_between_stations = array();
    $trains_found = NULL;

    $query_result = $DBcon_public->query($sql);
    while ($row = $query_result->fetch_array()){
        array_push($st_code_arr, $row[0]);
        array_push($st_name_arr, $row[1]);
    }

    $sql = "select distinct train_no, train_name from train;";

    $train_code_arr = array();
    $train_name_arr = array();
    $train_info = array();
    $available_seats = array();

    $query_result = $DBcon_public->query($sql);
    while ($row = $query_result->fetch_array()){
        array_push($train_code_arr, $row[0]);
        array_push($train_name_arr, $row[1]);
    }

    $sql = "select (current_date()+INTERVAL 1 DAY) as min_date , (current_date() + INTERVAL 10 DAY) as max_date;";
    $query_result = $DBcon_public->query($sql);
    $row = $query_result->fetch_array();
    $min_date = $row['min_date'];
    $max_date = $row['max_date'];

    if (!empty($_POST)) {
        if (isset($_POST['destination'], $_POST['source'], $_POST['dept-date'])){
            $dest = $DBcon_public->real_escape_string(strip_tags($_POST['destination']));
            $src = $DBcon_public->real_escape_string(strip_tags($_POST['source']));
            $dept_date = $DBcon_public->real_escape_string(strip_tags($_POST['dept-date']));
            $dept_date = strtotime($dept_date);

            if ($dept_date) {
                $dept_date = date('Y-m-d', $dept_date);
            } else {
                echo 'Invalid Date: ' . $_POST['dept-date'];
                die(0);
            }

            $sql = "call train_between_stations('" . $src . "' , '" . $dest . "');";

            $query_result = $DBcon_public->query($sql);

            while ($row = $query_result->fetch_array()){
                array_push($train_between_stations, $row);
            }

            $query_result->free_result();
            $DBcon_public->next_result();

            foreach($train_between_stations as $tr) {
                $trno = $tr['train_no'];
                $temp_sql = "call available_seat_list($trno, '$dept_date', '$src', '$dest')";
                $temp_result = $DBcon_public->query($temp_sql);
                if (!$temp_result) {
                    echo "$DBcon_public->error";
                } else {
                    array_push($available_seats, $temp_result->num_rows);
                }

                $temp_result->free_result();
                $DBcon_public->next_result();
            }

            $trains_found = true;
        } else if (isset($_POST['trainNum'])){

            $train_number = $DBcon_public->real_escape_string(strip_tags($_POST['trainNum']));
            $sql = "call train_details(" . $train_number . ")";

            $query_result = $DBcon_public->query($sql);

            while ($row = $query_result->fetch_array()){
                array_push($train_info, $row);
            }
        }
    }

    $DBcon_public->close();
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
        function trainObj(tr_data_arr) {
            this.trno = parseInt(tr_data_arr[0]);
            this.fare = parseInt(tr_data_arr[5]);
            this.availSeats = parseInt(tr_data_arr[6]);
            this.qty = parseInt(tr_data_arr[7]);
        }

        function validateSrcDestForm() {
            if($('#source').val() !== $("#destination").val()) {
                // alert("Source and destination are not same");
                return true;
            } else {
                alert("Source and destination can't be same");
                return false;
            }
        }

        $(function(){
            $("input[name='btn-book']").click(function(){
                var $tr = $(this).closest("tr");
                var $tds = $tr.find("td");
                var data = [];
                $.each($tds, function() {
                    data.push($(this).text());
                    console.log($(this).text());
                });
                var qty = $tr.children("td").eq(7).children("input[type='number']:first-child").val();
                data.pop();
                data.push(qty);
                var tr_obj = new trainObj(data);

                var data = {
                    "train_no" : tr_obj.trno,
                    "qty" : tr_obj.qty,
                    "fare" : tr_obj.fare,
                    "availSeats" : tr_obj.availSeats,
                    "src" : <?php echo "'$src'"?>,
                    "dest" : <?php echo "'$dest'"?>,
                    "date" : <?php echo "'$dept_date'" ?>
                };

                console.log(data);

                if(validateBookingCredentials(data)){
                    $.ajax({
                        type	: 'POST',
                        url		: 'http://localhost/train_ticket_system/utility/booking_script.php',
                        data	: { booking_credentials : JSON.stringify(data) },
                        success	: function(response){

                            console.log(response);
                            response = JSON.parse(response);

                            if(response['status'] === true){
                                alert("Booking succesful");
                            } else if (response['status'] === false && response['msg'] == "NOT LOGGED IN"){
                                alert("Please login first!!");
                                window.location.replace("./signinup.php");
                            } else {
                                alert($response['msg']);
                            }
                        }
                    });
                } else {
                    alert('Error : wrong booking credentials');
                }
            });
        });

    </script>

    <?php
        if (isset($_POST['trainNum'])){
            echo "<script>$(function(){ $('#nav-train-info').click(); });</script>";
        }

    ?>

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
              <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./information.php">Booking</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./information.php">Information</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./profile.php">My Profile</a>
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
    

<div align = "center">
    <!-- <strong>HOME</strong> -->
    <br>
    <marquee scrollamount="10" style = "color: red; font-size : 150%"> New train started by Railway minister Himanshu Rai </marquee>
    <h1>Train Ticket Booking</h1>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a id="nav-find-trains" class="nav-link active" data-toggle="tab" href="#findTrains">Find trains</a>
        </li>
        <li class="nav-item">
            <a id="nav-train-info" class="nav-link" data-toggle="tab" href="#trainInfo">Train Info</a>
        </li>
    </ul>


    <div class="tab-content">
        <div class="tab-pane container active" id="findTrains">

            <form action= <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> onsubmit="return validateSrcDestForm()" method="post">

                <label for="source">Source: </label>
                <select name="source" id="source">
                    <?php 
                        for ($i=0; $i < sizeof($st_code_arr); $i = $i + 1) {
                            echo ("<option value='" . $st_code_arr[$i] . "'>". $st_name_arr[$i] ."</option>");
                        }
                    ?>    
                </select>
                
                <br/>
                <label for="destination">Destination: </label>
                <select name="destination" id="destination">
                    <?php 
                        for ($i=0; $i < sizeof($st_code_arr); $i = $i + 1) {
                            echo ("<option value='" . $st_code_arr[$i] . "'>". $st_name_arr[$i] ."</option>");
                        }
                    ?>
                </select>
                <br/>
                <label for="dept-date">Dept. Date: </label>

                <input type="date" name="dept-date" id="dept-date" min=<?php echo "$min_date" ?> max=<?php echo "$max_date"?> value=<?php echo "$min_date" ?> required>
                <div class="col-sm-2" style="padding-top:10px">
                    <input id="btn-findtrains" class="btn btn-primary btn-block" type="submit">Find Trains</button>
                </div>
            </form>

            <?php
                if (sizeof($train_between_stations) != 0) {

                    echo "<h2>Trains Running on: $dept_date </h2><br/>";
                    // echo json_encode($train_between_stations);
                    echo "<table style='width:100%' sborder=2>";
                    echo "<tr>
                            <th>TrainNo.</th>
                            <th>TrainName</th>
                            <th>SchedDept</th>
                            <th>SchedArr</th>
                            <th>Dist.</th>
                            <th>Fare</th>
                            <th>Avail. Seats</th>
                            <th>Qty.</th>
                           
                        </tr>";

                    for ($i = 0; $i <sizeof($train_between_stations) ; $i++) {
                        echo "<tr>".
                            "<td>" . $train_between_stations[$i]['train_no'] . "</td> " .
                            "<td>" . $train_between_stations[$i]['train_name'] . "</td> " .
                            "<td>" . $train_between_stations[$i]['sched_dept'] . "</td>" .
                            "<td>" . $train_between_stations[$i]['sched_arr'] . "</td>" .
                            "<td>" . $train_between_stations[$i]['distance_travelled'] . "</td>" .
                            "<td>" . intval($train_between_stations[$i]['total_fare']) . "</td>" .
                            "<td>" . $available_seats[$i] . "</td>" .
                            "<td>" . "<input type='number' id='ticket-qty-". $i ."' name='ticket-qty' value=1 min='1' max='$available_seats[$i]'> " .
                                     "<input type=\"button\" name=\"btn-book\" value=\"✓ GetNow\">" . "</td>".
                            "</tr>";
                    }

                    echo "</table>";
                } else if (!is_null($trains_found)){
                    echo "<h1>No trains found.</h1>";
                }
            ?>

        </div>
        <div class="tab-pane container fade" id="trainInfo">
            <form action= <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> onsubmit="return true" method="post">
                <label for="trainNum">Train Name: </label>
                <select name="trainNum" id="trainNum">
                    <?php
                        for ($i=0; $i < sizeof($train_code_arr); $i = $i + 1) {
                            $train_name = $train_code_arr[$i] . " - " . $train_name_arr[$i];
                            echo ("<option value='" . $train_code_arr[$i] . "'>". $train_name ."</option>");
                        }
                    ?>
                </select>

                <div class="col-sm-2" style="padding-top:10px">
                    <input id="btn-traininfo" class="btn btn-primary btn-block" type="submit">Get Train Info</button>
                </div>
            </form>

            <?php
                if (sizeof($train_info) != 0) {

                    echo "<table style='width:100%' sborder=2>";
                    echo "<tr>
                                <th>StopIdx</th>
                                <th>StnCode</th>
                                <th>StnName</th>
                                <th>SchedArr</th>
                                <th>SchedDept</th>
                                <th>Dist</th>
                            </tr>";

                    for ($i = 0; $i <sizeof($train_info) ; $i++) {
                        echo "<tr>".
                            "<td>" . $train_info[$i]['stop_idx'] . "</td> " .
                            "<td>" . $train_info[$i]['station_code'] . "</td> " .
                            "<td>" . $train_info[$i]['station_name'] . "</td>" .
                            "<td>" . $train_info[$i]['sched_arr'] . "</td>" .
                            "<td>" . $train_info[$i]['sched_dept'] . "</td>" .
                            "<td>" . $train_info[$i]['distance'] . "</td>" .
                            "</tr>";
                    }

                    echo "</table>";
                } else if(isset($_POST['trainNum'])) {
                    echo "<h1>Unknown Error</h1>";
                }
            ?>
        </div>
    </div>


</div>

<!-- Footer -->
<footer class="page-footer font-small cyan darken-3 fixed-bottom" style="align:bottom; z-index: -1">
    <!-- Footer Elements -->
    <div class="container">
      <!-- Grid row-->
      <div class="row">
        <!-- Grid column -->
        <div class="col-md-12 text-center">
          <div class="mt-5 mb-1">
            <!-- Facebook -->
            <a class="fb-ic">
              <i class="fa fa-facebook fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
            </a>
            <!-- Twitter -->
            <a class="tw-ic">
              <i class="fa fa-twitter fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
            </a>
            <!-- Google +-->
            <a class="gplus-ic">
              <i class="fa fa-google-plus fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
            </a>
            <!--Linkedin -->
            <a class="li-ic">
              <i class="fa fa-linkedin fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
            </a>
            <!--Instagram-->
            <a class="ins-ic">
              <i class="fa fa-instagram fa-lg white-text mr-md-5 mr-3 fa-2x"> </i>
            </a>
            <!--Pinterest-->
            <a class="pin-ic">
              <i class="fa fa-pinterest fa-lg white-text fa-2x"> </i>
            </a>
          </div>
        </div>
        <!-- Grid column -->

      </div>

    <div class="footer-copyright text-center m-2">Made with &hearts; in IIT-PKD</div>
    </div>

  </footer>

</div>


</body>
</html>
