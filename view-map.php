<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tank Turn Tactics</title>
    <meta charset="utf-8">
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
    overflow: scroll;
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

    <script>



// Settings
var config = {
    maxzoom: 150,
    minzoom: 20,
    clickpositionthreshold: 1,
    axismarkercolor: "gray",
    axistextstyle: "0.4px Arial",
    dotsize: 0.08,
    dotcolor: "gray",
    backgroundcolor: "white"
};

// Process data
// Make a 2d array with tank objects
var map = [];
function placeTanksOnMap() {
    map = [];
    for(var i = 0; i < data.map.width; i++) map.push([]);
    for(var i = 0; i < data.tanks.length; i++) {
        var tank = data.tanks[i];
        map[tank.x][tank.y] = tank;
    }
}

// Setup the canvas
var canvas = document.getElementById("map-viewer");
var ctx = canvas.getContext("2d");

// Initialize data with nothing
data = {
    map: {
        width: 0,
        height: 0
    },
    log: [
    ],
    tanks: [
    ]
};

// Get a reference to info-box
var infoBox = document.getElementById("info-box");

// Keep track of what the player is looking at in the info box
var boxContents = {
    type: "log",
    player: ""
};

// Keep track of which tank the mouse is hovering over
var hoveredTank = "";

// Camera object
var cam = {
    x: 0,
    y: 0,
    zoom: 1 // Change later
};

// A function for setting up the camera after the first AJAX response
var cameraIsSetup = false;
function setupCamera() {
    // Determine whether or not the user has already opened their dashboard
    // and if they have, focus on their tank. Otherwise, configure the camera
    // to fit the whole map in the viewport.
    <?php
        // Get the id of the last player dashboard viewed by the user
        session_start();
        if(isset($_SESSION['player'])) {
            $playerid = $_SESSION['player'];
        } else {
            $playerid = -1;
        }
    ?>
    var playerid = <?php print($playerid) ?>; // -1: no player
    if(playerid >= 0) {
        // Focus on player
        var player;
        for(var i = 0; i < data.tanks.length; i++) {
            if(data.tanks[i].id == playerid) {
                player = data.tanks[i];
                break;
            }
        }
        if(player !== undefined) {
            // Focus on a single player
            cam.x = player.x + 0.5;
            cam.y = player.y + 0.5;
            cam.zoom = 100;
            // Set the info box to focuse on the player
            boxContents.type = "player";
            boxContents.player = player.id;
            return;
        }
        // Continue on to fit the map to the viewport
    }
    // Fit map to viewport because focusing on a player didn't work
    // Get predicted dimensions of canvas
    var w = window.innerWidth - infoBox.offsetWidth;
    var h = window.innerHeight;
    // Place camera in the middle
    cam.x = data.map.width / 2;
    cam.y = data.map.height / 2;
    // Calculate zoom to fit map in canvas
    if(data.map.width / data.map.height > w / h) {
        // Match map to width of screen
        cam.zoom = 0.7 * w / data.map.width;
    } else {
        // Match map to height of screen
        cam.zoom = 0.7 * h / data.map.height;
    }
}

// Mouse object for tracking the mouse/touch
var mouse = {
    startX: 0,
    startY: 0,
    prevX: 0,
    prevY: 0,
    down: false,
    x: 0,
    y: 0
};

// Util functions
function getCenteredMouse() {
    return {
        x: mouse.x - canvas.width/2,
        y: mouse.y - canvas.height/2
    };
}
function getCamMouse() {
    return {
        x: (mouse.x - canvas.width/2) / cam.zoom + cam.x,
        y: (mouse.y - canvas.height/2) / cam.zoom + cam.y
    };
}
function moveCam(xOffset, yOffset) {
    cam.x += (xOffset) / cam.zoom;
    cam.y += (yOffset) / cam.zoom;
};
function bindCamToBox() {
    if(cam.x < 0) cam.x = 0;
    if(cam.y < 0) cam.y = 0;
    if(cam.x > data.map.width) cam.x = data.map.width;
    if(cam.y > data.map.height) cam.y = data.map.height;
};

