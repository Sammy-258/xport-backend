<?php

    require_once('app/controller.php');
    require_once("app/model.php");
    
    class postController extends controller
    {
        

        public function userPasswordReset($file, $data){
            $userPasswordReset = new Model($this->pdo);
            $userPasswordReset = $userPasswordReset->userPasswordReset($data);

            
            if($userPasswordReset["status"]=="success"){
                session_start();
                $_SESSION["reset_password"] = $userPasswordReset["reset_code"];

                if(isset($_SESSION["reset_password"])){
                    echo json_encode($userPasswordReset);
                }

            }else{
                echo json_encode($userPasswordReset);
            }
        }

        public function userPasswordResetSecond($file, $data){
            session_start();
            if(empty($data["reset_code"])){
                header("HTTP/1.0 400 Bad Request");
                $response = array(
                    'status' => 'failed',
                    'message' => 'reset_code feild is required.'
                );
                echo json_encode($response);
            }else{
                $reset_code = $data["reset_code"];
                if($reset_code !==$_SESSION["reset_password"]){
                    $response = array(
                        'status' => 'failed',
                        'message' => 'reset_code you entered does not match with the one given.'
                    );
                    echo json_encode($response);
                }else{
                    $_SESSION["reset_status"] = 1;
                    $response = array(
                        'status' => 'success',
                        'message' => 'reset_code you entered matchs with the one given.'
                    );
                    echo json_encode($response);
                }
                echo json_encode();
            }
        }

        public function userPasswordResetThird($file, $data){
            session_start();
            if(empty($data["password"])){
                header("HTTP/1.0 400 Bad Request");
                $response = array(
                    'status' => 'failed',
                    'message' => 'password feild is required.'
                );
                echo json_encode($response);
            }else{
                $password = $data["password"];
                if(empty($_SESSION["reset_status"])){
                    $response = array(
                        'status' => 'failed',
                        'message' => 'your Dont have a reset status.'
                    );
                    echo json_encode($response);
                }else{
                    
                    $response = array(
                        'status' => 'success',
                        'message' => 'You have successfully reset your password..'
                    );
                    echo json_encode($response);
                }
                echo json_encode();
            }
        }

        public function adminResetPassword($file, $data){
            $adminResetPassword = new model($this->pdo);
            $adminResetPassword = $adminResetPassword->adminResetPassword($data);

            // echo json_encode($adminResetPassword["status"]);

            if($adminResetPassword["status"]=="success"){
                session_start();
                $_SESSION["reset_password"] = $adminResetPassword["reset_code"];
                $_SESSION["admin_email"] = $data["company_email"];

                if(isset($_SESSION["reset_password"])){
                    echo json_encode($adminResetPassword);
                }

            }else{
                echo json_encode($adminResetPassword);
            }
        }

        public function adminResetPasswordSecond($file, $data){
            session_start();
            if(empty($data["reset_code"])){
                header("HTTP/1.0 400 Bad Request");
                $response = array(
                    'status' => 'failed',
                    'message' => 'reset_code feild is required.'
                );
                echo json_encode($response);
            }else{
                $reset_code = $data["reset_code"];
                if($reset_code !==$_SESSION["reset_password"]){
                    $response = array(
                        'status' => 'failed',
                        'message' => 'reset_code you entered does not match with the one given.'
                    );
                    echo json_encode($response);
                }else{
                    $_SESSION["reset_status"] = 1;
                    $response = array(
                        'status' => 'success',
                        'message' => 'reset_code you entered matchs with the one given.'
                    );
                    echo json_encode($response);
                }
                
            }
        }

        public function adminResetPasswordThird($file, $data){
            session_start();
            if(empty($data["password"])){
                header("HTTP/1.0 400 Bad Request");
                $response = array(
                    'status' => 'failed',
                    'message' => 'password feild is required.'
                );
                echo json_encode($response);
            }else{
                $password = $data["password"];
                if(empty($_SESSION["reset_status"])){
                    $response = array(
                        'status' => 'failed',
                        'message' => 'your Dont have a reset status.'
                    );
                    echo json_encode($response);
                }else{
                    $admin_email = $_SESSION["admin_email"];
                    $adminResetPasswordThird = new model($this->pdo);
                    $adminResetPasswordThird = $adminResetPasswordThird->adminResetPasswordThird($data, $admin_email);


                    
                    echo json_encode($adminResetPasswordThird);

                    // var_dump($adminResetPasswordThird);
                }
                
            }
        }
        
        public function coverageArea($file, $data){
            session_start();

            if(isset($_SESSION["admin_data"])){
                $company_user_name = $_SESSION["admin_data"]["company_user_name"];
                $coverageArea = new Model($this->pdo);
                $coverageArea = $coverageArea->coverageArea($data, $company_user_name);

                echo  json_encode($coverageArea);
            }else{
                $response = array(
                    'status' => 'failed',
                    'message' => 'you are not an authenticated admin to access this route'
                );

                echo json_encode($response);
            }
        }

        public function adminPricing($file, $data){
            session_start();

            if(isset($_SESSION["admin_data"])){
                $company_user_name = $_SESSION["admin_data"]["company_user_name"];
                $adminPricing = new Model($this->pdo);
                $adminPricing = $adminPricing->adminPricing($data, $company_user_name);

                echo json_encode($adminPricing);
            }else{
                $response = array(
                    'status' => 'failed',
                    'message' => 'you are not an authenticated admin to access this route'
                );

                echo json_encode($response);
            }
        }

        public function adminAccount($file, $data){
            session_start();

            if(isset($_SESSION["admin_data"])){
                $company_user_name = $_SESSION["admin_data"]["company_user_name"];
                $adminAccount = new Model($this->pdo);
                $adminAccount = $adminAccount->adminAccount($data, $company_user_name);

                echo json_encode($adminAccount);
            }else{
                $response = array(
                    'status' => 'failed',
                    'message' => 'you are not an authenticated admin to access this route'
                );

                echo json_encode($response);
            }
        }

        public function adminWithdraw($file, $data){
            session_start();

            if(isset($_SESSION["admin_data"])){
                $company_user_name = $_SESSION["admin_data"];
                $adminWithdraw = new Model($this->pdo);
                $adminWithdraw = $adminWithdraw->adminWithdraw($data, $company_user_name);

                echo json_encode($adminWithdraw);
            }else{
                $response = array(
                    'status' => 'failed',
                    'message' => 'you are not an authenticated admin to access this route'
                );

                echo json_encode($response);
            }
        }

    }
    