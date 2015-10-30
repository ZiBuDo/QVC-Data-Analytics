<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class test{
	
}


$test = new test;
$test->name = "hello";
$test->y = 3;
$test->drilldown = "hello";

echo json_encode($test);

$test2 = array(1,2,3,4);
echo json_encode($test2);

$test3 = array($test, $test);
echo json_encode($test3);

//save it to a file

?>
