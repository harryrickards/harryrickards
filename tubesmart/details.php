<?php
	require_once("functions.php");
	require_once("gChart.php");
	print_header();
	if(!isset($_GET['tag'])) {
		print "Station tag needs to be passed via GET, and it wasn't";
		exit();
	}
	$tag = $_GET['tag'];

	$db = new Database("localhost","user","pass","db");
	$db->table="stations";
	$stations_result = $db->retrieve_where('tag', $tag, 1, 0);

	foreach ($stations_result as $value) {
		$name = $value->name;
	}

	$time = time();
	$time = $time - ($time % 120);

	$db->table="counts";
	$counts_results = $db->retrieve_where('tag', $tag, 999999999999, 0);

	$i = 0;
	foreach ($counts_results as $value) {
		if ($value->timestamp == $time) {
			$p_in = $value->p_in;
		} 
		if($value->timestamp <= $time) {
			if($i % 15 == 0 || $value->timestamp == $time) {
				$data[] = $value->p_in;
				if($i % 90 == 0) {
					$timestamps[] = date('H:i',$value->timestamp);
				}
			}
		}
		$i++;
	}

	for($i = 2; $i < count($data); $i++) {
		$averages[] = round(($data[$i] + $data[$i-1] + $data[$i-2])/3);
	}

	$averages = array_reverse($averages);

	$p_in = round($averages[count($averages)-1]);

	$lineChart = new gLineChart(700,400);
	$lineChart->addDataSet($averages);
	$lineChart->setLegend(array("People"));
	$lineChart->setColors(array("ff3344"));
	$lineChart->setVisibleAxes(array('x','y'));
	$lineChart->setDataRange(0,1000);
	$lineChart->addAxisRange(1, 0, 1000);
	$lineChart->addAxisLabel(0, $timestamps);
	$lineChart->addLineFill(B,'76A4FB',0,0);
	$lineChart->setGridLines(100,10);
	
	$url = $lineChart->getUrl();

	print "<p><h1>$name</h1></p>";	
	print "Number of people currently at the station: $p_in <br/>";


	print "<img src='$url' />";
	print "<br/><i>A graph showing the number of people at $name station over the last day</i><br/>";
?>
</div>
</body>
</html>
