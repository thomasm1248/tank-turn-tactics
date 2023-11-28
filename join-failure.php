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

            <p>There was an issue adding you to the session. Please double check that your username is unique, and try again.</p>

            <!-- add a link back to the session join page -->
            <a href="session-home.php?session=<?php print($_GET['session']); ?>">Back to session home</a>
        </main>

    </div>
</body>
</html>
