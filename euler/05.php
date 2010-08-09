<html>
<head>
	<title>Project Euler</title>
</head>
<body>
Smallest number that is evenly disible by all the numbers from 1 to 20 is 
<?php
	$lim = 17;
	for ($i = $lim; ; $i++)
	{
		for ($j = $lim; $j > 0; $j--)
		{
			if (!is_int($i/$j))
			{
				continue 2;
			}
		}
		break;
	}
	echo $i;
?>
</body>
</html>
