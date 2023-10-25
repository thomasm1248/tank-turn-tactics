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
    $admincode = $_GET['admin'];
}

// Get table row for session
$sql = "SELECT `name`, `status` FROM `Sessions` WHERE `pagecode` = $pagecode AND `admincode` = $admincode;";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)) {
    $row = mysqli_fetch_array($result);
    $sessionname = $row["name"];
    $status = $row["status"];
} else {
    $sessionname = "Error: Invalid URL";
}

// Get list of players
$players = array();
$sql = "SELECT Tanks.tankid, Tanks.name, Tanks.x, Tanks.y, Tanks.lives, Tanks.actionpoints, Tanks.pagecode AS playercode
FROM Tanks
JOIN Sessions ON Sessions.sessionid = Tanks.partofsession
WHERE Sessions.pagecode = $pagecode;";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)) {
    $players[] = $row;
}

    ?>

    <title><?php print($sessionname); ?> - Admin</title>
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
            
            <h2><?php print($sessionname); ?>: Session Admin</h2>

            <p>Bookmark this page!</p>
            
            <p>Go <a href="session-home.php?session=<?php print($pagecode); ?>">here</a> to access the session page.</p>

            <h3>Status:</h3>
            <p><?php print($status);?></p>

            <?php if($status === 'waiting') { ?>
                <form action="admin-action.php" method="post">
                    <input type="hidden" name="session" value="<?php print($pagecode); ?>">
                    <input type="hidden" name="admin" value="<?php print($admincode); ?>">
                    <input type="hidden" name="action" value="start-session">
                    <input id="start-session" type="submit" value="Start Session">
                </form>
            <?php }
            if($status !== 'ended') { ?>
            <form action="admin-action.php" method="post">
                <input type="hidden" name="session" value="<?php print($pagecode); ?>">
                <input type="hidden" name="admin" value="<?php print($admincode); ?>">
                <input type="hidden" name="action" value="end-session">
                <input id="end-session" type="submit" value="End Session">
            </form>
            <?php } ?>
            
            <h3>Players</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Lives Left</th>
                    <th>Action Points</th>
                    <th>Access URL</th>
                </tr>

                <?php 

// Print out a table of the players' info
for($i = 0; $i < sizeof($players); $i+=1) {
    $id = $players[$i]['tankid'];
    $name = $players[$i]['name'];
    $x = $players[$i]['x'];
    $y = $players[$i]['y'];
    $lives = $players[$i]['lives'];
    $actionpoints = $players[$i]['actionpoints'];
    $playercode = $players[$i]['playercode'];
    print("<tr><td>$id</td>");
    print("<td>$name</td>");
    print("<td>$x, $y</td>");
    print("<td>$lives</td>");
    print("<td>$actionpoints</td>");
    print("<td><a href=\"player-dashboard.php?session=$pagecode&player=$playercode\">$name</a></td></tr>");
}

// Leave a placeholder if there aren't any players yet
if(sizeof($players) === 0) {
    print("<tr><td colspan=\"4\">No players have joined yet.</td></tr>");
}

                ?>

            </table>

            <h3>Admin Actions</h3>
            <form action="admin-action.php" method="post">

                <input id="pagecode" type="hidden" name="session" value="<?php print($pagecode); ?>">
                <input id="admincode" type="hidden" name="admin" value="<?php print($admincode); ?>">
                
                <label>
                    Player:
                    <select id="player" name="player" value="">
                        <option value=""></option>
                        
                        <!-- Create an option for each player -->
                        <?php

// List all players
for($i=0; $i < sizeof($players); $i+=1) {
    $playerid = $players[$i]['tankid'];
    $playername = $players[$i]['name'];
    print("<option value=\"$playerid\">$playername</option>");
}

                        ?>

                    </select>
                </label><br>

                <label>
                    Action:
                    <select id="action" name="action" value="">
                        <option value=""></option>
                        <option value="kick">Kick</option>
                        <option value="refresh-url">Refresh URL</option>
                    </select>
                </label><br>

                <input id="submit" type="submit" value="Submit">

            </form>

            <?php

// Close database connection
mysqli_close($conn);

            ?>

        </main>

    </div>
</body>
</html>