// Main logic functions
function drawMap() {
    // Draw dots between cells
    var size = config.dotsize;
    ctx.beginPath();
    for(var i = 0; i <= data.map.width; i++) {
        for(var j = 0; j <= data.map.height; j++) {
            ctx.save();
            ctx.translate(i, j);
            ctx.moveTo(0, -size);
            ctx.lineTo(size, 0);
            ctx.lineTo(0, size);
            ctx.lineTo(-size, 0);
            ctx.restore();
        }
    }
    ctx.fillStyle = config.dotcolor;
    ctx.fill();
    // Draw axis markers
    ctx.fillStyle = config.axismarkercolor;
    ctx.font = config.axistextstyle;
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    for(var i = 0; i < data.map.width; i++) {
        ctx.fillText(i, i+0.5, -1.15);
    }
    for(var i = 0; i < data.map.height; i++) {
        ctx.fillText(i, -1, i+0.35);
    }
    // Draw ranges
    ctx.save();
    ctx.beginPath();
    ctx.rect(0.1, 0.1, data.map.width-0.2, data.map.height-0.2);
    ctx.clip();
    for(var i = 0; i < data.tanks.length; i++) {
        ctx.save();
        ctx.translate(data.tanks[i].x+0.5, data.tanks[i].y+0.5);
        ctx.fillStyle = "black";
        ctx.globalAlpha = 0.06;
        var r = data.tanks[i].range;
        ctx.fillRect(-r-0.4, -r-0.4, r*2+0.8, r*2+0.8);
        ctx.restore();
    }
    ctx.restore();
    // Draw tanks
    for(var i = 0; i < data.tanks.length; i++) {
        ctx.save();
        ctx.translate(data.tanks[i].x, data.tanks[i].y);
        ctx.save(); // For HTML5 Canvas shadow for highlighting
        // Draw highlight if applicable
        if(boxContents.type === "player" && data.tanks[i].id === boxContents.player) {
            // Highlight because the user is viewing the tank's stats in infobox
            ctx.shadowColor = "orange";
            ctx.shadowBlur = 10;
            ctx.strokeStyle = "orange";
        } else if(data.tanks[i].id === hoveredTank) {
            // Highlight because the mouse is hovering over the tank
            ctx.shadowColor = "blue";
            ctx.shadowBlur = 10;
            ctx.strokeStyle = "blue";
        } else {
            // No highlight
            ctx.strokeStyle = "transparent";
        }
        // Draw square
        ctx.fillStyle = "#323232";
        ctx.beginPath();
        ctx.rect(0.1, 0.1, 0.8, 0.8);
        ctx.lineWidth = 0.1;
        ctx.stroke();
        ctx.fill();
        ctx.restore(); // Revert back to no shadow
        // Draw lives
        ctx.fillStyle = "#e33636";
        var x = 0.2;
        var y = 0.7;
        var shift = 0.2;
        ctx.beginPath();
        for(var j = 0; j < data.tanks[i].lives; j++) {
            ctx.fillRect(x, y, 0.05, 0.1);
            y -= shift;
        }
        // Draw action point tokens
        ctx.fillStyle = "green";
        var x = 0.75;
        var y = 0.8;
        var shift = 0.1;
        for(var j = 0; j < data.tanks[i].action_points && j < 7; j++) {
            ctx.fillRect(x, y, 0.1, 0.05);
            y -= shift;
        }
        // Draw votes
        ctx.fillStyle = "yellow";
        var x = 0.45;
        var y = 0.7;
        var shift = 0.15;
        for(var j = 0; j < data.tanks[i].votes && j < 3; j++) {
            ctx.fillRect(x, y, 0.1, 0.1);
            y -= shift;
        }
        ctx.restore();
    }
}
function displayLog() {
    var content = "<h2>Action Log</h2>";
    for(var i = 0; i < data.log.length; i++) {
        content += "<h3>"+data.log[i].tank+"</h3><p>"+data.log[i].action+"</p>";
    }
    infoBox.innerHTML = content;
}
function displayTank(tank) {
    infoBox.innerHTML = "<h2>"+tank.name+"</h2><div id='lives'></div><h3>Action Points: "+tank.action_points+"</h3><h3>Votes Received: "+tank.votes+"</h3><p>"+tank.bio+"</p>";
    // Display a heart for each life the tank has
    var images = "";
    for(var i = 0; i < tank.lives; i++) {
        images += "<img class='life-heart' src='images/heart.png'>";
    }
    document.getElementById("lives").innerHTML = images;
}
function getPlayerById(id) {
    for(var i = 0; i < data.tanks.length; i++) {
        if(data.tanks[i].id === id) {
            return data.tanks[i];
        }
    }
    return undefined;
}
function refreshInfoBox() {
    if(boxContents.type === "log") {
        displayLog();
    } else {
        var player = getPlayerById(boxContents.player);
        if(player !== undefined) {
            displayTank(player);
        } else {
            displayLog();
            boxContents.type = "log";
        }
    }
}

