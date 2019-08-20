<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php
            //check if the user has logged on this session
            require("checkuser.php");
            //add links if the user is logged in
            require("connection.php");
            //get the review title from DB
            if (isset($_GET['name'])) {
                //get the review title
                $modname = $_GET['name']; //workaround for not allowing array variable access in strings
                $sql = "SELECT `name`, id FROM mods WHERE `name` = '" . $modname . "' LIMIT 1";
            } else {
                //redirect the user to the mods page if no name given
                header("Location: mods.php");
            }

            if($results = mysqli_query($con, $sql)) {
                if(mysqli_num_rows($results) > 0) {
                    while($row = mysqli_fetch_array($results)) {
                        //set the page title as appropriate
                        echo("<title>" . $row['name'] . " - Mod Reviews</title>");
                    }
                } else {
                    echo("<title>Mod not found - Mod Reviews</title>");
                }
            } else {
                echo("<title>Database error - Mod Reviews</title>");
                die(mysqli_error($con));
            }

        ?>
        <!--Bootstrap CSS styling -->
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
            
            
            <?php
                //we already checked for and got a game name, we don't need to do that again
                //get the game details
                $sql = "SELECT mods.name AS modname, developer.name AS devname, game.name AS gamename, mods.id, mods.releaseDate AS modrelease, mods.contentType, mods.diskSpace AS moddisk, mods.modLength, mods.modLink
                FROM ((mods
                INNER JOIN game ON mods.gameID = game.id) INNER JOIN developer ON mods.developerID = developer.id) WHERE mods.name = \"$modname\"";

                //display the results
                
                if($results = mysqli_query($con, $sql)) {
                    if(mysqli_num_rows($results) > 0) {
                        while($row = mysqli_fetch_array($results)) {
                            echo("<div class='row'>");
                                echo("<h1 class='display-4 text-center'>" . $row['modname'] . " for " . $row['gamename'] . "</h1>");
                            echo("</div><br>");
                            echo("<div class='row'>");
                                echo("<h2 class='text-center'>Key Info</em></h2>");
                            echo("</div><br>");
                            echo("<div class='row'>");
                                //Bootstrap-styled table
                                echo("<table class='table'>");
                                    //echo("<tbody>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Developer</th>");
                                            echo("<td class='p-3'>" . $row['devname'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Release Date</th>");
                                            echo("<td class='p-3'>" . $row['modrelease'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Minimum Free Space</th>");
                                            echo("<td class='p-3'>" . $row['moddisk'] . " MB</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Average Play Time</th>");
                                            echo("<td class='p-3'>" . $row['modLength'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Link to mod homepage</th>");
                                            //open a new tab when linking to an external site
                                            echo("<td class='p-3'><a href='" . $row['modLink'] . "' target='_blank'>Website</a></td>");
                                        echo("</tr>");
                                    //echo("</tbody");
                                echo("</table>"); 
                            echo("</div><br>");
                            echo("<h2>Reviews for this mod</h2><br>");
                        }
                    } else {
                        echo("<div class='row'>");
                        echo("<h1 class='display-4 text-center'>Mod not found</h1>");
                        echo("</div><br>");
                        echo("<div class='row'>");
                            echo("<h2 class='text-center'>This mod either does not exist or is not in our database.</h2>");
                        echo("</div><br>");
                        echo("<div class='row'>");
                            echo("<a href='mailto:requests@example.co.uk?subject=Mod%20Request' class='btn btn-secondary text-light ml-auto mr-0'>Request a Mod</a>");
                            echo("<a onclick='history.back()' class='btn btn-primary text-light mx-3'>Go back</a>");
                        echo("</div>");
                    }
                } else {
                    die(mysqli_error($con));
                }
            ?>
            
            <!--Get reviews here-->
            <div class="row">
                <?php
                    
                    $sql = "SELECT users.username, mods.name, reviews.title, reviews.id, reviews.rating, reviews.reviewimg
                    FROM ((reviews
                    INNER JOIN users ON reviews.userid = users.id) INNER JOIN mods ON reviews.modid = mods.id) WHERE mods.name = \"$modname\" ORDER BY reviews.id DESC";
                    
                    if($results = mysqli_query($con, $sql)) {
                        if(mysqli_num_rows($results) > 0) {
                            while($row = mysqli_fetch_array($results)) {
                                echo("<div class='col-9 col-md-6 col-lg-4 col-xl-4 card my-1'>");
                                echo("<img class='card-img-top' src='" . $row['reviewimg'] . "' alt='Review Image'>");
                                echo("<div class='card-body'>");
                                echo("<h5 class='card-title'>" . $row['title'] . " - " . $row['rating'] . "/10</h5>");
                                echo "<p class='card-text'>" . $row['name'] . " reviewed by " . $row['username'] . "</p>";
                                echo("<a href='review.php?id=" . $row['id'] . "' class='btn btn-primary'>Read review</a>");
                                echo("</div>");
                                echo("</div>");
                            }
                        }
                    } else {
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
        <!--Site-specific styling-->
        <link rel="stylesheet" href="styles.css">
        
        
    </body>
</html>