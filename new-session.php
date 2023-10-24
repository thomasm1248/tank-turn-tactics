<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tank Turn Tactics</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <script>

var l = id => document.getElementById(id);

function restoreDefaults() {
    l("width").value = "20";
    l("height").value = "15";
    l("action-point-freq").value = "1";
    l("time-unit").value = "days";
}

    </script>
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

            <h2>New Session</h2>

            <form action="create-session.php">

                <label for="session-name">Session Name:</label>
                <input id="session-name" name="session-name" type="text"><br>

                <label>
                    Map Dimensions:
                    <input id="width" name="width" type="number" value="20" size="4" min="10" max="100">
                    &times;
                    <input id="height" name="height" type="number" value="15" size="4" min="10" max="100">
                </label><br>

                <label>
                    Players get a new action point every
                    <input id="action-point-freq" name="action-point-freq" type="number" value="1" min="1" max="120" size="3">
                    <select id="time-unit" name="time-unit" value="days">
                        <option>days</option>
                        <option>hours</option>
                        <option>minutes</option>
                    </select>
                </label><br>

                <input id="admin-name" name="admin-name" type="text" placeholder="admin username"><br>

                <input id="password" name="password" type="password" placeholder="password"><br>

                <input id="confirm-password" type="password" placeholder="confirm password"><br>

                <button id="reset" type="button" onclick="restoreDefaults()">Restore Defaults</button>
                <input id="submit" type="submit" value="Create New Session">

            </form>
        </main>

    </div>
</body>
</html>
