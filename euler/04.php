<html>
<head>
	<title>Project Euler</title>
</head>
<body>
Largest palindrome of two 3 digit numbers
<?php
$largest; $string;
	
for ($i = 100; $i < 1000; $i++)
{
	for ($j = 100; $j < 1000; $j++)
	{
		$string = (string)($i * $j);
		if ($string == strrev($string) && ($i * $j > $largest))
		{
			$largest = (int)$string;
		}
	}
}
print $largest;
?>
</body>
</html>
