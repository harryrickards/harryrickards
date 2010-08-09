<?PHP

require_once('functions.php');
global $time;
$time=time();
init();

header("Content-type: image/svg+xml");
echo '<?xml version="1.0" encoding="iso-8859-1" ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN"
"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg id="svgElem" width="100%" height="100%" version="1.1" viewBox="1070 1010 2097 1386"
    xmlns="http://www.w3.org/2000/svg" 
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xmlns:drag="http://www.codedread.com/dragsvg"
    onload="initializeDraggableElements(); enableDrag(document.getElementById(\'widget\'));"
    onmouseup="mouseUp(evt)"
    onmousemove="mouseMove(evt)"> 
<script xlink:href="map.js" type="text/emacscript"></script>
<script id="draggableLibrary" xlink:href="dragsvg.js" />
<style data="text/css">
	#map {
		cursor: default;
	}
</style>
<g id="map" drag:enable="true" transform="translate(1000,1000)">';
drawmap($time);

echo '</g></svg>';
