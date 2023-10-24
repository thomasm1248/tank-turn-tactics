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
            
            <h2><!-- Session Name -->Tanks Forever</h2>

            <h3><!-- Player Name -->Big Fat Tank</h3>

            <h4>Lives</h4>
            <!-- add one to three images of hearts to show the number of lives the player has left -->
            <div id="lives"></div>

            <h4>Actions</h4>
            <p>Action Points: <!-- put the number of Action Points here --></p>
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

        </main>

    </div>
</body>
</html>
