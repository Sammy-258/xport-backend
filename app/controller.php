<?php
    require_once('config.php');
    require_once("app/model.php");

    class controller{
        protected $pdo;

        public function __construct(){
            $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        }

        public function adminRegister($path, $data, $file){
            $adminRegister = new model($this->pdo);
            $adminRegister = $adminRegister->adminRegister($data, $file);

            // echo json_encode($adminRegister);
            if($adminRegister){
                session_start();
                $_SESSION["admin_data"] = $adminRegister;
                echo json_encode($_SESSION["admin_data"]);
            }else{
                echo json_encode($adminRegister);
            }
        }

        public function userRegistration($file, $data){
            
            $userRegistration = new Model($this->pdo);
            $userRegistration = $userRegistration->userRegistration($data);

        
            if($userRegistration){
                session_start();
                $_SESSION["user_data"] = $userRegistration;
                echo json_encode($_SESSION["user_data"]);
            }else{
                echo json_encode($userRegistration);
            }
            
        }

        public function userLogin($file, $data){
            $userLogin = new model($this->pdo);
            $userLogin = $userLogin->userLogin($data);

           
            if(empty($userLogin["status"])){
                session_start();
                $_SESSION["user_data"] = $userLogin;
                echo json_encode($_SESSION["user_data"]);
            }else{
                echo json_encode($userLogin);
            }
        }

        public function userProfileRegistration($file, $data, $image){

            session_start();

            if(isset($_SESSION["user_data"])){
                $userProfileRegistration = new model($this->pdo);
                $userProfileRegistration = $userProfileRegistration->userProfileRegistration($data, $image);

            
                if($userProfileRegistration){
                    
                    $_SESSION["user_profile"] = $userProfileRegistration;
                    echo json_encode($_SESSION["user_profile"]);
                }else{
                    echo json_encode($userProfileRegistration);
                }
            }else{
                header("HTTP/1.0 404 Not Found");
                $response = array(
                    'status' => 'failed',
                    'message' => 'kindly register or login.'
                );

                echo json_encode($response);
            }
        }

        public function adminLogin($file, $data){
            $adminLogin = new model($this->pdo);
            $adminLogin = $adminLogin->adminLogin($data);

            if($adminLogin){
                session_start();
                // $company_user_name = $adminLogin["company_user_name"];
                // $company_email = $adminLogin["company_email"];
                // $admindata = new model($this->pdo);
                // $admindata = $admindata->admindata($company_user_name, $company_email);
                
                $_SESSION["admin_data"] = $adminLogin;
                echo json_encode($_SESSION["admin_data"]);
            }else{
                echo json_encode($adminLogin);
            }
        }

        public function superAdminLogin($file, $data){
            if(empty($data["email"]) || empty($data["password"])){
                header("HTTP/1.0 400 Bad Request");
                $response = array(
                    'status' => 'failed',
                    'message' => 'Company_Email feild is required.'
                );
                
                // return $response;
                echo json_encode($response);
            }else{
                $email = $data["email"];
                $password = $data["password"];

                if($email === 'admin@gmail.com' && $password== '123456789'){
                    $response = array(
                        'status' => 'success',
                        'message' => 'You are now logged in as an admin',
                        'superAdmin'=> $email
                    );
                    session_start();
                    $_SESSION["super_admin_data"] = $response;
                    // return $response;
                    echo json_encode( $_SESSION["super_admin_data"]);
                }else{
                    header("HTTP/1.0 409 Conflict");
                    $response = array(
                        'status' => 'failed',
                        'message' => 'admin details does not exist.'
                    );
                    // return $response;
                    echo json_encode($response);
                }
            }
        }
    }