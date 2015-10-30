<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('database_connect.php');

class state {}
class category {}
class county{}
class product{}
class product_data{}
class fip_category{}
class fip_product_data{}
$states_array = array();
$categories_array = array();
$counties_array = array();
$products_array = array();
$products_data = array();
$fip_category_array= array();
$county_array = array();
$fip_products_array = array();
$fip_products_data = array();

//GET TABLES FOR ENCODING

$qrystate_total = "SELECT STATE,STATE_TOTAL_SALES FROM state_sales";
$statement1 = $db -> prepare($qrystate_total);
$statement1 -> execute();
$states = $statement1 -> fetchAll();
$statement1 -> closeCursor();


foreach ($states AS $state){
    $state_total_sales = $state['STATE_TOTAL_SALES'];
    $state_loop = $state['STATE'];
    $qrycategories = "SELECT `STATE`,PRODUCT_CATEGORY,STATE_CATEGORY_TOTAL FROM state_category WHERE `STATE` = '$state_loop'"
            . "ORDER BY STATE_CATEGORY_TOTAL DESC LIMIT 10";
    $statement2 = $db -> prepare($qrycategories);
    $statement2 -> execute();
    $categories = $statement2 -> fetchAll();
    $statement2 -> closeCursor();
    //var_dump($categories);
    // category information
    foreach ($categories AS $category){
        $category_total_sales = $category['STATE_CATEGORY_TOTAL'];
        $category_loop = $category['PRODUCT_CATEGORY'];
        $category_list = new category();
        $category_list -> name = $category_loop;
        $category_list -> y = floatval(number_format(($category_total_sales/$state_total_sales),5) * 100);
        $category_list -> drilldown = $category_loop;
        $categories_array[] = $category_list;
      
        $product_list = new product();
        $product_list -> name =  $category_loop;
        $product_list -> id =  $category_loop;
        $product_list -> info = "Detailed Information";
        

        $qryproducts = "SELECT product_total,STATE,PRODUCT_CATEGORY,PRODUCT_NBR,PRODUCT_DESCRIPTION FROM state_category_product WHERE STATE ='$state_loop'"
            . "AND PRODUCT_CATEGORY ='$category_loop' "
                . "ORDER BY product_total DESC LIMIT 10";
        $statement3 = $db -> prepare($qryproducts);
        $statement3 -> execute();
        $products = $statement3 -> fetchAll();
        $statement3 ->closeCursor();
    // PRODUCT INFORMATION
        foreach ($products as $product){
        $product_total_sales = $product['product_total'];
        $product_loop = $product['PRODUCT_NBR'];
        $info = $product['PRODUCT_DESCRIPTION']. '   '. 'TOTAL SALES : $' . number_format(($product_total_sales),0); 
        $product_data = new product_data();
        $product_data -> name = $product_loop;
        $product_data -> y = floatval(number_format(($product_total_sales/$category_total_sales),5)* 100);
        $product_data -> info = $info;
        $products_data[] = $product_data;  
    }
     $product_list -> data = $products_data;
     $products_array[] = $product_list;
     $products_data = array();
    }

    //for county informaiton
    $qrystate_county = "SELECT FIPS,`STATE`,COUNTY,COUNTY_TOTAL_SALES FROM state_county WHERE `STATE` = '$state_loop'";
        
        $statement4 = $db -> prepare($qrystate_county);
        $statement4 -> execute();
        $counties = $statement4 -> fetchAll();
        $statement4 -> closeCursor();
        
        
    Foreach ($counties AS $county){
        $fips_loop = $county['FIPS'];
        $countyname_loop =$county['COUNTY'];
        $countysales_loop =$county['COUNTY_TOTAL_SALES'];
    
        $qrycounty_categories = "SELECT FIPS,PRODUCT_CATEGORY,FIP_CATEGORY_TOTAL FROM state_fip_category WHERE FIPS = '$fips_loop' ORDER BY FIP_CATEGORY_TOTAL DESC LIMIT 10";
                $statement5 = $db -> prepare($qrycounty_categories);
                $statement5 -> execute();
                $county_categories = $statement5 -> fetchAll();
                $statement5 -> closeCursor();
  
        
        
        foreach($county_categories AS $county_category){
            $fip_category_loop = $county_category['PRODUCT_CATEGORY'];
            $fip_catagory_total_sales = $county_category['FIP_CATEGORY_TOTAL'];
            //echo $fip_catagory_total_sales;
            $fip_category_list = new fip_category();
            $fip_category_list -> name = $fip_category_loop;
            $fip_category_list -> y = floatval(number_format(($fip_catagory_total_sales/$countysales_loop),5) * 100);
            $fip_category_list -> drilldown = $fip_category_loop;
            $fip_category_array[] = $fip_category_list;
   
                    $fip_product_list = new product();
                    $fip_product_list -> name = $fip_category_loop;
                    $fip_product_list -> id =  $fip_category_loop;
                    $fip_product_list -> info = "Detailed Information";
                    

                    $qryfip_products = "SELECT FIPS,PRODUCT_CATEGORY,PRODUCT_NBR,FIP_PRODUCT_TOTAL,PRODUCT_DESCRIPTION FROM state_fip_product WHERE FIPS = '$fips_loop' "
                            . "AND PRODUCT_CATEGORY = '$fip_category_loop' "
                            . "ORDER BY FIP_PRODUCT_TOTAL DESC LIMIT 10"; 
                    $statement6 = $db -> prepare($qryfip_products);
                    $statement6 -> execute();
                    $fip_products = $statement6 -> fetchAll();
                    $statement6 ->closeCursor();
                    //var_dump($fip_products);
                // PRODUCT INFORMATION
                    foreach ($fip_products as $fip_product){
                    $product_total_sales = $fip_product['FIP_PRODUCT_TOTAL'];
                    $fip_product_loop = $fip_product['PRODUCT_NBR'];
                    $info = $fip_product['PRODUCT_DESCRIPTION'].'      TOTAL SALES : $' . number_format(($product_total_sales),0); 
                    $fip_product_data = new fip_product_data();
                    $fip_product_data -> name = $fip_product_loop;
                    $fip_product_data -> y = floatval(number_format(($product_total_sales/($fip_catagory_total_sales + 1)),5)*100);
                    $fip_product_data -> info = $info;
                    $fip_products_data[] = $fip_product_data;  
                    
                }
                     $fip_product_list -> data = $fip_products_data;
                     $fip_products_array[] = $fip_product_list;
                     $fip_products_data = array();
                     
    }
        
        
    $county_list = new county();
    $county_list ->name = $countyname_loop;
    $county_list ->value = floatval($countysales_loop);
    $county_list ->fips = intval($fips_loop);
    $county_list -> categories = $fip_category_array;
    $county_list -> products =   $fip_products_array;
    $county_array[] = $county_list;
    $fip_category_array = array();
    $fip_products_array = array();

    
}

    $state_list = new state();
    $state_list -> hc_key = 'us-'.strtolower($state_loop);
    $state_list -> value = floatval($state_total_sales);
    $state_list -> categories = $categories_array;
    $state_list -> products = $products_array;
    $state_list -> county = $county_array;
    $categories_array = array();
    $products_array = array();
    $county_array = array();
    $states_array[] = $state_list;
    
}

echo json_encode($states_array);




?>