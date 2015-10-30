<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('database_connect.php');

class object{}

$days = 90;

$qry_avg_cat_day = "SELECT DATE(DATE_ORDER) as DATE_ORDER,PRODUCT_CATEGORY,SUM(TOTAL_SALES) as TOTAL_SALES FROM tbldate_category GROUP BY PRODUCT_CATEGORY";

$statement1= $db -> prepare($qry_avg_cat_day);
$statement1 -> execute();
$dates_sales = $statement1 -> fetchAll();
$statement1 -> closeCursor();

//var_dump($campaigns);
foreach ($dates_sales AS $day_sale){
    $reg_sales_date = $day_sale['DATE_ORDER'];
    $UTC_Sales_date = strtotime($day_sale['DATE_ORDER']) * 1000;
    $avg_catSales_perday = $day_sale['TOTAL_SALES']/$days;
    $product_category = $day_sale['PRODUCT_CATEGORY'];

    
        $qry_camp_spending = "SELECT CAMPAIGN_DATE,`PRODUCT_CATEGORY`,`CAMPAIGN_SPEND` FROM (
        SELECT str_to_date(`CAMPAIGN_DATE`,'%m/%d/%Y') as CAMPAIGN_DATE,`PRODUCT_CATEGORY`,`CAMPAIGN_SPEND` FROM email_campaign) as tmp1
        WHERE `PRODUCT_CATEGORY`= '$product_category' AND CAMPAIGN_DATE = '$reg_sales_date'";
        $statement2= $db -> prepare($qry_camp_spending);
        $statement2 -> execute();
        $campaigns = $statement2 -> fetchAll();
        $statement2 -> closeCursor();
        
        foreach ($campaigns AS $campaign){
        $camp_spending = $campaign['CAMPAIGN_SPEND'];
        $reg_camp_date= $campaign['CAMPAIGN_DATE'];
        $UTC_camp_date = strtotime($campaign['CAMPAIGN_DATE']) * 1000;
        $camp_category = $campaign['PRODUCT_CATEGORY'];
        
        $x =  $camp_spending;
        $y = $camp_spending - $avg_catSales_perday;
        
        
        echo "<p> SALES DATE : ".$reg_sales_date. "</p>";
        echo "<p> CAMPAIGN DATE: ".$reg_camp_date. "</p>";        
        echo "<p>".$camp_category. "</p>";
        echo "[".$x.",".$y."],";


        
        }

}
    
    
    





