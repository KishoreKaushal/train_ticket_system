<?php
    require_once "./utility/dbconnect_public.php"; 
    $sql = "select distinct station_code , station_name from station;";
    
    $st_code_arr = array();
    $st_name_arr = array();
    
    $train_info = array();
    $trains_found = NULL;


    $query_result = $DBcon_public->query($sql);
    while ($row = $query_result->fetch_array()){
        array_push($st_code_arr, $row[0]);
        array_push($st_name_arr, $row[1]);
    }


    if (!empty($_POST)) {
        
        $dest = $DBcon_public->real_escape_string(strip_tags($_POST['destination'])); 
        $src = $DBcon_public->real_escape_string(strip_tags($_POST['source'])); 

        echo $dest.$src;

        $sql = "call train_between_stations('" . $src . "' , '" . $dest . "')";

        echo $sql;


        $query_result = $DBcon_public->query($sql);

        while ($row = $query_result->fetch_array()){
           array_push($train_info, $row);
        }

        $trains_found = true;
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

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
        function validateSrcDestForm() {
            if($('#source').val() !== $("#destination").val()) {
                // alert("Source and destination are not same");
                return true;
            } else {
                alert("Source and destination can't be same");
                return false;
            }
        }
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
    

<div align = "center">
    <!-- <strong>HOME</strong> -->
    <br>
    <marquee scrollamount="10" style = "color: red; font-size : 150%"> New train started by Railway minister Himanshu Rai </marquee>
    <h1>Retrieve Information</h1>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#findTrains">Find trains</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#trainInfo">Train Info</a>
        </li>
    </ul>


    <div class="tab-content">
        <div class="tab-pane container active" id="findTrains">
            <h3>Let's find some train.</h3>
            <br/>

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
                <div class="col-sm-2" style="padding-top:10px">
                    <input id="btn-findtrains" class="btn btn-primary btn-block" type="submit">Find Trains</button>
                </div>
            </form>

            <?php
                if (sizeof($train_info) != 0) {

                    // echo json_encode($train_info);
                    echo "<table style='width:100%' sborder=2>";
                    echo "<tr>
                            <th>TrainNo.</th>
                            <th>TrainName</th>
                            <th>SchedDept</th>
                            <th>SchedArr</th>
                            <th>Dist.</th>
                            <th>Fare</th>
                        </tr>";

                    for ($i = 0; $i <sizeof($train_info) ; $i++) {
                        echo "<tr>".
                            "<td>" . $train_info[$i]['train_no'] . "</td> " .
                            "<td>" . $train_info[$i]['train_name'] . "</td> " .
                            "<td>" . $train_info[$i]['sched_dept'] . "</td>" .
                            "<td>" . $train_info[$i]['sched_arr'] . "</td>" .
                            "<td>" . $train_info[$i]['distance_travelled'] . "</td>" .
                            "<td>" . $train_info[$i]['total_fare'] . "</td>" .
                            "</tr>";
                    }

                    echo "</table>";
                } else if (!is_null($trains_found)){
                    echo "<h1>No trains found.</h1>";
                }
            ?>

        </div>
        <div class="tab-pane container fade" id="trainInfo">Let's find train information for you.</div>
    </div>


</div>

<!-- Footer -->
<footer class="page-footer font-small cyan darken-3 fixed-bottom" style="align:bottom;">
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
