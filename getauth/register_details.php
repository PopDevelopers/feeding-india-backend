<?php
require '../include/connect.php';
header('Content-Type:Application/json');

if
(
    isset($_POST['user_type']) && 
    ( ($_POST['user_type'] == 'donor') || ($_POST['user_type'] = 'volunteer') ) &&
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['name']) &&
    isset($_POST['phone_no_1'])
)
{
    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $phone_no_1 = $_POST['phone_no_1'];

    if(!empty($email) && !empty($password) && !empty($name) && !empty($phone_no_1))
    {
        $phone_no_2 = $_POST['phone_no_2'];
        
        if(isset($_POST['profile_pic_url']))
        {
            $profile_pic_url = $_POST['profile_pic_url'];
        }
        else
        {
            $profile_pic_url = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Placeholder_no_text.svg/1024px-Placeholder_no_text.svg.png";
        }
        
        if($user_type == 'donor')
        {
            if
            (
                isset($_POST['donor_type']) &&
                !empty($_POST['donor_type']) &&
                ( ($_POST['donor_type'] == 'individual') || ($_POST['donor_type'] == 'non_individual') )
            )
            {
                $donor_type = $_POST['donor_type'];

                $query = "SELECT `donor_id`,`password`,`name`,`phone_no_1` FROM `donors` WHERE `email` = '$email';";
                $result = mysqli_query($conn, $query);

                if($result == false)
                {
                    $response = array('response_code'=>0,'message'=>'Cannot query database');
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);
                    
                    if($num_rows != 1) 
                    {
                        $response = array('response_code'=>0,'message'=>'User with email doesn\'t exist or more than 1 users exists');                
                    }
                    else
                    {
                        $row = mysqli_fetch_assoc($result);
                        $password_check_result = password_verify($password,$row['password']);
                        $donor_id = $row['donor_id'];

                        if($password_check_result == true)
                        {
                            if($donor_type == 'individual')
                            {
                                $query = "UPDATE `donors` SET `donor_type`=0, `name`='$name', `phone_no_1`='$phone_no_1', `phone_no_2`='$phone_no_2', `photo_url`='$profile_pic_url' WHERE `donor_id` = '$donor_id';";
                            }
                            else if($donor_type == 'non_individual')
                            {
                                $query = "UPDATE `donors` SET `donor_type`=1, `name`='$name', `phone_no_1`='$phone_no_1', `phone_no_2`='$phone_no_2', `photo_url`='$profile_pic_url' WHERE `donor_id` = '$donor_id';";
                            } // Donor type if ends
            
                            $result = mysqli_query($conn, $query);
                        
                            if($result == false)
                            {
                                $response = array('response_code'=>0,'message'=>'Cannot query database');
                            }
                            else
                            {
                                $response = array('response_code'=>1,'message'=>'Registered Successfully','id'=>$donor_id,'name'=>$name,'password_hash'=>$row['password'],'phone_no_1'=>$row['phone_no_1']);
                            }// Update query result if ends
                        }
                        else
                        {
                            $response = array('response_code'=>0,'message'=>'Entered password is incorrect');
                        } // Password checking if ends
                    } // No of rows if ends
                } // Select query if ends
            }
            else
            {
                $response = array('response_code'=>0,'message'=>'Incorrect request');            
            } // Donor type is correct if ends
        } // Donor if ends

        else if($user_type == 'volunteer')
        {
            if(isset($_POST['city']) && !empty($_POST['city']))
            {
                $city = $_POST['city'];

                $query = "SELECT `volunteer_id`,`password`,`name`,`phone_no_1` FROM `volunteers` WHERE `email` = '$email';";
                $result = mysqli_query($conn, $query);

                if($result == false)
                {
                    $response = array('response_code'=>0,'message'=>'Cannot query database');
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);
                    
                    if($num_rows != 1) 
                    {
                        $response = array('response_code'=>0,'message'=>'User with email doesn\'t exist or more than 1 users exists');                
                    }
                    else
                    {
                        $row = mysqli_fetch_assoc($result);
                        $password_check_result = password_verify($password,$row['password']);
                        $volunteer_id = $row['volunteer_id'];

                        if($password_check_result == true)
                        {
                            
                            $query = "UPDATE `volunteers` SET `name`='$name', `phone_no_1`='$phone_no_1', `phone_no_2`='$phone_no_2', `photo_url`='$profile_pic_url', `city`='$city' WHERE `volunteer_id` = $volunteer_id;";

                            $result = mysqli_query($conn, $query);
                        
                            if($result == false)
                            {
                                $response = array('response_code'=>0,'message'=>'Cannot query database');
                            }
                            else
                            {
                                $response = array('response_code'=>1,'message'=>'Registered Successfully','id'=>$volunteer_id,'name'=>$name,'password_hash'=>$row['password'],'phone_no_1'=>$row['phone_no_1']);
                            }// Update query result if ends
                        }
                        else
                        {
                            $response = array('response_code'=>0,'message'=>'Entered password is incorrect');
                        } // Password checking if ends
                    } // No of rows if ends
                } // Select query if ends

            }
            else
            {
                $response = array('response_code'=>0,'message'=>'Incorrect request');            
            } // City is correct if ends
        } // Volunteer if ends
    }
    else
    {
        $response = array('response_code'=>0,'message'=>'Incorrect request');
    } // Email, password, name, phone no 1 not empty if ends
}
else
{
    $response = array('response_code'=>0,'message'=>'Incorrect request');
} // All post varibles set if ends


echo json_encode($response);
mysqli_close($conn);  
?>