<html>
<head>
	<title>Project Euler</title>
</head>
<body>
Sum of all multiples of 3 and 5 below 1000 is: 
<?php
	$total = 0;
	for ($i = 1; $i < 1000; $i++)
	{
		if (($i % 3) == '0' || ($i % 5) == '0')
		{
			$total = $total + $i;
		}
	}
	print $total;
?>
</body>
</html>
