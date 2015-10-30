<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('database_connect.php');

/*
$query = 'SELECT * FROM categories
                       ORDER BY categoryID';
$statement = $db->prepare($query);
$statement->execute();
$categories = $statement->fetchAll();
$statement->closeCursor();
*/

//Queries
$qryUS_total = "SELECT PRODUCT_CATEGORY, SUM(order_master.TOTAL_LINE_AMT) as 'TOTAL'
FROM product_master,order_master
WHERE product_master.PRODUCT_NBR = order_master.PRODUCT_NBR
GROUP BY PRODUCT_CATEGORY
ORDER BY SUM(order_master.TOTAL_LINE_AMT) DESC
LIMIT 10";
$statement = $db->prepare($qryUS_total);
$statement -> execute();
$categories = $statement->fetchAll();
$statement->closeCursor();
foreach ($categories as $category){
    echo "<p>".$category['PRODUCT_CATEGORY']."     ". $category['TOTAL']. "</p>";
}



/*
class test{
 
}


$test = new test;
$test->name = "hello";
$test->y = 3;
$test->drilldown = "hello";

//echo json_encode($test);

$test2 = array(1,2,3,4);
//echo json_encode($test2);

$test3 = array($test, $test);
echo json_encode($test3);

//save it to a file */

?>