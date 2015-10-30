<?php

$array = json_decode();



$fp = fopen('data33.txt', 'w');
fwrite($fp, json_encode($reg));
fclose($fp);


?>