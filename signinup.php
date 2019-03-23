<?php
	session_start();
	if (array_key_exists('username' , $_SESSION) && isset($_SESSION['username'])) {
        // if user has already logged in
        header("Location: /train_ticket_system/user/home.php");
    }
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


        <script>
            var toggleSignInSignUp = function(){
                $('div#signinContainer').toggle();
                $('div#signupContainer').toggle();
            }

            $(document).ready(function(){
                $('div#signupContainer').hide();
                $('#invalidLoginResponse').hide();
            });

            $(function(){
                $('a#showSignIn').click(toggleSignInSignUp);
                $('a#showSignUp').click(toggleSignInSignUp);
                $('#invalidLoginResponse .close').click(function () {$('#invalidLoginResponse').toggle()});


                $('#btn-signin').click(function(){
                    var username = $('#signin-username').val();
                    var password = $('#signin-password').val();
                    if (!validateUsername(username)){
                        alert('Enter a valid user.');
                    } 
                    // else if (!validatePassword(password)) {
                    //     alert('Password [6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter]');
                    // }
                     else {
                        var loginData = {
                            "username" 	: username,
                            "password"	: password
                        };
                        $.ajax({
                            type	: 'POST',
                            url		: 'http://localhost/train_ticket_system/utility/authenticate.php',
                            data	: { login_credentials : JSON.stringify(loginData) },
                            success	: function(response){

                                console.log(response);
                                response = JSON.parse(response);

                                if(response['status'] === true || response['msg'] === "LOGGED IN"){
                                    window.location.replace("./user/home.php");
                                } else {
                                    $('#invalidLoginResponse').show();
                                }
                            }
                        });
                    }
                });

                $('#btn-signup').click(function(){
                    alert('kaushal signup');
                    console.log("i m here");

                    var username = $('#signup-username').val();
                    var name = $('#signup-name').val();
                    var aadhar = $('#signup-aadhar').val();
                    var contact = $('#signup-contact').val();
                    var password = $('#signup-password').val();
                    var confPassword = $('#signup-conf-password').val();
                    
                    if (!validateUsername(username)){
                        alert('Enter a valid user.');
                    } else if (!validateName(name)){
                        alert('Enter a valid name.');
                    } else if (!validateEmail(aadhar)) {
                        alert('Enter a valid 12-Digit aadhar number.');
                    } else if (!validateContact(contact)){
                        alert('Enter a valid contact number.');
                    } else if (password !== confirmPassword) {
                        alert('Passwords do not match.');
                    } else if (!validatePassword(password)) {
                        alert('Password [6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter]');
                    } else {
                    
                        var signUpData = {
                            "username" 	: username,
                            "email"     : email,
                            "password"  : password                        
                        };

                        $.ajax({
                            type	: 'POST',
                            url		: 'http://localhost/train_ticket_system/utility/register.php',
                            data	: {request : JSON.stringify(signUpData)},
                            success	: function(response){
                                response = JSON.parse(response);
                                console.log(response);
                            }
                        });
                    }
                });



            });

            

        </script>        

        <style>
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>

    </head>

    <body>

        <div id="signupContainer" class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-9 mx-auto">
                    <div class="card card-signin flex-row my-5">
                        <div class="card-img-left d-none d-md-flex">
                            <!-- Background image for card set in CSS! -->
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center">Register</h5>
                            <form class="form-signin">
                                <div class="form-label-group">
                                    <input type="text" id="signup-username" class="form-control" placeholder="Unique identification name" required autofocus>
                                    <label for="signup-username">Username</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="signup-name" class="form-control" placeholder="What we may call you?" required>
                                    <label for="signup-name">Your Name</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="number" id="signup-aadhar" class="form-control" placeholder="12 Digit Aadhar Number" required>
                                    <label for="signup-aadhar">12-Digit Aadhar</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="signup-contact" class="form-control" placeholder="To contact you." required>
                                    <label for="signup-contact">Contact Info</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="password" id="signup-password" class="form-control" placeholder="Password" required>
                                    <label for="signup-password">Password</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="password" id="signup-conf-password" class="form-control" placeholder="Password" required>
                                    <label for="signup-conf-password">Confirm password</label>
                                </div>
                                <hr>
                                <button id="btn-signup" class="btn btn-lg btn-primary btn-block text-uppercase" type="button">Register</button>
                                <a id="showSignIn" class="d-block text-center mt-2 small" href='#'>Sign In</a>
                                
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="signinContainer" class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-9 mx-auto">
                    <div class="card card-signin flex-row my-5">
                        <div class="card-img-left d-none d-md-flex">
                            <!-- Background image for card set in CSS! -->
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center">Welcome Back!</h5>
                            <form class="form-signin">

                                <div id="invalidLoginResponse" class="alert alert-danger">
                                    <button type="button" class="close">&times;</button>
                                    <strong>Error!</strong> Invalid login credentials.
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="signin-username" class="form-control" placeholder="Username" required autofocus>
                                    <label for="signin-username">Username</label>
                                </div>

                                

                                <div class="form-label-group">
                                    <input type="password" id="signin-password" class="form-control" placeholder="Password" required>
                                    <label for="signin-password">Password</label>
                                </div>

                                <hr>

                                <button id="btn-signin" class="btn btn-lg btn-primary btn-block text-uppercase" type="button">Sign In</button>
                                <a id="showSignUp" class="d-block text-center mt-2 small" href='#'>Sign Up</a>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
