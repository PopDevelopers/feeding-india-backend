<?php
require '../include/connect.php';
header('Content-Type:Application/json');



if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['user_type']) && isset($_POST['action']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    if
    (
        (!empty($_POST['user_type']) && (($_POST['user_type'] == 'volunteer') || ($_POST['user_type'] == 'donor'))) &&
        (!empty($_POST['action']) && (($_POST['action'] == 'register') || ($_POST['action'] == 'login')))
    )
    {
        $user_type = $_POST['user_type'];
        $action = $_POST['action'];

        if($user_type == 'donor')
        {

            if($action == 'login')
            {
                $query = "SELECT `donor_id`,`password`,`name`,`phone_no_1`,`donor_type`,`photo_url` FROM `donors` WHERE `email` = '$email';";
                $result = mysqli_query($conn, $query);

                if($result == false)
                {
                    $response = array('response_code'=>0,'message'=>'Cannot query database');
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);

                    if($num_rows == 0) 
                    {
                        $response = array('response_code'=>0,'message'=>'No such user exists');                
                    }
                    else if($num_rows == 1)
                    {
                        $row = mysqli_fetch_assoc($result);

                        $password_check_result = password_verify($password,$row['password']);

                        if($password_check_result == true)
                        {
                            $response = array('response_code'=>1,'message'=>'Welcome back','id'=>$row['donor_id'],'name'=>$row['name'],'password_hash'=>$row['password'],'phone_no_1'=>$row['phone_no_1'],'donor_type'=>$row['donor_type'],'photo_url'=>$row['photo_url']);
                        }
                        else
                        {
                            $response = array('response_code'=>0,'message'=>'Entered password is incorrect');
                        } // Password checking if ends

                    }
                    else
                    {
                        $response = array('response_code'=>0,'message'=>'Database integrity error. Records with same email ids exist');
                    } // No of rows if ends
                } // Select query result success if ends
            } // Login action if ends
            else if($action == 'register')
            {
                $query = "SELECT `donor_id` FROM `donors` WHERE `email` = '$email';";
                $result = mysqli_query($conn, $query);

                if($result == false)
                {
                    $response = array('response_code'=>0,'message'=>'Cannot query database');
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);
                    
                    if($num_rows != 0) 
                    {
                        $response = array('response_code'=>0,'message'=>'User with same email id already exists');                
                    }
                    else
                    {
                        $hashed_password = password_hash($password,PASSWORD_DEFAULT);

                        $query = "INSERT INTO `donors`(`email`,`password`) VALUES ('$email','$hashed_password');";
                        $result = mysqli_query($conn,$query);

                        if($result == false)
                        {
                            $response = array('response_code'=>0,'message'=>'Cannot query database');
                        }
                        else
                        {
                            $query = "SELECT `donor_id` FROM `donors` WHERE `email` = '$email';";
                            $result = mysqli_query($conn, $query);
                            
                            if($result != false)
                            {
                                $row = mysqli_fetch_assoc($result);
                                $response = array('response_code'=>1,'message'=>'Success','id'=>$row['donor_id']);
                            }

                        } // Insert query result if ends

                    } //No of rows if ends
                } // Select query result if ends
            } // Register action if ends
            else
            {
                $response = array('response_code'=>0,'message'=>'Incorrect request');
            } // Action if ends

        } // Donor if ends
        else if($user_type == 'volunteer')
        {

            if($action == 'login')
            {
                $query = "SELECT `volunteer_id`,`password`,`name`,`phone_no_1`,`photo_url` FROM `volunteers` WHERE `email` = '$email';";
                $result = mysqli_query($conn, $query);

                if($result == false)
                {
                    $response = array('response_code'=>0,'message'=>'Cannot query database');
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);

                    if($num_rows == 0) 
                    {
                        $response = array('response_code'=>0,'message'=>'No such user exists');                
                    }
                    else if($num_rows == 1)
                    {
                        $row = mysqli_fetch_assoc($result);
                        $password_check_result = password_verify($password,$row['password']);
                        
                        if($password_check_result == true)
                        {
                            $response = array('response_code'=>1,'message'=>'Welcome back','id'=>$row['volunteer_id'],'name'=>$row['name'],'password_hash'=>$row['password'],'phone_no_1'=>$row['phone_no_1'],'photo_url'=>$row['photo_url']);
                        }
                        else
                        {
                            $response = array('response_code'=>0,'message'=>'Entered password is incorrect');
                        }
                    }
                    else
                    {
                        $response = array('response_code'=>0,'message'=>'Database integrity error. Records with same email ids exist');
                    } // No of rows if ends
                } // Select query result if ends
            } // Login if ends
            else if($action == 'register')
            {
                $query = "SELECT `volunteer_id` FROM `volunteers` WHERE `email` = '$email';";
                $result = mysqli_query($conn, $query);

                if($result == false)
                {
                    $response = array('response_code'=>0,'message'=>'Cannot query database');
                }
                else
                {
                    $num_rows = mysqli_num_rows($result);

                    if($num_rows != 0) 
                    {
                        $response = array('response_code'=>0,'message'=>'User with same email id already exists');                
                    }
                    else
                    {
                        $hashed_password = password_hash($password,PASSWORD_DEFAULT);
                        
                        $query = "INSERT INTO `volunteers`(`email`,`password`) VALUES ('$email','$hashed_password');";
                        $result = mysqli_query($conn,$query);

                        if($result == false)
                        {
                            $response = array('response_code'=>0,'message'=>'Cannot query database');
                        }
                        else
                        {
                           
                            $query = "SELECT `volunteer_id` FROM `volunteers` WHERE `email` = '$email';";
                            $result = mysqli_query($conn, $query);
                            
                            if($result != false)
                            {
                                $row = mysqli_fetch_assoc($result);
                                $response = array('response_code'=>1,'message'=>'Success','id'=>$row['volunteer_id']);
                            }

                        } // Insert query result if ends
                    } // No of rows if ends
                } // Select query result if ends
            } // Register if ends
            else
            {
                $response = array('response_code'=>0,'message'=>'Incorrect request');
            } // Action if ends

        } // Volunteer if ends
        else
        {
            $response = array('response_code'=>0,'message'=>'Incorrect request');
        } // Donor type if ends



    }
    else
    {
        $response = array('response_code'=>0,'message'=>'Incorrect request');
    }// user_type and action if ends

}
else
{
    $response = array('response_code'=>0,'message'=>'Incorrect request');
} // All post varibales set if ends


echo json_encode($response);
mysqli_close($conn);  
?>