// The draw function that repeats every animation frame
function draw() {
    // Resize canvas
    canvas.width = window.innerWidth - infoBox.offsetWidth;
    canvas.height = window.innerHeight;
    // Request another frame
    window.requestAnimationFrame(draw);
    // Clear screen
    ctx.fillStyle = config.backgroundcolor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    // Tranlate canvas to cam position
    ctx.save();
    ctx.translate(canvas.width/2, canvas.height/2);
    ctx.scale(cam.zoom, cam.zoom);
    ctx.translate(-cam.x, -cam.y);
    // Draw everything
    drawMap();
    ctx.restore();
}

// Event listeners
canvas.addEventListener("mousedown", function(e) {
    mouse.down = true;
    mouse.startX = mouse.x;
    mouse.startY = mouse.y;
}, false);
canvas.addEventListener("mouseup", function(e) {
    mouse.down = false;
}, false);
canvas.addEventListener("mousemove", function(e) {
    mouse.prevX = mouse.x;
    mouse.prevY = mouse.y;
    mouse.x = e.offsetX;
    mouse.y = e.offsetY;
    // Move camera
    if(mouse.down) {
        moveCam(mouse.prevX - mouse.x, mouse.prevY - mouse.y);
        // Don't let user move camera out of box
        bindCamToBox();
    }
    // Highlight tank if mouse is over it
    var cell = getCamMouse();
    cell.x = Math.floor(cell.x);
    cell.y = Math.floor(cell.y);
    var cellContents;
    if(map[cell.x] !== undefined) {
        cellContents = map[cell.x][cell.y];
    }
    if(cellContents === undefined) {
        hoveredTank = "";
    } else {
        hoveredTank = cellContents.id;
    }
}, false);
canvas.addEventListener("gestureend", function(e) {
    cam.zoom *= e.scale;
    // Don't let user zoom out too much
    if(cam.zoom < config.minzoom) cam.zoom = config.minzoom;
    // Don't let user zoom in too much
    if(cam.zoom > config.maxzoom) cam.zoom = config.maxzoom;
}, false);
canvas.addEventListener("wheel", function(e) {
    // Get mouse's location
    var m = getCenteredMouse();
    // Move mouse's location to center of screen
    moveCam(m.x, m.y);
    // Zoom
    if(e.deltaY > 0) {
        cam.zoom *= 0.9;
        // Don't let user zoom out too much
        if(cam.zoom < config.minzoom) cam.zoom = config.minzoom;
    } else if(e.deltaY < 0) {
        cam.zoom /= 0.9;
        // Don't let user zoom in too much
        if(cam.zoom > config.maxzoom) cam.zoom = config.maxzoom;
    }
    // Move mouse's location back
    moveCam(-m.x, -m.y);
    // Don't let zooming move the camera outside of box
    bindCamToBox();
}, false);
canvas.addEventListener("click", function(e) {
    var diff = {
        x: mouse.x - mouse.startX,
        y: mouse.y - mouse.startY
    };
    var dist = Math.sqrt(diff.x*diff.x + diff.y*diff.y);
    if(dist < config.clickpositionthreshold) {
        // Click on map, update info-box with relevant data
        var cell = getCamMouse();
        cell.x = Math.floor(cell.x);
        cell.y = Math.floor(cell.y);
        var cellContents;
        if(map[cell.x] !== undefined) {
            cellContents = map[cell.x][cell.y];
        }
        if(cellContents === undefined) {
            displayLog();
            boxContents.type = "log";
        } else {
            displayTank(cellContents);
            boxContents.type = "player";
            boxContents.player = cellContents.id;
        }
    }
}, false);

// Get data from server every 10 seconds
var req = new XMLHttpRequest();
<?php
$pagecode = $_GET['session'];
?>
function refreshData() {
    req.open("GET", "map-state.php?session=<?php print($pagecode); ?>", true);
    req.send();
}
req.onreadystatechange = function() {
    if(this.readyState === 4 && this.status === 200) {
        data = JSON.parse(this.responseText);
        placeTanksOnMap();
        // Setup camera if it hasn't been done yet
        if(!cameraIsSetup) {
            setupCamera();
            cameraIsSetup = true;
        }
        // This must be done after setting up the camera since the info box
        // may have been set to view a particular player
        refreshInfoBox();
        // Do it again in 10 seconds
        setTimeout(refreshData, 10000);
    }
}
refreshData();

// Start running the animation function
draw();



    </script>

</body>
</html>
