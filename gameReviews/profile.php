<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Profile Editor</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <!--Site-specific styling-->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <?php
            require('connection.php');
            //check if the user is already logged in
            require('checkuser.php');

            if (!$loggedin) {
                //redirect users to log in if not already
                header("Location: login.php");
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
                        <a class="nav-item nav-link px-2" href="post.php">Create Review</a>
                        <?php
							//user profile link still needs to be customised
                        echo("<a class='nav-item nav-link px-2 active' href='profile.php'>$currentuser</a>
                        <a class='nav-item btn nav-link nav-primary bg-primary text-light px-2' href='logout.php'>Log Out</a>");
                        ?>
                        
                    </div>
                </div>
            </nav>

            <!--PHP Script to get current user details based on PHP session-->
            <?php
                $query="SELECT * FROM users WHERE username = \"$currentuser\"";
                $result = mysqli_query($con,$query);
                if ($result) {
					//get required info for the profile editor
                    $userinfo = mysqli_fetch_assoc($result);
                    $currentemail = $userinfo['email'];
                    $userid = $userinfo['id'];
                } else {
					//exit if we couldn't get the required info
                    exit("Could not get user info from database.");
                }
            ?>

            <div class="row">
                <div class="col">
                    <h1 class="display-4">Edit Profile</h1><br>
					<!--Profile editor form - also uses POST for security and file compatibility-->
                    <form action="profile.php" method="post" enctype="multipart/form-data" name="register">
                        <div class="form-group">
                            <label for="username">User Name</label>
							<!--php customises this value, but users may not change names after their account is created, so this is disabled-->
                            <input type="text" name="username" id="username" class="form-control" <?php echo("value=\"$currentuser\"");?> disabled />
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="email">Email Address</label>
							<!--PHP also used to insert existing email-->
                            <input type="email" name="email" id="email" <?php echo("value=\"$currentemail\"");?> class="form-control" required />
                            <small id="email-help" class="form-text text-muted">Your email address will never be shared with any third parties.</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="password-field">Change Password</label>
							<!--password should not be copied here, it is hashed and salted anyway-->
                            <input type="password" name="userpass" id="userpass" class="form-control">
                            <small id="password-help" class="form-text text-muted">Passwords must be at least 8 characters, contain upper case, lower case and numbers / special characters. Leave blank to keep your current password.</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="image">Profile image</label><br>
                            <input type="file" name="image" id="image" class="form-control-file">
                            <small id="image-help" class="form-text text-muted">Leave blank to keep your current profile image.</small>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="currpass-field">Confirm Current Password</label>
							<!--Ask user to confirm current password in order to make profile changes-->
                            <input type="password" name="currpass" id="currpass" class="form-control">
                            <small id="currpass-help" class="form-text text-muted">Enter your current password to confirm profile changes.</small>
                        </div>
                        <br>
                        <input type="submit" name="submit" value="Update Profile" class="btn btn-primary" />
                    </form>
                </div>
            </div>
            <br>
            <?php
                //if form submitted, take action
                if (isset($_POST['email'])) {

                    $passchange = ($_POST['userpass'] != null); //they are always set, so we need to check if they are empty strings
                    $imagechange = ($_FILES['image']['name'] != null);

                    //remove backslashes and escape special chars
                    $email = stripslashes($_REQUEST['email']);
                    $email = mysqli_real_escape_string($con, $email);
                    //does this make certain passwords unusable?
                    $userpass = stripslashes($_REQUEST['userpass']);
                    $userpass = mysqli_real_escape_string($con, $userpass);
                    $currpass = stripslashes($_REQUEST['currpass']);
                    $currpass = mysqli_real_escape_string($con, $currpass);
					//this is unused here, we can ignore it
                    //$creationdate = date("Y-m-d H:i:s");

					//check the current password is correct before updating details
                    if(password_verify($currpass, $userinfo['userpass'])) {
                        //only update image if requested by user
                        if ($imagechange) {
                            //file has been uploaded, store it
                            $image = $_FILES['image']['name'];
                            $source = $_FILES['image']['tmp_name'];
                            $target = "images/user/" . basename($image);

							//check if uplaod was successful
                            if (move_uploaded_file($source, $target)) {
                                $uploaded = true;
                            } else {
                                $uploaded = false;
                            }

                            $query = "UPDATE `users` SET `profileimg` = \"$target\" WHERE `id` = $userid";
                            $result = mysqli_query($con,$query);
                            if($result){
                                if (!$uploaded) {
									//warn user that image failed to upload
                                    echo("<br> <div class='alert alert-warning'>Your image failed to upload!</div>"); 
                                } else {
									//notify user with Bootstrap alert that file has been uploaded
									echo "<br> <div class='alert alert-success'>
									Image uploaded successfully.</div>";
								}
                            } else {
                                die(mysqli_error($con));
                            }

                        } 
                        //only update password if requested by user
                        if ($passchange) {
                            $query = "UPDATE `users` SET userpass = '".password_hash($userpass, PASSWORD_BCRYPT)."' WHERE `id` = $userid";
                            $result = mysqli_query($con,$query);
                            if($result){
                                echo "<div class='alert alert-success'>
                                Password changed successfully.</a></div>";
                            }
                        }
                        //always update e-mail
                        $query = "UPDATE `users` SET email = \"$email\" WHERE `id` = $userid";
                        $result = mysqli_query($con,$query);
                        if($result){
                            echo "<div class='alert alert-success'>
                            E-mail address successfully updated.</a></div>";
                        }
                    } else {
                        //current password was incorrect - do not change details
                        echo "<div class='alert alert-danger'>
                        <h3>Current password is incorrect.</h3>
                        </div>";
                    }
                    
                    
    
                }else{
                    //no action needed if a email address hasn't been sent
                }
            ?>

        </div><br><br>
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