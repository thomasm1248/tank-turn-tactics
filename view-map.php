<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tank Turn Tactics</title>
    <meta charset="utf-8">
    <script src="demo-map-data.js"></script>
    <style>

/* This page has its own stylesheet */

body {
    margin: 0;
    padding: 0;
}

/* Canvas rules */

canvas {
    position: absolute;
    top: 0;
    left: 0;
}

/* Info box rules */

#info-box {
    z-index: -1;
    position: absolute;
    background-color: #131416;
    width: 25em;
    top: 0;
    bottom: 0;
    right: 0;
    padding: 2em 2em;
}

#info-box h2 {
    color: #62788d;
    font-size: 1.5em;
}

#info-box h3 {
    color: #62788d;
    font-size: 1.3em;
}

#info-box p {
    color: #62788d;
    font-size: 1.1em;
}

.life-heart {
    width: 3em;
}

    </style>
</head>
<body>

    <!-- use javascript to put various things in this box such as player stats and global action log when things on the map are clicked -->
    <div id="info-box">
    </div>

    <!-- draw the map on this canvas -->
    <canvas id="map-viewer"></canvas>

    <script src="map-viewer.js"></script>

</body>
</html>