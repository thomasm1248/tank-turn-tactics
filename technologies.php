<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tank Turn Tactics</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/logo.svg">
</head>
<body>
    <div id="container">

        <img id="logo" src="images/logo.svg">

        <header>
            <h1>Tank Turn Tactics</h1>
        </header>

        <nav>
            <?php
                include("nav-insert.html");
            ?>
        </nav>

        <main>

            <h2>Technologies Used</h2>

            <h3>XHTML</h3>
            <p>All HTML pages follow XHTML standards.</p>

            <h3>HTML5</h3>
            <p>All the pages were written in HTML5.</p>

            <h3>HTML5 Canvas element</h3>
            <p>The map viewer uses a canvas element to display the map of a game session.</p>

            <h3>HTML5 Video element</h3>
            <p>On the home page, video elements are embeded to display YouTube videos.</p>

            <h3>CSS</h3>
            <p>CSS is used to style each page. All the pages use the same stylesheet, except for the map viewer, which has its own CSS.</p>

            <h3>Javascript</h3>
            <p>Javascript is used on the map viewer page to parse JSON data from the server, and draw an interactable map on the canvas.</p>

            <h3>Dynamic Javascript</h3>
            <p>Javascript is used to change the contents of the info box on the right side of the map viewer whenever the user clicks on a tank.</p>

            <h3>PHP</h3>
            <p>PHP is used throughout the entire project to generate pages, JSON data, and to handle all the logic.</p>

            <h3>Database</h3>
            <p>A MySQL database is used to store all the information about the game sessions, the players participating in each session, and the history of actions made during each session.</p>

            <h3>SVG Logo</h3>
            <p>I made a simple logo in the shape of a T, and I put it in the top right corner of every web-page, and made it the tab icon too. I'm still deciding what to do with it though.</p>

            <h3>A webserver</h3>
            <p>This project was hosted on XAMPP during delelopment, and has been tested on a Raspberry Pi LAMP stack as well. It should work on any system set up with some sort of LAMP stack as long as the database has been configured correctly.</p>

            <h3>XHTML validation for all pages</h3>
            <p>All pages have been validated using <a href="https://validator.w3.org/" target="_blank">the w3schools markup validation service</a>.</p>

        </main>

    </div>
</body>
</html>
