<?php

// Connect to database
$host = "localhost";
$username = "root";
$password = "";
$database = "Game";
$conn = mysqli_connect($host, $username, $password, $database);
// Check if connection was successful
if (!$conn) {
    print("fail");
}

// Get session info
$pagecode = $_GET['session'];
$sql = "SELECT width, height FROM Sessions WHERE pagecode = $pagecode;";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$width = $row['width'];
$height = $row['height'];

// Generate action log data
$sql = "SELECT Tanks.name, Actions.description FROM Actions JOIN Sessions ON Sessions.sessionid = Actions.partofsession JOIN Tanks ON Tanks.tankid = Actions.actingtank WHERE Sessions.pagecode = $pagecode;";
$result = mysqli_query($conn, $sql);
$actionlog = [];
while($row = mysqli_fetch_array($result)) {
    $tank = $row['name'];
    $action = $row['description'];
    $actionlog[] = "{
            \"tank\": \"$tank\",
            \"action\": \"$action\"
        }";
}
$actionlog_json = implode(',', $actionlog);

// Generate tank data
$sql = "SELECT Tanks.name, Tanks.bio, Tanks.lives, Tanks.actionpoints, Tanks.shootrange, Tanks.x, Tanks.y FROM Tanks JOIN Sessions ON Sessions.sessionid = Tanks.partofsession WHERE Sessions.pagecode = $pagecode AND Tanks.lives > 0;";
$result = mysqli_query($conn, $sql);
$tanks = [];
while($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    $bio = $row['bio'];
    $lives = $row['lives'];
    $action_points = $row['actionpoints'];
    $range = $row['shootrange'];
    $x = $row['x'];
    $y = $row['y'];
    $tanks[] = "{
            \"name\": \"$name\",
            \"bio\": \"$bio\",
            \"lives\": $lives,
            \"action_points\": $action_points,
            \"range\": $range,
            \"x\": $x,
            \"y\": $y
        }";
}
$tanks_json = implode(',', $tanks);

print("{
    \"map\": {
        \"width\": $width,
        \"height\": $height
    },
    \"log\": [$actionlog_json],
    \"tanks\": [$tanks_json]
}");

?>
