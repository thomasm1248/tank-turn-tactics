<?php

function spotTaken($x, $y, $xs, $ys) {
    for($i = 0; $i < sizeof($xs); $i+=1) {
        if($x === $xs[$i] && $y === $ys[$i]) {
            return true;
        }
    }
    return false;
}

// Connect to database
$host = "localhost";
$username = "admin";
$password = "helloWorld1248";
$database = "Game";
$conn = mysqli_connect($host, $username, $password, $database);
// Check if connection was successful
if (!$conn) {
    print("fail");
}

// Get new player information
$sessionid = $_POST['sessionid'];
$pagecode = $_POST['pagecode'];
$playername = $_POST['player-name'];
$bio = addslashes($_POST['bio']);
$playercode = rand();

// Get height and width of table
$sql = "SELECT `width`, `height` FROM `Sessions` WHERE `sessionid` = $sessionid;";
$row = mysqli_fetch_array(mysqli_query($conn, $sql));
$width = $row['width'];
$height = $row['height'];

// Get locations of other tanks
$sql = "SELECT `x`, `y` FROM `Tanks` WHERE `partofsession` = $sessionid;";
$result = mysqli_query($conn, $sql);
$xs = array();
$ys = array();
print(mysqli_fetch_array($result));
while($row = mysqli_fetch_array($result)) {
    $xs[] = $row['x'];
    $ys[] = $row['y'];
}

// Determine where to place new player on the map
$x = rand(0, $width - 1);
$y = rand(0, $height - 1);
$i=0;
while(spotTaken($x, $y, $xs, $ys)) {
    print("($x,$y)");
    $x = rand(0, $width - 1);
    $y = rand(0, $height - 1);
    $i+=1;
    if($i > 20) {
        print("timeout");
        break;
    }
}

// Add new player to database
$sql = "INSERT INTO `Tanks`(`name`, `pagecode`, `partofsession`, `bio`, `x`, `y`) VALUES ('$playername', $playercode, $sessionid, '$bio', $x, $y);";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);

// Redirect to join success page
include('player-dashboard.php');
header("Location: $url/player-dashboard.php?session=$pagecode&player=$playercode");

?>
