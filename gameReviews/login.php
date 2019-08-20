<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Login to Mod Reviews</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <!--Site-specific styling-->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <br><br><br><!--nasty hack to avoid navbar obstructing page content-->
            <!--Main site navigation-->
            <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
                <!--Main site logo, always visible-->
                <a class="navbar-brand" href="index.php">Mod Reviews</a>
                <!--Toggler used to expand navbar on mobile devices-->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--Collapsable content-->
                <div class="collapse navbar-collapse" id="navbar-content">
                    <!--Thanks to: https://www.codeply.com/go/t6EpPvoacQ/bootstrap-4-navbar-justify -->
                    <div class="navbar-nav nav-fill w-100 align-items-start"> <!--nav-fill, w-100 and align-items-start stretch the elements to fill the navbar-->
                        <a class="nav-item nav-link px-2" href="index.php">Home</a>
                        <a class="nav-item nav-link px-2" href="games.php">Games</a>
                        <a class="nav-item nav-link px-2" href="mods.php">Mods</a>
						<!--No link customisation needed, the user can only get her logged out-->
                        <a class="nav-item nav-link active px-2" href="login.php">Login</a>
                        <a class="nav-item nav-link nav-primary btn bg-primary text-light px-2" href="register.php">Register</a>
                    </div>
                </div>
            </nav>
        
            
            <div class="row">
                <div class="col">
                    <h1 class="display-4">Log In</h1><br>
                    <form action="" method="post" name="login">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" class = "form-control" required />
                        </div>
                        <div class="form-group">
                            <input type="password" name="userpass" placeholder="Password" class = "form-control" required />
                        </div>
                        <input name="submit" type="submit" value="Login" class="btn btn-primary"/>
                    </form>
                    <br>
                    <?php
                        require('connection.php');
                        require('checkuser.php');
                        if($loggedin) {
                            //user is already logged in - redirect them
                            header("Location: index.php");
                        }
                        // If form submitted, check credentials.
                        if (isset($_POST['username'])){
                                // removes backslashes
                            $username = stripslashes($_REQUEST['username']);
                                //escapes special characters in a string
                            $username = mysqli_real_escape_string($con,$username);
                            $userpass = stripslashes($_REQUEST['userpass']);
                            $userpass = mysqli_real_escape_string($con,$userpass);
                            //Checking is user existing in the database or not
                            $query = "SELECT * FROM `users` WHERE username='$username'";
                            $result = mysqli_query($con,$query) or die(mysql_error());
                            $row = mysqli_fetch_assoc($result);
							//check if password is correct by hash & compare
                            if($row && password_verify($userpass, $row['userpass'])){
								//set a PHP session variable to signify which user is logged in on this browser
                                $_SESSION['username'] = $username;
                                // Redirect user to home page
                                header("Location: index.php");
                            }else{
								//warn user of incorrect password
                                echo "<div class='alert alert-danger'>
                                <h3>Username/password is incorrect.</h3>
                                </div>";
                            }
                        }else{

                        }
                    ?>
					<!--Informational alert style from Bootstrap used to direct new users-->
                    <div class="alert alert-primary"> Not registered yet? <a href='register.php'>Register Here</a></div>
                </div>
            </div>
        </div>
        <br><br><br>
        <!--Website footer - positioned here to fill the entire width-->
        <nav class="navbar fixed-bottom navbar-light bg-light">
            <span class="text-muted">Website created by Alexander Duffy</span>
        </nav>

        <!--***Included JavaScript files beyond here***-->
        <!--JQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!--Bootstrap - used for responsiveness and styling-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js" integrity="sha384-lZmvU/TzxoIQIOD9yQDEpvxp6wEU32Fy0ckUgOH4EIlMOCdR823rg4+3gWRwnX1M" crossorigin="anonymous"></script>
    </body>
</html>