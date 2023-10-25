<?php

// Util functions
function getTankAt($x, $y, $tanks) {
    for($i = 0; $i < sizeof($tanks); $i+=1) {
        if($x == $tanks[$i]['x'] && $y == $tanks[$i]['y']) {
            return $tanks[$i];
        }
    }
    return null;
}

function getTankById($id, $tanks) {
    for($i = 0; $i < sizeof($tanks); $i+=1) {
        if($id === $tanks[$i]['id']) {
            return $tanks[$i];
        }
    }
    return null;
}

// Get POST values
$pagecode = $_POST['session'];
$playercode = $_POST['player'];
$action = $_POST['action'];
$direction = $_POST['direction'];
$targetplayer = $_POST['target'];
$pointstosend = $_POST['action-points'];

// Connect to the database
$host = "localhost";
$username = "root";
$password = "";
$database = "Game";
$conn = mysqli_connect($host, $username, $password, $database);
// Check if connection was successful
if (!$conn) {
    print("fail");
}

// Get info about player
$sql = "SELECT Tanks.tankid, Tanks.actionpoints, Tanks.lives, Tanks.shootrange, Tanks.x, Tanks.y FROM Tanks JOIN Sessions ON Sessions.sessionid = Tanks.partofsession WHERE Sessions.pagecode = $pagecode AND Tanks.pagecode = $playercode;";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$playerid = $row['tankid'];
$actionpoints = $row['actionpoints'];
$lives = $row['lives'];
$range = $row['shootrange'];
$x = $row['x'];
$y = $row['y'];

// Get info about the session
$sql = "SELECT status, width, height FROM Sessions WHERE pagecode = $pagecode;";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$status = $row['status'];
$width = $row['width'];
$height = $row['height'];

// Get info about the other living players
$sql = "SELECT Tanks.x, Tanks.y, Tanks.tankid, Tanks.lives, Tanks.actionpoints FROM Tanks JOIN Sessions ON Sessions.pagecode = $pagecode WHERE Tanks.lives > 0 AND Tanks.tankid <> $playerid;";
$result = mysqli_query($conn, $sql);
$othertanks = array();
while($row = mysqli_fetch_array($result)) {
    $tank = array(
        'id' => $row['tankid'],
        'lives' => $row['lives'],
        'actionpoints' => $row['actionpoints'],
        'x' => $row['x'],
        'y' => $row['y']
    );
    $othertanks[] = $tank;
}

// Preform action
// TODO: if authentication or action point amount fails, do nothing (check status too)
if($action === 'move') {
    // Lookup table
    $directions = array(
        "N" => [0,-1],
        "NE" => [1,-1],
        "E" => [1,0],
        "SE" => [1,1],
        "S" => [0,1],
        "SW" => [-1,1],
        "W" => [-1,0],
        "NW" => [-1,-1]
    );
    // Compute new position
    $change = $directions[$direction];
    $x += $change[0];
    $y += $change[1];
    // Check if that spot is already taken or if it's out of bounds
    $tank = getTankAt($x, $y, $othertanks);
    if(is_null($tank) && $x >= 0 && $y >= 0 && $x < $width && $y < $height) {
        $actionpoints -= 1;
        // Update the position of the tank
        $sql = "UPDATE Tanks SET x = $x, y = $y, actionpoints = $actionpoints WHERE tankid = $playerid;";
        mysqli_query($conn, $sql);
    }
} elseif($action === 'shoot') {
    // Get info about target tank
    $targettank = getTankById($targetplayer, $othertanks);
    // Check if tank is within range
    $tx = $targettank['x'];
    $ty = $targettank['y'];
    $r = $range;
    if(!is_null($targettank) && $tx >= $x-$r && $tx <= $x+$r && $ty >= $y-$r && $ty <= $y+$r) {
        // Shoot target tank
        $targettank['lives'] -= 1;
        $actionpoints -= 1;
        // Update database
        $updatedlives = $targettank['lives'];
        $targetid = $targettank['id'];
        $sql = "UPDATE Tanks SET lives = $updatedlives WHERE tankid = $targetid;";
        mysqli_query($conn, $sql);
        $sql = "UPDATE Tanks SET actionpoints = $actionpoints WHERE tankid = $playerid;";
        mysqli_query($conn, $sql);
    }
} elseif($action === 'upgrade-range') {
    $range += 1;
    $actionpoints -= 1;
    $sql = "UPDATE Tanks SET shootrange = $range, actionpoints = $actionpoints WHERE tankid = $playerid;";
    mysqli_query($conn, $sql);
} elseif($action === 'give-ap') {
    if($actionpoints >= $pointstosend) {
        $actionpoints -= $pointstosend;
        $targetpoints = getTankById($targetplayer, $othertanks)['actionpoints'] + $pointstosend;
        print("($targetid, $targetpoints)");
        $sql = "UPDATE Tanks SET actionpoints = $actionpoints WHERE tankid = $playerid;";
        mysqli_query($conn, $sql);
        $sql = "UPDATE Tanks SET actionpoints = $targetpoints WHERE tankid = $targetplayer;";
        mysqli_query($conn, $sql);
        print("foo");
    }
}

// Redirect back to the player dashboard page
include("player-dashboard.php");
header("Location: $url/player-dashboard.php?session=$pagecode&player=$playercode");

?>
