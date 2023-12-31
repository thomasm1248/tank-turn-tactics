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
            
            <h2>Rules</h2>
            
            <h3>Action Points</h3>
            <p>Rather than taking turns, players play whenever they want. However, each action they take will cost them one Action Point. Each player starts with one Action Point, and gains another Action Point each day.</p>

            <h3>Actions</h3>
            <p>There are four actions you may take:<p>
            <ul>
                <li>Move your tank to an adjacent square on the grid</li>
                <li>Shoot another tank within your range</li>
                <li>Upgrade your range to reach one square further</li>
                <li>Give an Action Point to another tank within range</li>
            </ul>
            <p>Each of these actions will cost you one Action Point. If you give an Action Point to another tank, the Action Point you spend will be given to the other tank. If you shoot another tank, that tank looses a life. When a tank loses its third life, it is eliminated from the game.</p>

            <h3>Jury System</h3>
            <p>When players are eliminated, they aren't completely out of the game. They continue to contribute by voting on a player that they feel should receive an extra action point each day. Players that receive three or more votes will recieve two action points each day instead of one.</p>

        </main>

    </div>
</body>
</html>
