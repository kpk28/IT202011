<?php
echo "Hello World <br/>";
echo "<br/>";

echo "1 - Create a numerical array of numbers <br/>";
$arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
echo "Made an array from 1 to 10 <br/>";

echo "<br/>";

echo "2 - Create a loop that loops over each number and shows their value <br/>";
foreach($arr as $val) {
	echo "$val <br/>";
}

echo "<br/>";

echo "3 - Create a loop that only outputs even numbers <br/>";
foreach($arr as $val) {
	if( ($val % 2) == 0) {
    	echo "$val <br/>";
    }
}

?> 

