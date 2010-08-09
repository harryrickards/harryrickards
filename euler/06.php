<html>
<head>
	<title>Project Euler</title>
</head>
<body>
The difference between the sum of the squares of the first one hundred
natural numbers and the square of the sum of the first one hundred
natural numbers is 
<?php
	$sum = 0;
	$square = 0;
	for ($i = 1; $i <= 100; $i++)
	{
		$sum = $sum + ($i * $i);
		$square = $square + $i;
	}
	$square = $square * $square;
	print $square - $sum;
?>
</body>
</html>
