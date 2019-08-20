<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Welcome to Mod Reviews!</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <!--Site-specific styling-->
        <link rel="stylesheet" href="styles.css">

        <!--handle logon redirect-->
        <?php
            //check if the user has logged on this session
            require("checkuser.php");
            //add links if the user is logged in
            require("connection.php");
        ?>
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
                        <a class="nav-item nav-link active px-2" href="index.php">Home</a>
                        <a class="nav-item nav-link px-2" href="games.php">Games</a>
                        <a class="nav-item nav-link px-2" href="mods.php">Mods</a>
                        <?php
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

            <div class="row">
                <div class="col">
                    <h1 class="display-4">Welcome to Mod Reviews!</h1>
                </div>
            </div>
            <br>
            <!-- Main wrapper for latest reviews -->
            <div class="row">
                <div class="col">
                    <h2>Latest reviews</h2>
                </div>
            </div>
            <br>
            <div class="row">
                <!--Review summary retriever script-->
                <?php
                    //only return the 6 most recent reviews
                    $sql = "SELECT users.username, mods.name, reviews.title, reviews.id, reviews.rating, reviews.reviewimg
                    FROM ((reviews
                    INNER JOIN users ON reviews.userid = users.id) INNER JOIN mods ON reviews.modid = mods.id) ORDER BY reviews.id DESC LIMIT 6";
                   
                    if($results = mysqli_query($con, $sql)) {
                        if(mysqli_num_rows($results) > 0) {
                            while($row = mysqli_fetch_array($results)) {
								//Bootstrap provides cards to arrange content neatly and respond to different device sizes
								//col classes set size as n/12 of container width based on screen width breakpoints set by Bootstrap
                                echo("<div class='col-9 col-md-6 col-lg-4 col-xl-4 card my-1'>");
								//card image caps are scaled automatically
                                echo("<img class='card-img-top' src='" . $row['reviewimg'] . "' alt='Review Image'>");
                                echo("<div class='card-body'>");
                                echo("<h5 class='card-title'>" . $row['title'] . " - " . $row['rating'] . "/10</h5>");
                                echo "<p class='card-text'>" . $row['name'] . " reviewed by " . $row['username'] . "</p>";
								//buttons can be inserted into cards
                                echo("<a href='review.php?id=" . $row['id'] . "' class='btn btn-primary'>Read review</a>");
                                echo("</div>");
                                echo("</div>");
                            }
                        }
                    } else {
						//mySQL error, quit immediately
                        die(mysqli_error($con));
                    }
                ?>
                
            </div>
            
            
        </div>
        <!--Website footer - positioned here to fill the entire width-->
        <br><br>
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