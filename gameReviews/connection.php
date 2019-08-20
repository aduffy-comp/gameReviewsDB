<?php
    // MySQL Server Connection settings
    // This file should be required for all communication with the database.
    $con = mysqli_connect("localhost","user","password","modreviews2"); //replace user and password with correct localhost login
    // attempt to connect
    if (mysqli_connect_errno())
    {
		//exit if we could not connect
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
?>