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

//Queries for Question 1
$total_sales = 340891894;
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
class categories {
    }
foreach ($categories as $category){

    $US_total = new categories;
    $US_total -> name = $category['PRODUCT_CATEGORY'];
    $US_total -> y = number_format(($category['TOTAL'] / $total_sales),5)* 100;
    $US_total -> drilldown = $category['PRODUCT_CATEGORY'];
    echo json_encode($US_total);
}

$qryUS_total_other = "SELECT SUM(TOTAL) AS 'OTHER_TOTAL' FROM
(
SELECT PRODUCT_CATEGORY, SUM(order_master.TOTAL_LINE_AMT) as 'TOTAL'
FROM product_master,order_master
WHERE product_master.PRODUCT_NBR = order_master.PRODUCT_NBR
GROUP BY PRODUCT_CATEGORY
ORDER BY SUM(order_master.TOTAL_LINE_AMT) ASC
LIMIT 9) AS tmp1";
$statement = $db->prepare($qryUS_total_other);
$statement -> execute();
$categories = $statement->fetchAll();
$statement->closeCursor();
class categories_other {
    }
foreach ($categories as $category){

    $US_total_other = new categories_other;
    $US_total_other -> name = 'Other';
    $US_total_other -> y = floatval(number_format(($category['OTHER_TOTAL'] / $total_sales),5)*100);
    $US_total_other -> drilldown = null;
    $mydata = array($US_total_other);
    echo json_encode($mydata);
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