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
            
            <h2>Session Admin</h2>

            <h3>Status:</h3>
            <p><!-- Session status -->Not Started Yet</p>

            <!-- Start session button should only be visible when the session hasn't started yet -->
            <button id="start-session">Start Session</button><br>

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

        </main>

    </div>
</body>
</html>
