<?php

function isAlreadyTaken($code, $column, $conn) {
    $sql = "SELECT `$column` FROM `Sessions` WHERE $column = $code";
    $result = mysqli_query($conn, $sql);
    print("here");
    return mysqli_num_rows($result) > 0;
}

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

// Create a new session
$name = $_POST["session-name"];
$width = $_POST["width"];
$height = $_POST["height"];
$admincode = rand();
$pagecode = rand();
while(isAlreadyTaken($pagecode, "pagecode", $conn)) {
    $pagecode = rand();
}
$sql = "INSERT INTO `Sessions`(`name`, `pagecode`, `admincode`, `width`, `height`, `status`) VALUES ('$name','$pagecode','$admincode','$width','$height','waiting')";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);

// Redirect to session-admin page
include("session-admin.php");
header("Location: $url/session-admin.php?session=$pagecode&admin=$admincode");

?>
