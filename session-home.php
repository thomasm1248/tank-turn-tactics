<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    
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

        // Get list of players
        $players = array();
        $sql = "SELECT Tanks.name, Tanks.lives, Tanks.deathdate
        FROM Tanks
        JOIN Sessions ON Sessions.sessionid = Tanks.partofsession
        WHERE Sessions.pagecode = $pagecode
        ORDER BY Tanks.lives DESC, Tanks.deathdate DESC;";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)) {
            $players[] = $row;
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

            <!-- Show link to map -->
            <a href="view-map.php?session=<?php print($pagecode); ?>"><h3>View Map</h3></a>

            <!-- Let players join session if session is waiting -->
            <?php if($status === "waiting") { ?>
                <p>This session hasn't started yet. Players are free to join until it starts.</p>
                <h3>Join This Session</h3>
                <form action="new-player.php" method="post">
                    
                    <input id="sessionid" name="sessionid" type="hidden" value="<?php print($sessionid); ?>">

                    <input id="pagecode" name="pagecode" type="hidden" value="<?php print($pagecode); ?>">

                    <input id="player-name" name="player-name" type="text" placeholder="player name" autofocus><br>

                    <textarea id="bio" name="bio" placeholder="player bio and contact info" cols="30" rows="15"></textarea><br>

                    <input id="join" type="submit" value="Join">

                </form>
            <?php } ?>

            <!-- Show a table of all the players when the game ends -->
            <?php if($status === "ended") { ?>
                <p>This session has ended.</p>
                <h3>Players</h3>
                <table>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Lives Remaining</th>
                        <th>Date of Death
                    </tr>

                    <?php 

// Print out a table of the players' info
for($i = 0; $i < sizeof($players); $i+=1) {
    $name = $players[$i]['name'];
    $lives = $players[$i]['lives'];
    $deathdate = $players[$i]['deathdate'];
    $rank = $i + 1;
    print("<tr><td>$rank</td>");
    print("<td>$name</td>");
    if($lives > 0) {
        print("<td>$lives</td>");
        print("<td>---</td></tr>");
    } else {
        print("<td>Dead</td>");
        print("<td>$deathdate</td></tr>");
    }
}

// Leave a placeholder if there aren't any players yet
if(sizeof($players) === 0) {
    print("<tr><td colspan=\"4\">No players have joined yet.</td></tr>");
}

                    ?>

                </table>
            <?php } ?>

            <h3>Share This Session</h3>
            <p>Share this page with anyone who wants to watch this session.</p>

        </main>

    </div>
</body>
</html>
