<?php

include("connection.php");
//get the search passed by the typeahead scripts
$search = $_GET['key'];
//search on the human-readable name column
$sql = "SELECT * FROM game WHERE `name` LIKE '%$search%'";

$query = mysqli_query($con, $sql);
//define a new array to store results returned
$results = array();

while($row = mysqli_fetch_assoc($query)) {
    //add the result to the array
    $results[] = $row['name'];
}
//encode the array into JSON, which can be read by Typeahead and send it back to the client
echo json_encode($results);
//close the SQL connection
mysqli_close($con);

?>