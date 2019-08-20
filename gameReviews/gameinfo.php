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
                $gamename = $_GET['name']; //workaround for not allowing array variable access in strings
                $sql = "SELECT `name`, id FROM game WHERE `name` = '" . $gamename . "' LIMIT 1";
            } else {
                
                //redirect the user to the games page if no name given
                header("Location: games.php");
            }

            if($results = mysqli_query($con, $sql)) {
                if(mysqli_num_rows($results) > 0) {
                    while($row = mysqli_fetch_array($results)) {
                        //set the page title as appropriate
                        echo("<title>" . $row['name'] . " - Mod Reviews</title>");
                    }
                } else {
                    echo("<title>Game not found - Mod Reviews</title>");
                }
            } else {
                echo("<title>Database error - Mod Reviews</title>");
                die(mysqli_error($con));
            }

        ?>
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
                $gamefound = false;
                $sql = "SELECT game.name AS gamename, publisher.name AS pubname, developer.name AS devname, game.id, game.criticReview, game.classification, game.releaseDate, game.diskSpace, game.operatingSys, game.minCPU, game.minRAM, game.minGPU
                FROM ((game
                INNER JOIN publisher ON game.publisherID = publisher.id) INNER JOIN developer ON game.developerID = developer.id) WHERE game.name = \"$gamename\"";

                //display the results
                //TODO: fix these appearing below the mods list.
                if($results = mysqli_query($con, $sql)) {
                    if(mysqli_num_rows($results) > 0) {
                        while($row = mysqli_fetch_array($results)) {
                            echo("<div class='row'>");
                                echo("<h1 class='display-4 text-center'>" . $row['gamename'] . " (" . $row['classification'] . ") - " . $row['criticReview'] . "/100</h1>");
                            echo("</div><br>");
                            echo("<div class='row'>");
                                echo("<h2 class='text-center'>Key Info</em></h2>");
                            echo("</div><br>");
                            //Bootstrap-styled table
                            echo("<div class='row'>");
                                echo("<table class='table'>");
                                    //echo("<tbody>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Developer</th>");
                                            echo("<td class='p-3'>" . $row['devname'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Publisher</th>");
                                            echo("<td class='p-3'>" . $row['pubname'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Release Date</th>");
                                            echo("<td class='p-3'>" . $row['releaseDate'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Operating System</th>");
                                            echo("<td class='p-3'>" . $row['operatingSys'] . "</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Minimum CPU Speed</th>");
                                            echo("<td class='p-3'>" . $row['minCPU'] . " MHz</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Minimum GPU Memory</th>");
                                            echo("<td class='p-3'>" . $row['minGPU'] . " MB</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Minimum System Memory</th>");
                                            echo("<td class='p-3'>" . $row['minRAM'] . " MB</td>");
                                        echo("</tr>");
                                        echo("<tr>");
                                            echo("<th scope='row' class='p-3'>Minimum Free Space</th>");
                                            echo("<td class='p-3'>" . $row['diskSpace'] . " MB</td>");
                                        echo("</tr>");
                                    //echo("</tbody");
                                echo("</table>"); 
                            echo("</div><br>");
                            echo("<h2>Mods for this game</h2><br>");
                            $gamefound = true;
                        }
                    } else {
                        echo("<div class='row'>");
                        echo("<h1 class='display-4 text-center'>Game not found</h1>");
                        echo("</div><br>");
                        echo("<div class='row'>");
                            echo("<h2 class='text-center'>This game either does not exist or is not in our database.</h2>");
                        echo("</div><br>");
                        echo("<div class='row'>");
                        echo("<a href='mailto:requests@example.co.uk?subject=Game%20Request' class='btn btn-secondary text-light ml-auto mr-0'>Request a Game</a>");
                            echo("<a onclick='history.back()' class='btn btn-primary text-light mx-3'>Go back</a>");
                        echo("</div>");
                        $gamefound = false;
                    }
                } else {
                    die(mysqli_error($con));
                }
            ?>
            
            <!--Get mods here-->
            <div class="row">
                <?php   
                    $sql = "SELECT mods.name AS modname, mods.releaseDate AS moddate, developer.name AS moddev, mods.contentType
                    FROM ((mods
                    INNER JOIN game ON mods.gameID = game.id) INNER JOIN developer ON mods.developerID = developer.id) WHERE game.name = \"$gamename\" ORDER BY mods.id DESC";
                    
                    if($results = mysqli_query($con, $sql)) {
                        if(mysqli_num_rows($results) > 0) {
                            while($row = mysqli_fetch_array($results)) {
                                echo("<div class='col-9 col-md-6 col-lg-4 col-xl-4 card my-1'>");
                                echo("<div class='card-body'>");
                                echo("<h5 class='card-title'>" . $row['modname'] . "</h5>");
                                echo("<p class='card-text'>Developed by " . $row['moddev'] . " for " . $gamename . "</p>");
                                echo("<p class='card-text'>Released on " . $row['moddate'] . "</p>");
                                echo("<p class='card-text'>Modifies: " . $row['contentType'] . "</p>");
                                echo("<a href='modinfo.php?name=" . $row['modname'] . "' class='btn btn-primary'>Details & Reviews</a>");
                                echo("</div>");
                                echo("</div>");
                            }
                        } else if ($gamefound) {
                            //game exists, but no mods available, prompt user to request one
                            echo("<h3>There are no mods in the database for this game.</h3>");
                            echo("<a href='mailto:requests@example.co.uk?subject=Mod%20Request' class='btn btn-secondary text-light ml-auto mr-0'>Request a Mod</a>");
                            echo("<a onclick='history.back()' class='btn btn-primary text-light  mx-3'>Go back</a>");
                        
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