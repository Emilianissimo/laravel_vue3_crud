var scale = 1,
panning = false,
pointX = 0,
pointY = 0,
start = { x: 0, y: 0 },
zoom = document.getElementById("zoom");

document.addEventListener('hidden.bs.modal', event => {
    scale = 1
    setTransform()
})

function setTransform() {
    zoom.style.transform = "translate(" + pointX + "px, " + pointY + "px) scale(" + scale + ")";
}

zoom.onmousedown = function (e) {
    e.preventDefault();
    start = { x: e.clientX - pointX, y: e.clientY - pointY };
    panning = true;
    zoom.style.cursor = 'grabbing'
}

zoom.onmouseup = function (e) {
    panning = false;
    zoom.style.cursor = 'grabbing'
}

zoom.onmousemove = function (e) {
    zoom.style.cursor = 'grabbing'
    e.preventDefault();
    if (!panning) {
        return;
    }
    pointX = (e.clientX - start.x);
    pointY = (e.clientY - start.y);
    setTransform();
}

zoom.onwheel = function (e) {
    e.preventDefault();
    var delta = (e.wheelDelta ? e.wheelDelta : -e.deltaY);
    if(delta > 0){
        scale *= 1.2
        zoom.style.cursor = 'zoom-in'
    }else{
        if(scale >= 1){
            scale /= 1.2
            zoom.style.cursor = 'zoom-out'
        }
    }

    setTransform();
}
