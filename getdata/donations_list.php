<?php
    require '../include/connect.php';

    // Variables
    $limit = 10;
    
    /**
     * We return records where donation_id < $donation_id (as we're displaying records in reverse order)
     * and limiting no of records returned by $limit, and are less than 24 hours old
     */
    if(isset($_GET['donation_id']))
    {
        $donation_id = $_GET['donation_id'];
        
        if($donation_id < 1)
        {
            $donation_id = PHP_INT_MAX;
        }
    }
    else
    {
        $donation_id = PHP_INT_MAX;
    }
    
    
    
    if(isset($_GET['picked']))
    {
        if(!strcmp($_GET['picked'],'yes'))
        {
            $is_picked = true;
        }
        else
        {
            $is_picked = false;
        }
    }
    else
    {
        $is_picked = false;
    }
    
    
    if($is_picked)
    {
        $query = "SELECT `donation_id`,`name`,`photo_url`,TIME_FORMAT(TIME(request_datetime), '%h:%i %p') AS `request_datetime`,TIME_FORMAT(TIME(pickup_datetime), '%h:%i %p') AS `pickup_datetime`,`pickup_area`,`is_perishable`,`is_veg`,`is_picked`,`is_accepted`,`other_details` FROM `donations`, `donors` WHERE ((donations.`donor_id` = donors.`donor_id`) AND (`is_picked` = 1) AND (`donation_id` < $donation_id) AND (TIMESTAMPDIFF(HOUR,`request_datetime`,NOW()) < 24)) ORDER BY `donation_id` DESC LIMIT $limit;"; 
    }
    else
    {
        $query = "SELECT `donation_id`,`name`,`photo_url`,TIME_FORMAT(TIME(request_datetime), '%h:%i %p') AS `request_datetime`,TIME_FORMAT(TIME(pickup_datetime), '%h:%i %p') AS `pickup_datetime`,`pickup_area`,`is_perishable`,`is_veg`,`is_picked`,`is_accepted`,`other_details` FROM `donations`, `donors` WHERE ((donations.`donor_id` = donors.`donor_id`) AND (`is_picked` = 0) AND (`donation_id` < $donation_id) AND (TIMESTAMPDIFF(HOUR,`request_datetime`,NOW()) < 24)) ORDER BY `donation_id` DESC LIMIT $limit;";
    }
    
    $result = mysqli_query($conn, $query);
    
    if($result == false)
    {
        die("Query cannot be run");
    }
    else
    {
        $array = array();
        
        while ($row = mysqli_fetch_assoc($result))
        {
            array_push($array,$row);
        }

        header('Content-Type:Application/json');
        echo json_encode($array);
    }

    mysqli_close($conn);   
?>