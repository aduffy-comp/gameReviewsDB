<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Create Review - Mod Reviews</title>
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
                //redirect to login page if not logged in
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
                        <a class="nav-item nav-link px-2 active" href="post.php">Create Review</a> <!--active makes the text darker and bolder-->
                        <?php
						//even though user is logged in, we need to customise profile link name
                        echo("<a class='nav-item nav-link px-2' href='profile.php'>$currentuser</a>
                        <a class='nav-item btn nav-link nav-primary bg-primary text-light px-2' href='logout.php'>Log Out</a>");
                        ?>
                    </div>
                </div>
            </nav>
            <?php
                //if form submitted, insert values into database
                if (isset($_POST['modid'])) {
                    //remove backslashes and escape special chars
                    $modid = stripslashes($_POST['modid']);
                    //remove any HTML tags - prevent insering HTML or JavaScript into a review page
                    $modid = filter_var($modid, FILTER_SANITIZE_STRING);
                    $rating = stripslashes($_POST['rating']);
                    $rating = filter_var($rating, FILTER_SANITIZE_STRING);
                    $title = stripslashes($_POST['title']);
                    $title = filter_var($title, FILTER_SANITIZE_STRING);
                    $reviewtext = stripslashes($_POST['reviewtext']);
                    $reviewtext = filter_var($reviewtext, FILTER_SANITIZE_STRING);

                    //check for valid mod id
                    $result = mysqli_query($con, "SELECT * FROM mods WHERE id = '$modid'");
                    $modidresult = mysqli_fetch_assoc($result);
                    //check for unique title
                    $result = mysqli_query($con, "SELECT * FROM reviews WHERE title = '$title'");
                    $titleresult = mysqli_fetch_assoc($result);
                    //check user is not banned
                    $result = mysqli_query($con, "SELECT userbanned FROM users WHERE username = '$currentuser'");
                    $bannedresult = mysqli_fetch_assoc($result);
					
					//act on check results
                    if ($bannedresult['userbanned'] == 1) {
						//banned users may not post reviews
                        echo("<div class='alert alert-danger'>You are banned from posting on Mod Reviews! For details, or to appeal your ban,<a class='alert-link' href='mailto:bans@example.co.uk?subject=Ban%20Appeal%20-%20" . $currentuser . "'> please contact a moderator.</a></div>");
                    } else if (!$modidresult) {
						//the mod needs to exist in the database to get a valid review
                        echo("<div class='alert alert-danger'>That mod does not exist.</div>");
                    } else if ($titleresult) {
						//review titles must also be unique - should this limit be lifted?
                        echo("<div class='alert alert-danger'>A review with that title already exists. Please choose a different title.</div>");
                    } else {
                        //checks passed, proceed to upload review
						
						//file has been uploaded, store it
						$image = $_FILES['image']['name'];
						$source = $_FILES['image']['tmp_name'];
						$target = "images/review/" . basename($image);

						//determine whether the file is saved successfully
						if (move_uploaded_file($source, $target)) {
							$uploaded = true;
						} else {
							$uploaded = false;
						}

						//get game, publisher and developer ID values from DB
						//these are then added to the review database as needed
						$sql = "SELECT gameID FROM mods WHERE id = '$modid'";
						$result = mysqli_query($con, $sql);
						$gameresult = mysqli_fetch_assoc($result);
						$gameid = $gameresult['gameID'];
						$sql = "SELECT developerID FROM mods WHERE id = '$modid'";
						$result = mysqli_query($con, $sql);
						$devresult = mysqli_fetch_assoc($result);
						$developerid = $devresult['developerID'];
						$sql = "SELECT publisherID FROM game WHERE id = '$gameid'";
						$result = mysqli_query($con, $sql);
						$pubresult = mysqli_fetch_assoc($result);
						$publisherid = $pubresult['publisherID'];


						//get poster's ID from their username
						$sql = "SELECT id FROM users WHERE username = '$currentuser'";
						$result = mysqli_query($con, $sql);
						$userresult = mysqli_fetch_assoc($result);
						$userid = $userresult['id'];

						//add the review to the database
						$query = "INSERT into `reviews` (title, reviewtext, reviewimg, rating, userid, gameid, publisherid, modid) VALUES ('$title', '$reviewtext', '$target', '$rating', '$userid', '$gameid', '$publisherid', '$modid')";
						$result = mysqli_query($con,$query);
						if($result){
							//get the newly-uploaded review's id
							$query = "SELECT id FROM `reviews` WHERE `title` = '$title'";
							$result = mysqli_query($con, $query);
							$idresult = mysqli_fetch_assoc($result);
							$targetid = $idresult['id'];
							if (!$uploaded) {
								//warn the user about the failed image upload
							   echo("<br> <div class='alert alert-warning'>Image failed to upload. Unfortunately, you will not be able to add one later without deleting this review. <br> <a class='btn' href='review.php?id=" . $targetid . "'>View Post</a></div>"); 
							} else {
								//automatically redirect if the upload was successful
								//nasty JS hack to work around not being able to send location header whilst sending document
								echo("<script>window.location.replace('./review.php?id=" . $targetid . "');</script>");
								//this crashes the PHP instantly
								//header("Location: review.php?id=$targetid");
							}
							
						} else {
							//warn the user the review failed to upload
							echo("<br> <div class='alert alert-danger'>Review failed to upload!</div>");
						}

                        
                    }
    
                }else{
                    //no action needed if a mod ID hasn't been sent
                }
            ?>
            <div class="row">
                <div class="col">
                    <h1 class="display-4">Create a Review</h1><br>
                    <form action="post.php" method="post" enctype="multipart/form-data" name="post">
                        <div class="form-group">
                            <label for="modid">Mod to review:</label>
                            <!--TODO: make this a dropdown with all valid mods!-->
                            
                            <select name="modid" id="modid" class="form-control">
								<!--PHP to populate the selection of available mods
									This selection could get unwieldy with a large database-->
                                <?php
                                    $sql = "SELECT `id`, `name` FROM mods";
                                    if($results = mysqli_query($con, $sql)) {
                                        if(mysqli_num_rows($results) > 0) {
                                            while($row = mysqli_fetch_array($results)) {
                                                //add the mod to the valid options
                                                echo("<option value='" . $row['id'] . "'>" . $row['name'] ."</option>");
                                            }
                                        } else {
											//this should never occur in normal use, unless all mods have been deleted from the database
                                            exit("No mods in database! Please contact a site admin.");
                                        }
                                    } else {
										//If there was a mySQL error, terminate immediately and print the message
                                        die(mysqli_error($con));
                                    }
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="rating">Your rating:</label>
							<!--number input used with valid range specified, will not accept other values-->
                            <input type="number" min="0" max="10" name="rating" id="rating" placeholder="0 to 10" class="form-control" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="title">Review title</label>
                            <input type="text" name="title" id="title" placeholder="Enter a review title..." class="form-control" required />
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="reviewtext">Review text</label>
                            <!--This tag must be on a single line to avoid whitespace in the text box-->
                            <textarea name="reviewtext" id="reviewtext" placeholder="Enter your review here..." class="form-control" rows="15" required></textarea>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="image">Review Image</label><br>
							<!--Images are required for reviews-->
                            <input type="file" name="image" id="image" class="form-control-file" required />
                        </div>
                        <br>
                        <input type="submit" name="submit" value="Post" class="btn btn-primary" />
                    </form>
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