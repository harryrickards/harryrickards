<?php
//Put $station->id into 

	require "_db.php";

	$db = new Database("localhost","user","pass","db");
	$db->table = "stations";
	$stations = $db->retrieve_all(500);

	$x = 0;

	if($db->num_rows!=0) {
		foreach($stations as $station) {
			$time = time();
			$time = $time - ($time % 120);
			$time_s = $time - (60 * 60 * 24);
			$time_e = $time + (60 * 60 * 24);
			for ($i = $time_s; $i < $time_e; $i+=120) {
				$tag = $station->tag;
				$p_in = rand('200','1000');
				$p_out = rand('200','1000');
				$closed_num = rand('1','25');
				if ($closed_num == '14') {
					$closed == true;
				} else {
					$closed == false;
				}
				$timestamp = $i;
				$station_id = $station->id;
				$db->table = "counts";
				$record = array('tag' => $tag, 'p_in' => $p_in, 'p_out' => $p_out, 'closed' => $closed, 'timestamp' => $timestamp, 'station' => $station_id);
				$x++;
				$db->insert_single($record);
			}
		}
	}

	echo $x;
?>

