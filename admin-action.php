<?php

// Get POST information
$playerid = $_POST['player'];
$action = $_POST['action'];
$pagecode = $_POST['session'];
$admincode = $_POST['admin'];

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

// Preform action
// TODO: authenticate admin
if($action === 'kick') {
    $sql = "DELETE FROM Tanks WHERE tankid = $playerid;";
    $result = mysqli_query($conn, $sql);
} elseif($action === 'refresh-url') {
    $newplayercode = rand();
    $sql = "UPDATE Tanks SET pagecode = $newplayercode WHERE tankid = $playerid;";
    $result = mysqli_query($conn, $sql);
} elseif($action === 'start-session') {
    // Get current status
    print("foo");
    $sql = "SELECT status FROM Sessions WHERE pagecode = $pagecode;";
    $status = mysqli_fetch_array(mysqli_query($conn, $sql))['status'];
    // If status is waiting, change it to running
    if($status === 'waiting') {
        $sql = "UPDATE Sessions SET status = 'running' WHERE pagecode = $pagecode;";
        mysqli_query($conn, $sql);
        print("bar");
    }
} elseif($action === 'end-session') {
    // Set status to ended
    $sql = "UPDATE Sessions SET status = 'ended' WHERE pagecode = $pagecode;";
    mysqli_query($conn, $sql);
}

// Close connection to database
mysqli_close($conn);

// Redirect back to admin page
include("session-admin.php");
header("Location: $url/session-admin.php?session=$pagecode&admin=$admincode");

?>
