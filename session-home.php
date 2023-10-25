<!DOCTYPE html>
<html lang="en">
<head>

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

        // Get session information
        if(!isset($pagecode)) {
            $pagecode = $_GET['session'];
        }

        // Get table row for session
        $sql = "SELECT `name`, `status` FROM `Sessions` WHERE `pagecode` = $pagecode;";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)) {
            $row = mysqli_fetch_array($result);
            $sessionname = $row["name"];
        } else {
            $sessionname = "Error: Invalid URL";
        }

    ?>

    <title><?php print($sessionname); ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="container">

        <header>
            <h1>Tank Turn Tactics</h1>
        </header>

        <nav>
            <?php
                include("nav-insert.html");
            ?>
        </nav>

        <main>
            
            <h2><?php print($sessionname); ?></h2>

            <h3>Player Login</h3>
            <form action="POST">
                
                <input id="player-name" name="player-name" type="text" placeholder="player name"><br>

                <input id="password" name="password" type="password" placeholder="password"><br>

                <input id="login" type="submit" value="Login">

            </form>

            <a href="view-map.php?session=<?php print($pagecode); ?>"><h3>View Map</h3></a>

            <h3>Share This Session</h3>
            <p>Share this page with anyone who wants to watch this session.</p>

        </main>

    </div>
</body>
</html>
