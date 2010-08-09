<?PHP

require_once('functions.php');
require_once('_db.php');
global $time;
$time=time();
init();

if(isset($_GET['id'])){
	$where['id']=(int)$_GET['id'];
	$db->table="stations";
	if(!$db->remove_single($where)){
		header("HTTP/1.1 400 Bad Request");
		echo " db error";
	}
	exit;
}


header("Content-type: image/svg+xml");
echo '<?xml version="1.0" encoding="iso-8859-1" ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN"
"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg xmlns="http://www.w3.org/2000/svg"
height="1386px" width="2079px"
xmlns:xlink="http://www.w3.org/1999/xlink"
viewBox="0 0 2079 1386">
<script xlink:href="http://ajax.googleapis.com/ajax/libs/prototype/1.6.1.0/prototype.js" type="text/emacscript"></script>
<script xlink:href="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.3/scriptaculous.js" type="text/emacscript"></script>
<script xlink:href="delete.js" type="text/emacscript"></script>';

drawmap($time);

echo '</svg>';
