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
if(!isset($playercode)) {
    $playercode = $_GET['player'];
}

// Get table row for player
$sql = "SELECT Tanks.tankid, Tanks.name, Tanks.bio, Tanks.shootrange, Tanks.lives, Tanks.actionpoints, Tanks.votingfor, Tanks.x, Tanks.y, Sessions.name AS sessionname, Sessions.status AS sessionstatus
FROM `Tanks`
JOIN `Sessions` ON Sessions.sessionid = Tanks.partofsession
WHERE Sessions.pagecode = $pagecode
AND Tanks.pagecode = $playercode;";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)) {
    $row = mysqli_fetch_array($result);
    $playerid = $row['tankid'];
    $name = $row['name'];
    $bio = $row['bio'];
    $range = $row['shootrange'];
    $lives = $row['lives'];
    $actionpoints = $row['actionpoints'];
    $votingfor = $row['votingfor'];
    $x = $row['x'];
    $y = $row['y'];
    $sessionname = $row['sessionname'];
    $sessionstatus = $row['sessionstatus'];
} else {
    $name = "Error: Invalid URL";
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

    <title><?php print($name); ?> - Tank Turn Tactics</title>
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

            <p>Bookmark this page!</p>

            <p>Click <a href="session-home.php?session=<?php print($pagecode); ?>">here</a> to go to the session page.</p>

            <h3>Name: <?php print($name); ?></h3>

            <!-- Only show actions and information if session is running -->
            <?php if($sessionstatus === "ended") { ?>
                <p>This session has ended. Return to the session page to see the ranking.</p>
            <?php } elseif($sessionstatus === "waiting") { ?>
                <p>This session hasn't started yet. Wait for the session admin to start the session.</p>
            <?php } elseif($lives > 0) { ?>
                <h3>Lives</h3>
                <div id="lives">
                    <?php

// Display a variable number of heart images
for($i = 0; $i < $lives; $i+=1) {
    print("<img class='life-heart' src='images/heart.png'>");
}

                    ?>
                </div>

                <h3>Actions</h3>
                <p>Action Points: <?php print($actionpoints); ?></p>
                <form action="player-action.php" method="post">
                    
                    <input type="hidden" name="session" value="<?php print($pagecode); ?>">
                    <input type="hidden" name="player" value="<?php print($playercode); ?>">

                    <label>
                        Action:
                        <select id="action" name="action" value="move">
                            <option value="move">Move</option>
                            <option value="shoot">Shoot</option>
                            <option value="upgrade-range">Upgrade Range</option>
                            <option value="give-ap">Give Action Points</option>
                        </select>
                    </label><br>

                    <div id="direction-select" style="display: block">
                        <label>
                            Direction:
                            <select id="direction" name="direction">
                                <option>N</option>
                                <option>NE</option>
                                <option>E</option>
                                <option>SE</option>
                                <option>S</option>
                                <option>SW</option>
                                <option>W</option>
                                <option>NW</option>
                            </select>
                        </label>
                    </div>

                    <div id="player-select" style="display: none">
                        <label>
                            Player:
                            <select id="target" name="target">

                                <!-- Create an option for each player -->
                                <?php

// Display list of players that can be targeted
for($i=0; $i < sizeof($players); $i+=1) {
    if($players[$i]['tankid'] === $playerid) continue;
    $tx = $players[$i]['x'];
    $ty = $players[$i]['y'];
    $r = $range;
    if($tx >= $x-$r && $tx <= $x+$r && $ty >= $y-$r && $ty <= $y+$r) {
        $tankid = $players[$i]['tankid'];
        $playername = $players[$i]['name'];
        print("<option value=\"$tankid\">$playername</option>");
    }
}
                                ?>

                            </select>
                        </label>
                    </div>

                    <div id="ap-select" style="display: none">
                        <label>
                            Number of Action Points to send:
                            <select id="action-points" name="action-points" value="1">

                                <!-- Create an option for every amount of action points -->
                                <?php

// Display options for how many action points can be sent
for($i=1; $i <= $actionpoints; $i+=1) {
    print("<option>$i</option>");
}
                                ?>

                            </select>
                        </label>
                    </div>

                    <input id="submit" type="submit" value="Take Action!">
                            
                </form>
            <?php } else { ?>
                <p> You are dead</p>
            <?php } ?>

            <?php mysqli_close($conn); ?>

        </main>

        <script>

function l(id) {
    return document.getElementById(id);
}

// Show/Hide sections of the form based on what action is selected
function updateForm() {
    var value = l("action").value;
    switch(value) {
        case "move":
            l("direction-select").style.display = "block";
            l("player-select").style.display = "none";
            l("ap-select").style.display = "none";
            break;
        case "shoot":
            l("direction-select").style.display = "none";
            l("player-select").style.display = "block";
            l("ap-select").style.display = "none";
            break;
        case "upgrade-range":
            l("direction-select").style.display = "none";
            l("player-select").style.display = "none";
            l("ap-select").style.display = "none";
            break;
        case "give-ap":
            l("direction-select").style.display = "none";
            l("player-select").style.display = "block";
            l("ap-select").style.display = "block";
            break;
    }
}

l("action").addEventListener("input", function(e) {
    updateForm();
}, false);
updateForm();

        </script>

    </div>
</body>
</html>
