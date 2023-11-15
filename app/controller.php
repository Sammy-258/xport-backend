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

        public function userProfileRegistration($file, $data){
            $userProfileRegistration = new model($this->pdo);
            $userProfileRegistration = $userProfileRegistration->userProfileRegistration($data);

           
            if($userProfileRegistration){
                session_start();
                $_SESSION["user_data"] = $userProfileRegistration;
                echo json_encode($_SESSION["user_data"]);
            }else{
                echo json_encode($userProfileRegistration);
            }
        }

        public function adminLogin($file, $data){
            $adminLogin = new model($this->pdo);
            $adminLogin = $adminLogin->adminLogin($data);

            if($adminLogin){
                session_start();
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