<?php
    //checks to see if the user has logged in
    //used to customise page layouts for logged-in vs logged-out users
    //can also be used to restrict access to certain users
    session_start();
    //we set a username variable associated with the web browser when they are logged in.
    //a date check should probably be issued here, to preven users remaining logged in for extended periods
    if (isset($_SESSION["username"])) {
        $loggedin = true;
        //workaround for using the username in echo statements
        $currentuser = $_SESSION["username"];
    } else {
        $loggedin = false;
    }
?>