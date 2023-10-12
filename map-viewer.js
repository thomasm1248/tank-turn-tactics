
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
for(var i = 0; i < data.map.width; i++) map.push([]);
for(var i = 0; i < data.tanks.length; i++) {
    var tank = data.tanks[i];
    map[tank.x][tank.y] = tank;
}

// Setup the canvas
var canvas = document.getElementById("map-viewer");
var ctx = canvas.getContext("2d");

// Get a reference to info-box
var infoBox = document.getElementById("info-box");

// Camera object
var cam = {
    x: data.map.width/2,
    y: data.map.height/2,
    zoom: 1 // Change later
};
// Get predicted dimensions of canvas
var w = window.innerWidth - infoBox.offsetWidth;
var h = window.innerHeight;
// Calculate zoom to fit map in canvas
if(data.map.width / data.map.height > w / h) {
    // Match map to width of screen
    cam.zoom = 0.7 * w / data.map.width;
} else {
    // Match map to height of screen
    cam.zoom = 0.7 * h / data.map.height;
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
    // Draw tanks
    for(var i = 0; i < data.tanks.length; i++) {
        ctx.save();
        ctx.translate(data.tanks[i].x, data.tanks[i].y);
        ctx.fillStyle = "blue";
        ctx.fillRect(0.1, 0.1, 0.8, 0.8);
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
        } else {
            // Display tank info
            var tank = cellContents;
            infoBox.innerHTML = "<h2>"+tank.name+"</h2><div id='lives'></div><h3>Action Points: "+tank.action_points+"</h3><p>"+tank.bio+"</p>";
            // Display a heart for each life the tank has
            var images = "";
            for(var i = 0; i < tank.lives; i++) {
                images += "<img class='life-heart' src='images/heart.png'>";
            }
            document.getElementById("lives").innerHTML = images;
        }
    }
}, false);

// Display the log
displayLog();

// Start running the animation function
draw();
