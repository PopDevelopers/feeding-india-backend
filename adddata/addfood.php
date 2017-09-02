<?php

	$mysql_host = 'localhost';
    $mysql_username = 'id2494217_androidapp';
    $mysql_password = 'androidapp';
	$mysql_db = 'id2494217_app';

// Establish connection
    $conn = @mysqli_connect($mysql_host,$mysql_username,$mysql_password,$mysql_db);

// Check connection
    if(mysqli_connect_errno())
    {
        echo 'Failed to connect to MySQL. Error: '.mysqli_connect_errno();
    }
    date_default_timezone_set('Asia/Kolkata');

$request_datetime = date('Y-m-d H:i:s');


$donor_id = $_POST["donor_id"];
$donor_password = $_POST["password_hash"];
$pickup_photo_url = $_POST["pickup_photo_url"];
$pickup_city = $_POST["pickup_city"];
$pickup_area = $_POST["pickup_area"];
//if($_POST["pickup_street"])
$pickup_street = $_POST["pickup_street"];
$pickup_house_no = $_POST["pickup_house_no"];
$has_pickup_gps =  	$_POST["has_pickup_gps"];
$pickup_gps_latitude = $_POST["pickup_gps_latitude"];
$pickup_gps_longitude = $_POST["pickup_gps_longitude"];
$is_veg = $_POST["is_veg"];
$is_perishable = $_POST["is_perishable"];
$other_details = $_POST["other_details"];
$is_valid_user = 0;
//$response1 =array();
$error_message="";
$query = "SELECT password FROM donors WHERE donor_id = '$donor_id';";
                $result = mysqli_query($conn, $query);
                if($result == false)
                {
                    //array_push($response1,array("code"=>0,'message'=>'Cannot query database'));
                    $error_message = "Cannot query database";
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);

                    if($num_rows == 0) 
                    {
                        $error_message ="No such user exists";
                    }
                    else if($num_rows == 1)
                    {
                        $row = mysqli_fetch_assoc($result);

                        //$password_check_result = password_verify($donor_password,$row['password']);

                        if(!strcmp($donor_password,$row['password']))
                        {
							$is_valid_user = 1;
						}
                        else
                        {
							//$error_message ="Entered password is incorrect";
							$error_message =$row['password'];
                        } 

                    }
				}

if($is_valid_user == 1)
{
$query = "insert into donations(request_datetime,donor_id,pickup_photo_url,pickup_city,pickup_area,pickup_street,pickup_house_no,has_pickup_gps,pickup_gps_latitude,pickup_gps_longitude, is_veg	,is_perishable,other_details) 
values('$request_datetime','$donor_id','$pickup_photo_url','$pickup_city','$pickup_area','$pickup_street','$pickup_house_no','$has_pickup_gps','$pickup_gps_latitude','$pickup_gps_longitude','$is_veg','$is_perishable','$other_details');";
$newresult = mysqli_query($conn,$query);
			
	if(!$newresult)
	{
		$response = array();
		$code="reg_false";
		$message="Some Server Error...Try again";
		array_push($response,array("code"=>$code,"message"=>$message));
		echo json_encode(array("server_response"=>$response));
				
	}

	else{
				
		$response = array();
		$code="reg_true";
		$message="Food listed for donation...Thank you";
		array_push($response,array("code"=>$code,"message"=>$message));
		echo json_encode(array("server_response"=>$response));
				
			}
}

else{
		$response = array();
		$code="reg_false";
		
		array_push($response,array("code"=>$code,"message"=>$error_message));
		echo json_encode(array("server_response"=>$response));
		
}
?>

<?php
mysqli_close($conn);
?>