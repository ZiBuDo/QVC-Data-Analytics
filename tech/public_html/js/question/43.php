<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "tech";
$password = "tech";
$dbname = "tech";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT unix_timestamp(ORDER_DATE), TIME_TO_SEC(ORDER_TIME), TOTAL_LINE_AMT FROM order_master ORDER BY ORDER_DATE ASC, ORDER_TIME ASC";
$result = mysqli_query($conn, $sql);
$march = 1425185999000; //ms of march 1st 0:00:00 or right before
$data = array();
$start = 0;
$sales = 0;
$average = array();
while($row = mysqli_fetch_assoc($result)) {
		$time = ($row["unix_timestamp(ORDER_DATE)"] * 1000) + ($row["TIME_TO_SEC(ORDER_TIME)"] * 1000);
		if($time > $march){
			if($start == 0){
				$start = $time;
			}else{
				$curr = $time;
				$diff = abs($curr - $start);
				if($diff >= 43200000){
					$data[] = array($start, $sales);
					$average[] = $sales;
					$sales= 0;
					$start = $curr;
				}
			}
			$sales = $sales + $row["TOTAL_LINE_AMT"];
		}
}
$avg = array_sum($average) / count($average);

$fp = fopen('data2.txt', 'w');
fwrite($fp, json_encode($data));
fclose($fp);

for($i = 0; $i < count($data); $i++){
	$data[$i][1] = $avg;
}

$fp = fopen('data3.txt', 'w');
fwrite($fp, json_encode($data));
fclose($fp);

?>