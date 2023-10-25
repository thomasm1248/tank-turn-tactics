<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tank Turn Tactics</title>
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

            ?>
            
            <h2><?php print($sessionname);?>: Session Admin</h2>

            <h3>Status:</h3>
            <p><?php print($status);?></p>

            <?php if($status === 'waiting') { ?>
                <button id="start-session">Start Session</button><br>
            <?php } ?>
            <button id="end-session">End Session</button>
            
            <h3>Players</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Lives Left</th>
                </tr>

                <!-- Add table entries for players that have joined -->

                <!-- Example row:
                <tr>
                    <td>123</td>
                    <td>Big Fat Tank</td>
                    <td>5, 6</td>
                    <td>3</td>
                </tr>
                -->

                <!-- placeholder that should be removed once a player joins -->
                <tr>
                    <td colspan="4">No players have joined yet.</td>
                </tr>

            </table>
            <form action="POST">
                
                <label>
                    Player:
                    <select id="player" name="player" value="">
                        <option value=""></option>
                        
                        <!-- Add player names and id's -->

                        <!-- Example option:
                        <option value="123">Big Fat Tank</option>
                        -->

                    </select>
                </label>

                <input id="submit" type="submit" value="Kick!">

            </form>

            <?php
                mysqli_close($conn);
            ?>

        </main>

    </div>
</body>
</html>
