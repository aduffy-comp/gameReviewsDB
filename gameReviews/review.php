<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!--Login checks and title setup script-->
        <?php
            //check if the user has logged on this session
            require("checkuser.php");
            //add links if the user is logged in
            require("connection.php");
            //get the review title from DB
            if (isset($_GET['id'])) {
                //get the review title
                $reviewid = $_GET['id']; //workaround for not allowing array variable access in strings
                $sql = "SELECT title, id FROM reviews WHERE id = $reviewid LIMIT 1";
            } else {
                //redirect the user to the home page if no id given
                header("Location: index.php");
            }

            if($results = mysqli_query($con, $sql)) {
                if(mysqli_num_rows($results) > 0) {
                    while($row = mysqli_fetch_array($results)) {
                        //set the page title as appropriate
                        echo("<title>" . $row['title'] . " - Mod Reviews</title>");
                    }
                } else {
                    echo("<title>Review not found - Mod Reviews</title>");
                }
            } else {
                echo("<title>Database error - Mod Reviews</title>");
                die(mysqli_error($con));
            }

        ?>
        <!--<title>Review for</title>-->
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
                <!--Main Collapsable content - will shrink to a single button on small devices-->
                <div class="collapse navbar-collapse" id="navbar-content">
                    <!--Thanks to: https://www.codeply.com/go/t6EpPvoacQ/bootstrap-4-navbar-justify for guidance on justifying navbar content-->
                    <div class="navbar-nav nav-fill w-100 align-items-start"> <!--nav-fill, w-100 and align-items-start stretch the elements to fill the navbar-->
                        <a class="nav-item nav-link px-2" href="index.php">Home</a>
                        <a class="nav-item nav-link px-2" href="games.php">Games</a>
                        <a class="nav-item nav-link px-2" href="mods.php">Mods</a>
                        <?php
							//check the user is logged in and provide the correct links
                            if($loggedin) {
                                //user is logged in, provide access to their profile
                                echo("<a class='nav-item nav-link px-2' href='post.php'>Create Review</a>");
                                echo("<a class='nav-item nav-link px-2' href='profile.php'>$currentuser</a>
                                <a class='nav-item btn nav-link nav-primary bg-primary text-light px-2' href='logout.php'>Log Out</a>");
                            } else {
                                //user is logged out, provide login links
                                echo("<a class='nav-item nav-link px-2' href='login.php'>Login</a>
                                <a class='nav-item btn nav-link nav-primary bg-primary text-light px-2' href='register.php'>Register</a>");
                            }
                        ?>
                    </div>
                </div>
            </nav>
            
            
            <?php
                //we already checked for and got a review ID, we don't need to do that again
                //get the review from the database - multiple inner joins are needed to do this
                $sql = "SELECT users.username, mods.name, reviews.title, reviews.id, reviews.rating, reviews.reviewimg, reviews.reviewtext
                FROM ((reviews
                INNER JOIN users ON reviews.userid = users.id) INNER JOIN mods ON reviews.modid = mods.id) WHERE reviews.id = $reviewid";

                //display the results
                if($results = mysqli_query($con, $sql)) {
                    if(mysqli_num_rows($results) > 0) {
                        while($row = mysqli_fetch_array($results)) {
                            echo("<div class='row'>");
								//large heading style provided by Bootstrap
                                echo("<h1 class='display-4 text-center'>" . $row['title'] . "</h1>");
                            echo("</div><br>");
                            echo("<div class='row'>");
                                echo("<h2 class='text-center'> A review of <em>" . $row['name'] . "</em> by <em>" . $row['username'] . "</em></h2>");
                                //echo("<br>");
                                //echo("<h2 class='text-center'> Reviewed by " . $row['username'] . "</h2>");
                            echo("</div><br>");
                            echo("<div class='row'>");
							//take up 50% width. h-50 is used just to ensure the image is not stretched, the correct aspect ratio is preserved
                                echo("<img src='" . $row['reviewimg'] . "' class='mx-auto w-75 h-50' alt='Review Image'>");  
                            echo("</div><br>");
							//apply a grey, rounded background to the review text
                            echo("<div class='row bg-light rounded mx-1'>");
                                echo("<p class='p-3'>" . $row['reviewtext'] . "</p>");
                            echo("</div><br>");
                            echo("<div class='row'>");
                                echo("<h3 class='ml-3'>Rating: " . $row['rating'] . "/10</h3>");
								//margin classes ml-auto and mr-3 place the button close to the right of the screen
                                echo("<a onclick='backToPrev()' class='btn btn-primary text-light ml-auto mr-3'>Go back</a>");
                            echo("</div>");
                        }
                    } else {
						//if no result returned, inform the user with an error message
                        echo("<div class='row'>");
                        echo("<h1 class='display-4 text-center'>Review not found</h1>");
                        echo("</div><br>");
                        echo("<div class='row'>");
                            echo("<h2 class='text-center'>This review either does not exist or has been deleted.</h2>");
                        echo("</div><br>");
                        echo("<div class='row'>");
                            //JavaScript function to determine where to send the user
                            echo("<a onclick='backToPrev()' class='btn btn-primary text-light ml-auto mr-3'>Go back</a>");
                        echo("</div>");
                    }
                } else {
                    die(mysqli_error($con));
                }
            ?>
            
            
        </div>
        
        <br><br><br>
		<!--Footer fixed to bottom of screen - makes site more consistent-->
        <nav class="navbar fixed-bottom navbar-light bg-light">
            <span class="text-muted">Website created by Alexander Duffy</span>
        </nav>
        



        <!--***Included JavaScript files beyond here***-->
        <!--JQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!--Bootstrap - used for responsiveness and styling-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js" integrity="sha384-lZmvU/TzxoIQIOD9yQDEpvxp6wEU32Fy0ckUgOH4EIlMOCdR823rg4+3gWRwnX1M" crossorigin="anonymous"></script>

        <!--custom script for the back button on the page-->
        <script src="condback.js"></script>
    </body>
</html>