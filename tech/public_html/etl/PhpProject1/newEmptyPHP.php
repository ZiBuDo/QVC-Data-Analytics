        $qry_category_all = "SELECT DATE_ORDER,PRODUCT_CATEGORY, TOTAL_SALES FROM tbldate_category
        WHERE PRODUCT_CATEGORY = 'Entertainment'";
        $statement4= $db -> prepare($qry_category_sales);
        $statement4 -> execute();
        $dates_sales_all = $statement4 -> fetchAll();
        $statement4 -> closeCursor();
      //var_dump($dates_sales_all);
        
        foreach ($dates_sales_all AS $day_sale_A){
        $reg_sales_date = $day_sale_A['DATE_ORDER'];
        $UTC_Sales_date = strtotime($day_sale_A['DATE_ORDER']) * 1000;
        $daily_sales = $day_sale_A['TOTAL_SALES'];
       $point = array($UTC_Sales_date,$daily_sales);
       $daily_sales_array[] = $point;
       
       
               $qry_category_sales = "SELECT DATE_ORDER,PRODUCT_CATEGORY,SUM(TOTAL_SALES) as TOTAL_SALES 
            FROM tbldate_category GROUP BY PRODUCT_CATEGORY WHERE PRODUCT_CATEGORY = '$camp_category'";
        $statement1= $db -> prepare($qry_category_sales);
        $statement1 -> execute();
        $dates_sales = $statement1 -> fetchAll();
        $statement1 -> closeCursor();
      //var_dump($dates_sales);
        
        foreach ($dates_sales AS $day_sale){
        $avg_category_sales = $day_sale['TOTAL_SALES']/$days;
        
        $campaign_list -> avg = $avg_category_sales;
        }
        


        $campaign_list -> data = $daily_sales_array;
        $campaign_array[] = $campaign_list;
    
    
      echo json_encode($campaign_array);