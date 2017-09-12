<?php
    require '../include/connect.php';

    // Variables
    
    $donor_id = $_POST['donor_id'];
	$query = "SELECT `donation_id`,`name`,`photo_url`,TIME_FORMAT(TIME(request_datetime), '%h:%i %p')
		AS `request_datetime`,TIME_FORMAT(TIME(pickup_datetime), '%h:%i %p') AS `pickup_datetime`,
		`pickup_area`,`is_perishable`,`is_veg`,`is_picked`,`is_accepted`,`other_details` FROM `donations`, 
		`donors` WHERE ((donations.`donor_id` = donors.`donor_id`) AND (donors.donor_id = '$donor_id')) ORDER BY `donation_id` ;";
    
    $result = mysqli_query($conn, $query);
    
    if(!$result)
    {
        //die("Query cannot be run");
		echo("it looks as if there is a problem");
        die(mysqli_error($conn));
    }
    else
    {
        $response = array();
        while ($row = mysqli_fetch_assoc($result))
        {
            array_push($response,$row);
        }
        echo json_encode($response);
    }
    mysqli_close($conn);   
?>