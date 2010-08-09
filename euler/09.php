<html>
<head>
	<title>Project Euler</title>
</head>
<body>
<?php
	$total = 0;
	$toaddupto = 1000;
	$product = 0;
	$a; $b; $c;
	for ($i = 1; ; $i++)
	{
		for ($j = 1; $j < $i; $j++)
		{
			$a = ($i * $i) - ($j * $j);
			$b = 2 * $i * $j;
			$c = ($i * $i) + ($j * $j);
			$total = $a + $b + $c;
			if (($toaddupto % $total) == 0)
			{
				$mb = $toaddupto / $total;
				$a = $a * $mb;
				$b = $b * $mb;
				$c = $c * $mb;
				break 2;
			}
		}
	}
	$product = $a * $b * $c;
	print $product;
?>
</body>
</html>
