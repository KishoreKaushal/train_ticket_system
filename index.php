<?php
	session_start();
	if (array_key_exists('uid' , $_SESSION) && isset($_SESSION['uid'])) {
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
            });



            $(function(){
                $('a#showSignIn').click(toggleSignInSignUp);
                $('a#showSignUp').click(toggleSignInSignUp);
            });

            $('#btn-login').click(function(){
                var email = $('#').val();
                var passwd = $('#').val();
                if (!validateEmail(email)) {
                    
                } else if (!validatePassword(passwd)) {
                    
                } else {
                    var loginData = {
                        "email" 	: email,
                        "password"	: passwd
                    };
                    $.ajax({
                        type	: 'POST',
                        url		: 'http://localhost/ctf-iitpkd/utility/authenticate.php',
                        data	: { login_credentials : JSON.stringify(loginData) },
                        success	: function(response){
                            response = JSON.parse(response);
                            
                            console.log(response);
                            if(response['status'] === true){
                                window.location.replace("./user/home.php");
                            } else {
                                
                            }
                        }
                    });
                }
            });
				
				


            $('#btn-signup').click(function(){

                if (!validateEmail(email)) {
                
                } else if (passwd !== confirmPasswd) {
                
                } else if (!validatePassword(passwd)) {
                
                } else {
                
                    var signUpData = {
                        "email" 	: email,
                        
                    };

                    $.ajax({
                        type	: 'POST',
                        url		: 'http://localhost/ctf-iitpkd/utility/register.php',
                        data	: {request : JSON.stringify(signUpData)},
                        success	: function(response){
                            response = JSON.parse(response);
                            console.log(response);
                        }
                    });
                }
            });

        </script>        

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
                                    <input type="text" id="signup-username" class="form-control" placeholder="Username" required autofocus>
                                    <label for="signup-username">Username</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="email" id="signup-email" class="form-control" placeholder="Email address" required>
                                    <label for="signup-email">Email address</label>
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
                                <button id="#btn-signup" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Register</button>
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

                                <div class="form-label-group">
                                    <input type="text" id="signin-username" class="form-control" placeholder="Username" required autofocus>
                                    <label for="signin-username">Username</label>
                                </div>

                                

                                <div class="form-label-group">
                                    <input type="password" id="signin-password" class="form-control" placeholder="Password" required>
                                    <label for="signin-password">Password</label>
                                </div>

                                <hr>

                                <button id="#btn-login" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign In</button>
                                <a id="showSignUp" class="d-block text-center mt-2 small" href='#'>Sign Up</a>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
