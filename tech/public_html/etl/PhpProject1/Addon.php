<?php

foreach ($products as $product1){

    $product_list = new product_list;
    $product_list -> name = $product1['PRODUCT_NBR'];
    $product_list -> y = ROUND(($product1['TOTAL']/1000000),2);
    $product_list -> info = $product1['PRODUCT_DESCRIPTION'];
    $product_array = array($product_list);
}
    

foreach ($products as $product){

    $category_product = new products;
    $category_product -> name = $product['PRODUCT_CATEGORY'];
    $category_product -> id = $product['PRODUCT_CATEGORY'];
    $category_product -> info = "Detailed information";
    $category_product -> data = $product_array;
    //$mydata = array($category_product);
    echo json_encode($category_product);
}