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

            <!-- add the name of the player this player is currently voting for -->
            <p>You are currently voting for </p>

            <form action="POST">

                <label>
                    Update vote:
                    <select id="player" name="player">
                        <!-- add list of players within range -->
                    </select>
                </label>

                <input id="submit" type="submit" value="Update Vote">

            </form>

        </main>

    </div>
</body>
</html>
