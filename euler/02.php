<html>
<head>
	<title>Project Euler</title>
</head>
<body>
Sum of all even-valued Fibonacci sequence terms below 4 million
<?php
	$current = 1;
	$previous = 0;
	$previoustmp;
	$sum = 0;

	while ($current < 4000000)
	{
		$previoustmp = $current;
		$current = $current + $previous;
		$previous = $previoustmp;
		if (($current % 2) == 0)
		{
			$sum = $sum + $current;
		}
	}
		print $sum;
?>
</body>
</html>
