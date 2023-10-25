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

            <h3>Player Login</h3>
            <form action="POST">
                
                <input id="player-name" name="player-name" type="text" placeholder="player name"><br>

                <input id="password" name="password" type="password" placeholder="password"><br>

                <input id="login" type="submit" value="Login">

            </form>

            <a href=""><h3>View Map</h3></a>

            <h3>Share This Session</h3>
            <p>Share this page with anyone who wants to watch this session.</p>

        </main>

    </div>
</body>
</html>