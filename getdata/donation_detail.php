<?php
    require '../include/connect.php';


    if(isset($_GET['donation_id']))
    {
        if(!empty($_GET['donation_id']))
        {
            $donation_id = $_GET['donation_id'];
        }
        else
        {
            $donation_id = 1;    
        }
    }
    else
    {
        $donation_id = 1;
    }

    if(isset($_POST['volunteer_id']) && isset($_POST['volunteer_pass']))
    {
        if(!empty($_POST['volunteer_id']) && !empty($_POST['volunteer_pass']))
        {
            $volunteer = true;
            $volunteer_id = $_POST['volunteer_id'];
            $volunteer_pass = $_POST['volunteer_pass'];
        }
        else
        {
            $volunteer = false;
            $volunteer_id = 0;
            $volunteer_pass = "";
        }
    }
    else
    {
        $volunteer = false;
        $volunteer_id = 0;
        $volunteer_pass = "";
    }
    
    
    $query = "SELECT `donation_id` FROM `donations`,`donors`,`volunteers` WHERE ((donations.`donor_id` = donors.`donor_id`) AND (donations.`volunteer_id` = volunteers.`volunteer_id`) AND (`donation_id` = $donation_id));";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 0)
    {
        $query = "SELECT `donation_id`, DATE_FORMAT(request_datetime, '%a, %e %b %Y') AS `request_date`, TIME_FORMAT(TIME(request_datetime), '%h:%i %p') AS `request_time`, DATE_FORMAT(pickup_datetime, '%a, %e %b %Y') AS `pickup_date`, TIME_FORMAT(TIME(pickup_datetime), '%h:%i %p') AS `pickup_time`,donations.`donor_id`,`pickup_photo_url`,`delivery_photo_url`,`pickup_city`,`pickup_area`,`pickup_street`,`pickup_house_no`,`is_veg`,`is_perishable`,`is_accepted`,`is_picked`,`is_completed`,`has_pickup_gps`,`pickup_gps_latitude`,`pickup_gps_longitude`,`other_details`,`donor_type`,donors.`name` AS `donor_name`,donors.`photo_url` AS `donor_photo_url` FROM `donations`,`donors` WHERE ((donations.`donor_id` = donors.`donor_id`) AND (`donation_id` = $donation_id));";

    }
    else
    {
        $query = "SELECT `donation_id`, DATE_FORMAT(request_datetime, '%a, %e %b %Y') AS `request_date`, TIME_FORMAT(TIME(request_datetime), '%h:%i %p') AS `request_time`, DATE_FORMAT(pickup_datetime, '%a, %e %b %Y') AS `pickup_date`, TIME_FORMAT(TIME(pickup_datetime), '%h:%i %p') AS `pickup_time`,donations.`donor_id`,donations.`volunteer_id`,`pickup_photo_url`,`delivery_photo_url`,`pickup_city`,`pickup_area`,`pickup_street`,`pickup_house_no`,`is_veg`,`is_perishable`,`is_accepted`,`is_picked`,`is_completed`,`has_pickup_gps`,`pickup_gps_latitude`,`pickup_gps_longitude`,`other_details`,`donor_type`,donors.`name` AS `donor_name`,donors.`photo_url` AS `donor_photo_url`,volunteers.`name` AS `volunteer_name`,volunteers.`photo_url` AS `volunteer_photo_url` FROM `donations`,`donors`,`volunteers` WHERE ((donations.`donor_id` = donors.`donor_id`) AND (donations.`volunteer_id` = volunteers.`volunteer_id`) AND (`donation_id` = $donation_id));";    
    }
    
    $result = mysqli_query($conn, $query);
    
    if($result == false)
    {
        die("Query cannot be run");
    }
    else
    {
        $array = array();
    
        $row = mysqli_fetch_assoc($result);
        array_push($array,$row);

        header('Content-Type:Application/json');
        echo json_encode($array);
    }

    mysqli_close($conn);   
?>