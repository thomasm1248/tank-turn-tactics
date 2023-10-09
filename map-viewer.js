
// Demo: reset the size of the canvas to match the size of the screen
var canvas = document.getElementById("map-viewer");
var ctx = canvas.getContext("2d");
function draw() {
    window.requestAnimationFrame(draw);
    canvas.width = window.innerWidth - document.getElementById("info-box").offsetWidth;
    canvas.height = window.innerHeight;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.lineTo(canvas.width, canvas.height);
    ctx.moveTo(canvas.width, 0);
    ctx.lineTo(0, canvas.height);
    ctx.stroke();
}
draw();
