<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$fb = array(0,0,0,0,0); //sneg0,neg1,net2,pos3,spos4
$twit = array(0,0,0,0,0);
$forum = array(0,0,0,0,0);


$servername = "localhost";
$username = "tech";
$password = "tech";
$dbname = "tech";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT SOURCE_TYPE, SENTIMENT FROM social ORDER BY SOURCE_TYPE";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
	if($row['SOURCE_TYPE'] == "FACEBOOK"){
		if($row['SENTIMENT'] == "Strongly pos"){
			$fb[4] = $fb[4] + 1;
		}elseif($row['SENTIMENT'] == "Positive"){
			$fb[3] = $fb[3] + 1;
		}elseif($row['SENTIMENT'] == "Neutral"){
			$fb[2] = $fb[2] + 1;
		}elseif($row['SENTIMENT'] == "Negative"){
			$fb[1] = $fb[1] + 1;
		}elseif($row['SENTIMENT'] == "Strongly neg"){
			$fb[0] = $fb[0] + 1;
		}
	}elseif($row['SOURCE_TYPE'] == "FORUMS"){
		if($row['SENTIMENT'] == "Strongly pos"){
			$forum[4] = $forum[4] + 1;
		}elseif($row['SENTIMENT'] == "Positive"){
			$forum[3] = $forum[3] + 1;
		}elseif($row['SENTIMENT'] == "Neutral"){
			$forum[2] = $forum[2] + 1;
		}elseif($row['SENTIMENT'] == "Negative"){
			$forum[1] = $forum[1] + 1;
		}elseif($row['SENTIMENT'] == "Strongly neg"){
			$forum[0] = $forum[0] + 1;
		}
	}elseif($row['SOURCE_TYPE'] == "TWITTER"){
		if($row['SENTIMENT'] == "Strongly pos"){
			$twit[4] = $twit[4] + 1;
		}elseif($row['SENTIMENT'] == "Positive"){
			$twit[3] = $twit[3] + 1;
		}elseif($row['SENTIMENT'] == "Neutral"){
			$twit[2] = $twit[2] + 1;
		}elseif($row['SENTIMENT'] == "Negative"){
			$twit[1] = $twit[1] + 1;
		}elseif($row['SENTIMENT'] == "Strongly neg"){
			$twit[0] = $twit[0] + 1;
		}
	}
}

mysqli_close($conn);

$fbt = $fb[0] + $fb[1] + $fb[2] + $fb[3] + $fb[4];
$twitt = $twit[0] + $twit[1] + $twit[2] + $twit[3] + $twit[4];
$forumt = $forum[0] + $forum[1] + $forum[2] + $forum[3] + $forum[4];

for($i = 0; $i < 5; $i++){
	$fb[$i] = floatval(number_format(($fb[$i] / $fbt) * 100,5));
}
for($i = 0; $i < 5; $i++){
	$twit[$i] = floatval(number_format(($twit[$i] / $twitt) * 100,5));
}
for($i = 0; $i < 5; $i++){
	$forum[$i] = floatval(number_format(($forum[$i] / $forumt) * 100,5));
}
echo "fb";
var_dump($fb);
echo "twitter";
var_dump($twit);
echo "forum";
var_dump($forum);


?> 
