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
        $sql = "SELECT Tanks.name, Tanks.bio, Tanks.lives, Tanks.actionpoints, Tanks.votingfor, Sessions.name AS sessionname, Sessions.status AS sessionstatus
FROM `Tanks`
JOIN `Sessions` ON Sessions.sessionid = Tanks.partofsession
WHERE Sessions.pagecode = $pagecode
AND Tanks.pagecode = $playercode;";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)) {
            $row = mysqli_fetch_array($result);
            $name = $row['name'];
            $bio = $row['bio'];
            $lives = $row['lives'];
            $actionpoints = $row['actionpoints'];
            $votingfor = $row['votingfor'];
            $sessionname = $row['sessionname'];
            $sessionstatus = $row['sessionstatus'];
        } else {
            $name = "Error: Invalid URL";
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

            <h3>Name: <?php print($name); ?></h3>

            <!-- Only show actions and information if session is running -->
            <?php if($sessionstatus === "running") { ?>
                <h3>Lives</h3>
                <div id="lives">
                    <?php
                        for($i = 0; $i < $lives; $i+=1) {
                            print("<img class='life-heart' src='images/heart.png'>");
                        }
                    ?>
                </div>

                <h3>Actions</h3>
                <p>Action Points: <?php print($actionpoints); ?></p>
                <form action="POST">

                    <label>
                        Action:
                        <select id="action" name="action">
                            <option value="move">Move</option>
                            <option value="shoot">Shoot</option>
                            <option value="upgrade-range">Upgrade Range</option>
                            <option value="give-ap">Give Action Points</option>
                        </select>
                    </label><br>

                    <div id="direction-select" style="display: none">
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
                            <select id="player" name="player">
                                <!-- add list of players within range -->
                            </select>
                        </label>
                    </div>

                    <div id="ap-select" style="display: none">
                        Number of Action Points to send:
                        <select id="action-points" name="action-points" value="1">
                            <!-- add options for different amounts of action points -->
                        </select>
                    </div>

                    <input id="submit" type="submit" value="Take Action!">
                            
                </form>

            <!-- When game is waiting or ended, display a message -->
            <?php } else { ?>
                <p>The session isn't running</p>
            <?php } ?>

            <p>Click <a href="session-home.php?session=<?php print($pagecode); ?>">here</a> to go to the session page.</p>

            <?php mysqli_close($conn); ?>

        </main>

    </div>
</body>
</html>
