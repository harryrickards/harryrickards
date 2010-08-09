<?PHP

require_once("_db.php");

$db=new Database("localhost","user","pass","db");

function init(){
	global $time;
	if (isset($_GET["hours"]) && isset($_GET["minutes"]) && isset($_GET["days"]) && isset($_GET["months"]) && isset($_GET["years"])) {
		$hours = $_GET["hours"];
		$minutes = $_GET["minutes"];
		$days = $_GET["days"];
		$months = $_GET["months"];
		$years = $_GET["years"];
		$time_s = "$hours:$minutes $years-$months-$days";
		$time = strtotime($time_s);
	} else {
		$time = time();
	}
	$time = $time - ($time % 120);

	if ($time > time()) {
		print "You cannot choose a time or date in the future";
		exit;
	} 

}

function print_header($inc=""){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>TubeSmart</title>
	<meta http-equiv=\'Content-Type\' content=\'application/xhtml+xml\' />
	<link rel="stylesheet" href="styles.css" type="text/css"/>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.1.0/prototype.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.3/scriptaculous.js?load=effects,dragdrop"></script>
	'.$inc.'
	</head>
<body>
<div id="content">';
}

	function drawtime($time) {
/*		$mins = 10;
		$hours = 5;
		$days = 5;
		print "<table border = \"1\">";
		print "<tr>";
		for ($i = 1; $i <= $mins; $i++) {
			$time_to_print = $time - ($i * 120);
			print "<td><a href='?time=".$time_to_print."'>"
			 . ($i * 2) . " minutes before<br/>"
			 . date("H:i", $time_to_print). "</a></td>";
		}

		print "</tr><tr>";

		for ($i = 1; $i <= $hours; $i++) {

			$time_to_print = $time - ($i * 60 * 120);
			print "<td><a href='?time=".$time_to_print."'>"
			 . $i * 2 . " hours before<br/>"
 			 . date("H:i", $time_to_print) . "</a></td>";
		}
		
		print "</tr><tr>";

		for ($i = 1; $i <= $days; $i++) {
			$time_to_print = $time - ($i * 60 * 120 * 24);
			print "<td><a href='?time=".$time_to_print."'>"
			 . $i * 2 . " before ago<br/>"
			 . date("d/m", $time_to_print) . "</a></td>";
		}
		print "</tr></table>";
		$ctime = time();
		$ctime = $ctime - ($ctime % 120);

		print "<a href='?time=".$ctime."'>Return to current time</a>"; */

		for ($i = 0; $i < 24; $i++) {
			$hours[] = str_pad($i,2,0,'STR_PAD_LEFT');
		}

		for ($i = 0; $i < 60; $i+=2) {
			$minutes[] = str_pad($i,2,0,'STR_PAD_LEFT');
		}

		for ($i = 1; $i < 32; $i++) {
			$days[] = str_pad($i,2,0,'STR_PAD_LEFT');
		}

		for ($i = 1; $i < 13; $i++) {
			$months[] = str_pad($i,2,0,'STR_PAD_LEFT');
		}

		$years[] = 2010;

		print '<form name="timeselect" action="' . $PHP_SELF . '" method="get">';
		print 'Time: Hours: <select name="hours">';
			foreach ($hours as $value) {
				if ($value == date('H')) {
					print "<option value='$value' selected='selected'>$value</option>";
				} else {
					print "<option value='$value'>$value</option>";
				}
			}
		print '</select>';

                print '<select name="minutes">';
                        foreach ($minutes as $value) {
				$mins = date('i');
				$mins = $mins - ($mins % 2);
				if ($value == date('i')) {
	                                print "<option value='$value' selected='selected'>$value</option>";
				} else {
	                                print "<option value='$value'>$value</option>";
				}
                        }
                print '</select>';

                print '<br/>Date: <select name="days">';
                        foreach ($days as $value) {
				if ($value == date('d')) {
	                                print "<option value='$value' selected='selected'>$value</option>";
				} else {
	                                print "<option value='$value'>$value</option>";
				}
                        }
                print '</select>';
                print '<select name="months">';
                        foreach ($months as $value) {
				if ($value == date('m')) {
	                               print "<option value='$value' selected='selected'>$value</option>";
				} else {
	                               print "<option value='$value'>$value</option>";
				}
                        }
                print '</select>';
                print '<select name="years">';
                        foreach ($years as $value) {
				if ($value == date('Y')) {
	                                print "<option value='$value' selected='selected'>$value</option>";
				} else {
	                                print "<option value='$value'>$value</option>";
				}
                        }
                print '</select>';

		print '<input type="submit" value="Submit"/>';

		print '</form>';


	}


	function drawmap($time) {
		global $db;
		$db->table="stations";
		$stations_results = $db->retrieve_all(500);

		

		//echo ' <image x="10" y="10" width="2079px" height="1386px"
		echo ' <image x="0" y="10" width="100%" height="100%"
		xlink:href="/assets/tube-map.gif" />';
		  //title
		  // REWRITE TO SINGLE QUERY - REQ. ALL FROM COUNTS WHERE TIMESTAMP IS VALID; USE THAT TO POPULATE MAP
		if($db->num_rows!=0){
			foreach($stations_results as $station) {
				$stations[$station->id]['x']=$station->x;
				$stations[$station->id]['y']=$station->y;
				$stations[$station->id]['tag']=$station->tag;
				$stations[$station->id]['id']=$station->id;
				$stations[$station->id]['name']=$station->name;
				$stations[$station->id]['type']=$station->type;
					
			}
			$db->table='counts';
			$res=$db->retrieve_where('timestamp',$time,5000);
			foreach ($res as $count){
				$counts[$count->station]=$count;
			}
			$opacity=0;
			foreach($counts as $key => $value){
				switch($stations[$key]['type']){
					case 'disabled':
					case 'major':
					$opacity=0.7;
					$stroke=0;
					break;
					case 'minor':
					$opacity=0.3;
					$stroke=0;
					break;
				}
				echo '<circle cx="'.$stations[$key]['x'].'" cy="'.$stations[$key]['y'].'" r="'.(($value->p_in)/40).'" fill="#33CCFF" stroke="#33CCFF" class="station" stroke-width="'.$stroke.'" fill-opacity="'.($opacity).'" onclick="stationClick(\'' . $stations[$key]['tag'] . '\',\'' . $stations[$key]['name'] . '\',' . $stations[$key]['id'] . ',' . $value->p_in . ')" />';
			}
				//$db->table='counts';
				//$searchterms['tag']=$station->tag;
				//$searchterms['timestamp']=$time;
				//$time_results = $db->search($searchterms,1,0);
				//if ($db->num_rows!=0){
				//foreach ($time_results as $result) {
					//print '<circle cx="'.$station->x.'" cy="'.$station->y.'" r="'.(($time_results[0]->p_in)/30).'" fill="blue"/>';
				//}
				//} 
			//}
		}
}
?>
