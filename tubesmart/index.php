<?php

require_once('functions.php');
require_once('_db.php');
global $time;
$time=time();
init();
print_header('
	<script type="text/javascript" src="home.js"></script>
');
echo '
<div id="header">
	<img src="assets/logo.jpg" width="190" height="81" alt="Logo"/><h1>TubeSmart</h1><h3 style="color:#fff;float:right;padding:20px;">'.date("H:i jS F Y",$time).'</h3>
	<h2>What TfL are (still) doing wrong.</h2>
</div>';

?>
<div id="mapcontainer">
	<div class="overlay">
		&nbsp;
	</div>

	<div style="top:-420px;left:-500px;position:relative">
		 <object id="draggable" data="map.php?content-type=.svg&amp;time=<?php print $time; ?>" type="image/svg+xml"
		    height="1386" width="2079"> 
			<embed src="map.php?content-type=.svg&amp;time=<?php print $time; ?>" type="image/svg+xml"
			    height="1386" width="2079"
			    pluginspage="http://www.adobe.com/svg/viewer/install/" />
		</object>
	</div>
</div>
<div id="dropdown">
<div id="todrop" onclick="showSlider()" style="float: right;">
<a href="#" onclick="showSlider();return false;">&nbsp;Back in time <small>&#9660;</small></a>
</div>
<?php
$ctime = time();
$ctime = $ctime - ($ctime % 120);
if ($time != $ctime)
{
         echo "<div style='float: right;'><a href='/'> Return to current time</a> | </div>";
}
?>
</div>
<div id="slider" style="display:none">
	<?php drawtime($time); ?>
	<hr/>
</div>
<div id="left">
	<div style="width:260px"><script language="javascript" type="text/javascript" src="http://www.tfl.gov.uk/tfl/syndication/widgets/serviceboard/embeddable/serviceboard-iframe-stretchy.js"></script></div> 
</div>
<div id="right">
<img src="less.png" class="align-right"/>Less people<br/>
<img src="more.png" class="align-right"/>More people<br/>
</div>
<div id="footer" style="clear:both">
	TfHell 2.0 or whatever we've called it
</div>
</div>
</body>
</html>
