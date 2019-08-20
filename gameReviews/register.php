<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Registration</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <!--Site-specific styling-->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <?php
            require('connection.php');
            //check if the user is already logged in
            require('checkuser.php');

            if ($loggedin) {
				//inform the user they are already logged in, so they cannot create an account in this state
                echo("<p>You are currently logged in. To create a new account, you must first <a href='logout.php'>log out.</a></p>");
                echo("<p>Alternatively, return to the <a href='index.php'> home page.</a></p>");
                exit();
            }
        ?>
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
						<!--No need to customise links, user can reach this only when logged out-->
                        <a class="nav-item nav-link px-2" href="login.php">Login</a>
                        <a class="nav-item btn nav-secondary bg-secondary nav-link text-light active px-2" href="register.php">Register</a>
                    </div>
                </div>
            </nav>

            <div class="row">
                <div class="col">
                    <h1 class="display-4">Registration</h1><br>
					<!--POST used to hide details from URL bar and allow for submission of files-->
                    <form action="register.php" method="post" enctype="multipart/form-data" name="register">
                        <div class="form-group">
							<!--Label to describe function of form controls-->
                            <label for="username">User Name</label>
                            <input type="text" name="username" placeholder="Enter a user name..." id="username" class="form-control" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="email">Email Address</label>
							<!--email type allows browser to check e-mail format is correct-->
                            <input type="email" name="email" id="email" placeholder="example@example.com" class="form-control" required />
							<!--Small text used for disclamer, text-muted makes text more subtle-->
                            <small id="email-help" class="form-text text-muted">Your email address will never be shared with any third parties.</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="password-field">Password</label>
							<!--Password type hides entered characters-->
                            <input type="password" name="userpass" id="password_field" class="form-control" required />
                            <small id="email-help" class="form-text text-muted">Passwords must be at least 8 characters, contain upper case, lower case and numbers / special characters.</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="image">Profile image (optional)</label><br>
							<!-- file type allows for the upload of files -->
                            <input type="file" name="image" id="image" class="form-control-file">
                        </div>
                        <br>
                        <input type="submit" name="submit" value="Register" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <?php
                //if form submitted, insert values into database
                if (isset($_POST['username'])) {
                    //remove backslashes and escape special chars
                    $username = stripslashes($_REQUEST['username']);
                    $username = mysqli_real_escape_string($con, $username);
                    $email = stripslashes($_REQUEST['email']);
                    $email = mysqli_real_escape_string($con, $email);
                    //does this make certain passwords unusable?
					//changing it could break some existing passwords, better not change this
                    $userpass = stripslashes($_REQUEST['userpass']);
                    $userpass = mysqli_real_escape_string($con, $userpass);
					//get the current date and time
                    $creationdate = date("Y-m-d H:i:s");

                    //check for duplicate user name or e-mail
                    $result = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
                    $nameresult = mysqli_fetch_assoc($result);
                    $result = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
                    $emailresult = mysqli_fetch_assoc($result);
                    if ($nameresult) {
						//ask user to use a different name
                        echo("<div class='alert alert-danger'>That user name is already in use. Please choose a different name.</div>");
                    } else if ($emailresult) {
						//inform user that no more than 1 account can be registered to a given email address
                        echo("<div class='alert alert-danger'>You may not register more than one account with any given email address.</div>");
                    } else {
                        //checks passed, proceed to register
                        //file issset check always returns true - I should fix this!
                        if (isset($_FILES['image']['name'])) {
                            //file has been uploaded, store it
                            $image = $_FILES['image']['name'];
                            $source = $_FILES['image']['tmp_name'];
                            $target = "images/user/" . basename($image);
							
							//determine if file uploaded successfully
							//also partial workaround for isset check not working
                            if (move_uploaded_file($source, $target)) {
                                $uploaded = true;
                            } else {
                                $uploaded = false;
                            }
							//add the new user to the database in the users table
                            $query = "INSERT into `users` (username, userpass, email, creationdate, profileimg) VALUES ('$username', '".password_hash($userpass, PASSWORD_BCRYPT)."', '$email', '$creationdate', '$image')";
                            $result = mysqli_query($con,$query);
                            if($result){
                                if (!$uploaded) {
									//warn user they didn't upload an image or it failed - we cannot tell the difference with current method
									//use Bootstrap alert styling
                                   echo("<br> <div class='alert alert-warning'>You did not select an image, or it failed to upload.
                                   You can add it later by visiting your profile page.</div>"); 
                                }
                                echo "<br> <div class='alert alert-success'>
                                You are registered successfully.
                                Click here to <a href='login.php'>log in.</a></div>";
                            }

                        } else {
                            //no file uploaded, proceed without it
							//in theory, this would get executed if the isset check wasn't broken
                            $query = "INSERT into `users` (username, userpass, email, creationdate) VALUES ('$username', '".password_hash($userpass, PASSWORD_BCRYPT)."', '$email', '$creationdate')";
                            $result = mysqli_query($con,$query);
                            if($result){
                                echo "<div class='alert alert-success'>
                                You are registered successfully.
                                Click here to <a href='login.php'>log in.</a></div>";
                            }
                        }
                    }
    
                }else{
                    //no action needed if a user name hasn't been sent
                }
            ?>

        </div><br><br><br>
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