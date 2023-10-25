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
        $sql = "SELECT `name`, `sessionid`, `status` FROM `Sessions` WHERE `pagecode` = $pagecode;";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)) {
            $row = mysqli_fetch_array($result);
            $sessionname = $row["name"];
            $sessionid = $row["sessionid"];
            $status = $row["status"];
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

            <!-- Show link to map if session is running -->
            <?php if($status === "running") { ?>
                <a href="view-map.php?session=<?php print($pagecode); ?>"><h3>View Map</h3></a>
            <?php } ?>

            <!-- Let players join session if session is waiting -->
            <?php if($status === "waiting") { ?>
                <p>This session hasn't started yet. Players are free to join until it starts.</p>
                <h3>Join This Session</h3>
                <form action="new-player.php" method="post">
                    
                    <input id="sessionid" name="sessionid" type="hidden" value="<?php print($sessionid); ?>">

                    <input id="pagecode" name="pagecode" type="hidden" value="<?php print($pagecode); ?>">

                    <input id="player-name" name="player-name" type="text" placeholder="player name"><br>

                    <textarea id="bio" name="bio" placeholder="player bio and/or contact info" cols="30" rows="15"></textarea><br>

                    <input id="join" type="submit" value="Join">

                </form>
            <?php } ?>

            <h3>Share This Session</h3>
            <p>Share this page with anyone who wants to watch this session.</p>

        </main>

    </div>
</body>
</html>
