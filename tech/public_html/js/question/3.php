<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class pres{
	
}
$data = array();
$servername = "localhost";
$username = "tech";
$password = "tech";
$dbname = "tech";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$host1 = array();
$sql = "SELECT DISTINCT(HOST1) AS HOST1 FROM product_airtime ORDER BY HOST1 ASC";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
	$host1[] = $row["HOST1"];
}
array_shift($host1); //delete null value
$host1 = array_splice($host1,16);


foreach($host1 as $host){
	$sql = "SELECT PRODUCT_NBR, unix_timestamp(ONAIR_DATE) AS ONAIR_DATE, ONAIR_MINS, HOST1, HOST2 FROM product_airtime WHERE HOST1 = '$host' OR HOST2 = '$host' ORDER BY ONAIR_DATE ASC, PRODUCT_NBR ASC";
	$result = mysqli_query($conn, $sql);
	$presenter = new pres();
	$presenter->name = $host;
	$prods = array();
	//initiliaze
	$start_prod = "P0";
	$time = 0;
	$start_date = 0;
	$change = false;
	$product_sales_air = 0;
	$prod_total = 0;
	while($row = mysqli_fetch_assoc($result)) {
		if($start_date == 0){
			$start_date = $row["ONAIR_DATE"];
		}else{
			$curr_date = $row["ONAIR_DATE"];
			if($start_date != $curr_date){ //time change, we have nec. air time
				$change = true;
			}
		}
		if($start_prod == "P0"){
			$start_prod = $row["PRODUCT_NBR"];
		}else{
			$curr = $row["PRODUCT_NBR"];
			if($curr != $start_prod){ //we have amounted the necessary air time
				$change = true;
			}
		}
		if($change){ //calculate product sales and product sales average
			$start_date_string = gmdate("Y-m-d", $start_date);
			$sql = "SELECT PRODUCT_NBR, unix_timestamp(ORDER_DATE) AS ORDER_DATE, TOTAL_LINE_AMT FROM order_master WHERE PRODUCT_NBR = '$start_prod' AND ORDER_DATE = '$start_date_string'";
			$order_result = mysqli_query($conn, $sql);
			while($rowo = mysqli_fetch_assoc($order_result)) {
				$product_sales_air = $product_sales_air + $rowo["TOTAL_LINE_AMT"];
			}
			$sql = "SELECT PRODUCT_NBR, TOTAL_SALES FROM product_sales WHERE PRODUCT_NBR = '$start_prod'";
			$order_result = mysqli_query($conn, $sql);
			while($rowo = mysqli_fetch_assoc($order_result)) {
				$prod_total = $prod_total + $rowo["TOTAL_SALES"];
			}
			$prod_avg = $prod_total / 90;
			$y = $product_sales_air - $prod_avg;
			$prods[] = array($time,$y); //add it to array
			//reinitialize
			$start_date = $curr_date;
			$start_prod = $curr;
			$prod_total = 0;
			$product_sales_air = 0;
			$change = false;
			$time = 0;
		}
		$time = $time + $row["ONAIR_MINS"];
		echo $host . " " . $start_prod . " " . $start_date . PHP_EOL;
	}
	foreach ($prods as $key => $row) {
		// replace 0 with the field's index/key
		$dates[$key]  = $row[0];
	}

	array_multisort($dates, SORT_ASC, $prods);
	$presenter->data = $prods;
	$data[] = $presenter;
	
}

$fp = fopen('host.txt', 'w');
fwrite($fp, json_encode($data));
fclose($fp);
?>