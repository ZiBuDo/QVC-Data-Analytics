<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ('database_connect.php');


$days = 90;
class campaign {}
class data{}
class flag{}
class data2{}
class all{};
$campaign_array = array();
$daily_sales_array = array();
$mydata = array();
$cat_data = array();
$all_sales_array = array();
$something_array = array();
$mydata2 = array();


//ALL CATEGORIES SALES PER DAY
$qry_all_cat = "SELECT DATE(DATE_ORDER) as DATE_ORDER,SUM(TOTAL_SALES) as SALES 
FROM tbldate_category
GROUP BY DATE(DATE_ORDER)";
$statement3= $db -> prepare($qry_all_cat);
$statement3 -> execute();
$all_Cat_Sales = $statement3 -> fetchAll();
$statement3 -> closeCursor();



//SALES CAMPAIGN SPENDING PER CATEGORY PER DAY
$qry_camp_spending = "SELECT CAMPAIGN_DATE,distinct(PRODUCT_CATEGORY) as PRODUCT_CATEGORY,`CAMPAIGN_SPEND` FROM (
SELECT str_to_date(`CAMPAIGN_DATE`,'%m/%d/%Y') as CAMPAIGN_DATE,`PRODUCT_CATEGORY`,
`CAMPAIGN_SPEND` FROM email_campaign) as tmp1";

$qry_get_categories = "SELECT DISTINCT(PRODUCT_CATEGORY) FROM email_campaign";
$statement2= $db -> prepare($qry_get_categories);
$statement2 -> execute();
$campaigns = $statement2 -> fetchAll();
$statement2 -> closeCursor();
//var_dump($campaigns);
foreach ($campaigns as $campaign){
    //$camp_spending = $campaign['CAMPAIGN_SPEND'];
    $camp_category = $campaign['PRODUCT_CATEGORY'];
   // $camp_date = $campaign['CAMPAIGN_DATE'];
    //$mil_camp_date = strtotime($camp_date) * 1000;
        If($camp_category == 'All'){
            $all_avg = 3796179.856;
            
                $all_list = new all();
                $all_list -> name = $camp_category;
                $all_list -> avg = floatval($all_avg);
                $all_list -> id = $camp_category;
                $qry_all_sales = "SELECT DATE_ORDER,SALES FROM tblSalesPerDay";
                $statement6= $db -> prepare($qry_all_sales);
                $statement6 -> execute();
                $all_sales = $statement6 -> fetchAll();
                $statement6 -> closeCursor();
                //var_dump($all_sales);
                Foreach ($all_sales as $sale){
                    $sale_date_mil = strtotime($sale['DATE_ORDER']) * 1000;
                    $sale_amount = floatval($sale['SALES']);
                    $sales_point = array($sale_date_mil,$sale_amount);
                    $all_sales_array[] = $sales_point;
                }


                $all_list -> data = $all_sales_array;
                $something_array[] = $all_list;
                $all_sales_array = array();
                $mydata2[] = $something_array;

                $cat_events = new flag();
                $cat_events -> showInLegend = false;
                $cat_events -> type = 'flags';
                $cat_events -> shape = 'circlepin';
                $cat_events -> width = 16;
                $cat_events -> onSeries = $camp_category;


                $qrycamp_list = "SELECT CAMPAIGN_DATE,PRODUCT_CATEGORY,CAMPAIGN_SPEND FROM email_campaign WHERE
                PRODUCT_CATEGORY = '$camp_category'";     
                  $statement5= $db -> prepare($qrycamp_list);
                  $statement5 -> execute();
                  $camp_lists = $statement5 -> fetchAll();
                  $statement5 -> closeCursor();
                   $i =1;
                foreach ($camp_lists as $camp_list){

                        $spent = '$'.number_format(($camp_list['CAMPAIGN_SPEND']),2);
                        $mil_camp_date = strtotime($camp_list['CAMPAIGN_DATE']) * 1000;
                      $camp_specific = new data2();
                      $camp_specific -> x = $mil_camp_date;
                      $camp_specific-> title = $i++;
                      $camp_specific -> text = "Campaign spending: $spent";
                      $cat_data[] = $camp_specific;
                      }
                      $cat_events -> data = $cat_data;
                      $mydata2[] = $cat_events;
                      $cat_data = array();

                      continue;
            }

        $campaign_list = new campaign();
        $campaign_list -> name = $camp_category;
        
        $qry_category_sales = "SELECT DATE_ORDER, PRODUCT_CATEGORY, sum(TOTAL_SALES) FROM tbldate_category WHERE PRODUCT_CATEGORY = '$camp_category'";
        $statement1= $db -> prepare($qry_category_sales);
        $statement1 -> execute();
        $dates_sales = $statement1 -> fetchAll();
        $statement1 -> closeCursor();
        //var_dump($dates_sales);
        
        foreach ($dates_sales AS $day_sale){
        $avg_category_sales = $day_sale['sum(TOTAL_SALES)']/$days;
            $campaign_list -> avg = $avg_category_sales;
        }
        
        $qry_category_all = "SELECT DATE_ORDER, PRODUCT_CATEGORY, TOTAL_SALES FROM tbldate_category WHERE PRODUCT_CATEGORY = '$camp_category'";
        $statement2= $db -> prepare($qry_category_all);
        $statement2 -> execute();
        $dates_sales_all = $statement2 -> fetchAll();
        $statement2 -> closeCursor();
        //var_dump($dates_sales_all);
         foreach ($dates_sales_all AS $day_sale_A){
        $reg_sales_date = $day_sale_A['DATE_ORDER'];
        $UTC_Sales_date = strtotime($day_sale_A['DATE_ORDER']) * 1000;
        $daily_sales = floatval($day_sale_A['TOTAL_SALES']);
       $point = array($UTC_Sales_date,$daily_sales);
       $daily_sales_array[] = $point;
         }
        
        $campaign_list -> data = $daily_sales_array;
        $campaign_list -> id = $camp_category;
        $mydata[] = $campaign_list; 
        $daily_sales_array = array();
        
        
            $cat_events = new flag();
            $cat_events -> showInLegend = false;
            $cat_events -> type = 'flags';
            $cat_events -> shape = 'circlepin';
            $cat_events -> width = 16;
            $cat_events -> onSeries = $camp_category;
            
      
      $qrycamp_list = "SELECT CAMPAIGN_DATE,PRODUCT_CATEGORY,CAMPAIGN_SPEND FROM email_campaign WHERE
              PRODUCT_CATEGORY = '$camp_category'";     
                $statement5= $db -> prepare($qrycamp_list);
                $statement5 -> execute();
                $camp_lists = $statement5 -> fetchAll();
                $statement5 -> closeCursor();
                 $i =1;
            foreach ($camp_lists as $camp_list){
               
                $spent = '$'.number_format(($camp_list['CAMPAIGN_SPEND']),2);
                $mil_camp_date = strtotime($camp_list['CAMPAIGN_DATE']) * 1000;
            $camp_specific = new data2();
            $camp_specific -> x = $mil_camp_date;
            $camp_specific-> title = $i++;
            $camp_specific -> text = "Campaign spending: $spent";
            $cat_data[] = $camp_specific;
            }
            $cat_events -> data = $cat_data;
            $mydata[] = $cat_events;
            $cat_data = array();
       
}


echo json_encode($mydata) ."<HR>";
echo json_encode($mydata2);






