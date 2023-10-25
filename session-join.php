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

            <h3>Join This Session</h3>
            <form action="POST">
                
                <input id="player-name" name="player-name" type="text" placeholder="player name"><br>

                <textarea id="bio" name="bio" placeholder="player bio and/or contact info" cols="30" rows="15"></textarea><br>

                <input id="password" name="password" type="password" placeholder="password"><br>

                <input id="confirm-password" type="password" placeholder="confirm password"><br>

                <input id="join" type="submit" value="Join">

            </form>

            <h3>Share This Session</h3>
            <p>Share this page with anyone who wants to join this session.</p>

        </main>

    </div>
</body>
</html>