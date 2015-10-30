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
$sql = "SELECT unix_timestamp(str_to_date(INTERACTION_DATE,'%m/%d/%Y %H:%i:%s')), SENTIMENT FROM social ORDER BY INTERACTION_DATE ASC";
$result = mysqli_query($conn, $sql);
$sent = array();
$sentscr = 0;
$start = 0;
$curr = 0;
$holder = array();
while($row = mysqli_fetch_assoc($result)) {
	if($start == 0){
		$start = $row["unix_timestamp(str_to_date(INTERACTION_DATE,'%m/%d/%Y %H:%i:%s'))"];
		$start = $start * 1000;
	}else{
		$curr = $row["unix_timestamp(str_to_date(INTERACTION_DATE,'%m/%d/%Y %H:%i:%s'))"];
		$curr = $curr * 1000;
		$diff = abs($curr - $start);
		if($diff >= 43200000){
			$sent[] = array($start, $sentscr);
			$sentscr = 0;
			$start = $curr;
		}
	}
	if($row['SENTIMENT'] == "Strongly pos"){
		$sentscr = $sentscr + 3;
	}elseif($row['SENTIMENT'] == "Positive"){
		$sentscr = $sentscr + 1;
	}elseif($row['SENTIMENT'] == "Neutral"){
		$sentscr = $sentscr + 0;
	}elseif($row['SENTIMENT'] == "Negative"){
		$sentscr = $sentscr - 1;
	}elseif($row['SENTIMENT'] == "Strongly neg"){
		$sentscr = $sentscr - 3;
	}
	
}
$fp = fopen('data.txt', 'w');
fwrite($fp, json_encode($sent));
fclose($fp);
