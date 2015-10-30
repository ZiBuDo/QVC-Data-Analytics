<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('database_connect.php');

//Queries for Question 1
class products {
    }
class product_list{
    
}
$mydata= array();
$data = array();
$total_sales =  340891894;
$qryUS_total = "SELECT PRODUCT_CATEGORY, SUM(order_master.TOTAL_LINE_AMT) as 'TOTAL'
FROM product_master,order_master
WHERE product_master.PRODUCT_NBR = order_master.PRODUCT_NBR
GROUP BY PRODUCT_CATEGORY
ORDER BY SUM(order_master.TOTAL_LINE_AMT) DESC
LIMIT 10";

$statement1 = $db->prepare($qryUS_total);
$statement1 -> execute();
$categories = $statement1->fetchAll();
$statement1 -> closeCursor();
//$product_list ="<table border=1><th>Product Number</th><th>Brand name</th><th>Product Description</th><Th>Category</th><th>TOTAL</th>";
Foreach ($categories as $category){
    $category_loop = $category['PRODUCT_CATEGORY'];
    $category_total= $category['TOTAL'];
    $qryUS_product = "SELECT product_master.PRODUCT_NBR,BRAND_NAME,PRODUCT_DESCRIPTION, PRODUCT_CATEGORY,SUM(order_master.TOTAL_LINE_AMT) as 'TOTAL' 
    FROM product_master,order_master
    WHERE product_master.PRODUCT_NBR = order_master.PRODUCT_NBR
    AND PRODUCT_CATEGORY= '$category_loop'
    GROUP BY PRODUCT_NBR
    ORDER BY sum(order_master.TOTAL_LINE_AMT) DESC
    LIMIT 10";
$statement = $db->prepare($qryUS_product);
$statement -> execute();
$products = $statement->fetchAll();
$statement->closeCursor();

foreach ($products as $product1){

    $product_list = new product_list;
    $product_list -> name = $product1['PRODUCT_NBR'];
    $product_list -> y = floatval(number_format(($product1['TOTAL']/$category_total),5) * 100);
    $product_list -> info = $product1['PRODUCT_DESCRIPTION'] ."Total Sales : " . "$".number_format(($product1['TOTAL']),0). "</b>";
    $data[] = $product_list;
}
    
//foreach ($products as $product){

    $category_product = new products;
    $category_product -> name = $category_loop;
    $category_product -> id = $category_loop;
    $category_product -> info = "Detailed information";
    $category_product -> data = $data;
    $data = array();
   

//}
 $mydata[] = $category_product;

/*echo "<p>".$category['PRODUCT_CATEGORY']."</p>";
Foreach ($products as $Product){
    $product_list .=  "<tr><td>".$Product['PRODUCT_NBR'] .  "</td><td>" . $Product['BRAND_NAME']."</td><td>" . $Product['PRODUCT_DESCRIPTION']."</td><td>" . $Product['PRODUCT_CATEGORY']."</td><td>" . number_format($Product['TOTAL'],2) ."</td></tr>";
    echo $product_list."</table>";
}*/


            
}
echo json_encode($mydata);

?>