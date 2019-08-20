<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--required for Bootstrap and mobile device emulation-->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Latest Mods - Mod Reviews</title>
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
                        <a class="nav-item nav-link px-2" href="index.php">Home</a>
                        <a class="nav-item nav-link px-2" href="games.php">Games</a>
                        <a class="nav-item nav-link active px-2" href="mods.php">Mods</a>
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
                    <h1 class="display-4">Mods List</h1>
                </div>
            </div>
            <br>

            <!-- search box for games -->
            <div class="row">
                <h2>Search mods</h2>
            </div><br>
            <form action="modinfo.php" method="GET">
                <div class="form-group">
                    <label for="name">Mod title</label>
                    <!--Typeahead library used to provide user with suggestions whilst searching-->
					<!--typeahead class signifies this-->
                    <input type="text" class="typeahead form-control" placeholder="Search" id="name" name="name">
                </div>
                <button type="submit" value="Submit" class="btn btn-primary" id="searchbtn">Search</button>
            </form><br><br>

            <!-- latest 6 mods appear here -->
            <div class="row">
                <h2>Latest Mods</h2>
            </div>
            <br>
            <div class="row">
                <!--Review summary retriever script-->
                <?php
                    //only return the 6 most recent mods
                    //aliases required as multiple tables share the 'name' column title
                    $sql = "SELECT mods.name AS modname, game.name AS gamename, mods.releaseDate, developer.name AS devname, mods.contentType
                    FROM ((mods
                    INNER JOIN developer ON mods.developerID = developer.id) INNER JOIN game ON mods.gameID = game.id) ORDER BY mods.releaseDate DESC LIMIT 6";
                   
                    if($results = mysqli_query($con, $sql)) {
                        if(mysqli_num_rows($results) > 0) {
                            while($row = mysqli_fetch_array($results)) {
                                echo("<div class='col-9 col-md-6 col-lg-4 col-xl-4 card my-1'>");
                                echo("<div class='card-body'>");
                                echo("<h5 class='card-title'>" . $row['modname'] . "</h5>");
                                echo "<p class='card-text'> Developed by " . $row['devname'] . " for " . $row['gamename'] ."</p>";
                                echo "<p class='card-text'> Released on " . $row['releaseDate'] . "</p>";
                                echo "<p class='card-text'> Modifies: " . $row['contentType'] . "</p>";
                                
                                echo("<a href='modinfo.php?name=" . $row['modname'] . "' class='btn btn-primary'>Details & Reviews</a>");
                                echo("</div>");
                                echo("</div>");
                            }
                        }
                    } else {
                        die(mysqli_error($con));
                    }
                ?>
            </div><br>
            
            
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
        <!--Typeahead library - used for real-time search suggestions-->
        <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
		<!-- Script for the search box interface-->
        <script type="text/javascript" src="modsearch.js"></script>
    </body>
</html>