<?php

class model
{
    protected $pdo;
    protected $user_table;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function userRegistration($data){
        
        if(empty($data['email']) || empty($data['first_name']) || empty($data['last_name']) || empty($data['password'])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'all feilds are required.'
            );
            return $response;
        }
        try {
            $email = $data['email'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $password = $data['password'];
           

            $stn = $this->pdo->prepare('SELECT * FROM `user` WHERE `email` = :email');
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);
            
            if($row){
                header("HTTP/1.0 409 Conflict");
                $response = array(
                    'status' => 'failed',
                    'message' => 'email already have registered and updated.'
                );
                return $response;
            }

            $stn = $this->pdo->prepare('INSERT INTO `user`(`first_name`, `last_name`, `email`, `password`) VALUES (:first_name, :last_name, :email, :password)');
           
            $stn->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stn->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            $stn->bindParam(':password', $password, PDO::PARAM_STR);
            
            $stn->execute();

            if($stn){
                $response = array(
                    'status' => 'success',
                    'message' => 'succesfully creared an acccount with us.',
                    'email' => $email
                );
                return $response;
            }else{
                header("HTTP/1.0 500 Internal Server Error");
                $response = array(
                    'status' => 'failed',
                    'message' => 'failed to upload details to the database.'
                );
                return $response;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            // return $e->getMessage();
            return $this->generateErrorResponse($errorMessage);
        }
        
    }

    public function userLogin($data){
        
        if(empty($data['email']) || empty($data['password'])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'all feilds are required.'
            );
            return $response;
        }
        try {
            $email = $data['email'];
            $password = $data['password'];
           

            $stn = $this->pdo->prepare('SELECT * FROM `user` WHERE `email` = :email');
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            // $stn->bindParam(':password', $password, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);
            
            if($row){
                if($row["password"] === "$password"){
                    return $row;
                }else{
                    header("HTTP/1.0 409 Conflict");
                    $response = array(
                        'status' => 'failed',
                        'message' => 'credentials does not match.'
                    );
    
                    return $response; 
                }
                
            }else{
                header("HTTP/1.0 409 Conflict");
                $response = array(
                    'status' => 'failed',
                    'message' => 'Email does not exit kindly register.'
                );

                return $response;
            }

            
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            // return $e->getMessage();
            return $this->generateErrorResponse($errorMessage);
        }
        
    }

    public function userProfileRegistration($data){
        
        if(empty($data['email']) || empty($data['country']) || empty($data['state']) || empty($data['address']) || empty($data['phoneNumber'])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'all feilds are required.'
            );
            return $response;
        }
        try {
            $email = $data['email'];
            $country = $data['country'];
            $state = $data['state'];
            $address = $data['address'];
            $phoneNumber = $data['phoneNumber'];

            $stn = $this->pdo->prepare('SELECT * FROM `userprofile` WHERE `email` = :email');
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);
            
            if($row){
                header("HTTP/1.0 409 Conflict");
                $response = array(
                    'status' => 'failed',
                    'message' => 'email already have registered and updated.'
                );
                return $response;
            }

            $stn = $this->pdo->prepare('INSERT INTO `userprofile`(`email`, `country`, `state`, `address`, `phoneNumber`) VALUES (:email, :country, :state, :address, :phoneNumber)');
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            $stn->bindParam(':country', $country, PDO::PARAM_STR);
            $stn->bindParam(':state', $state, PDO::PARAM_STR);
            $stn->bindParam(':address', $address, PDO::PARAM_STR);
            $stn->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
            $stn->execute();

            if($stn){
                $response = array(
                    'status' => 'success',
                    'message' => 'succesfully uploaded to the database.',
                    'email' => $email
                );
                return $response;
            }else{
                header("HTTP/1.0 500 Internal Server Error");
                $response = array(
                    'status' => 'failed',
                    'message' => 'failed to upload details to the database.'
                );
                return $response;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            // return $e->getMessage();
            return $this->generateErrorResponse($errorMessage);
        }
        
    }

    public function userPasswordReset($data){
        if(empty($data["email"])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'Email feild is required.'
            );
            return $response;
        }
        try {
            $email = $data["email"];
            $stn = $this->pdo->prepare('SELECT * FROM `user` WHERE `email`= :email');
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);

            if($row){
               $reset_code = substr(md5($email), 0, 5);
               $response = array(
                    'status'=>'success',
                    'message'=>'reset code retrival.',
                    'reset_code'=> $reset_code
               );

               return $response;
            }else{
                header("HTTP/1.0 404 Not Found");
                $response = array(
                    'status'=>'failed',
                    'message'=>'email does not exist.'
                );
                return $response;
            }

        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function adminRegister($data, $file){
        
        if(empty($data['company_name']) || empty($data['registration_number']) || empty($data['company_address']) || empty($data['company_email']) || empty($file['cac_certificate']) || empty($file["operational_license"]) || empty($file["proof_of_office_address"]) || empty($data["name"]) || empty($data["email"]) || empty($data["mobile_number"] || empty($file["id_upload"]))){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'all feilds are required.'
            );
            return $response;
        }
        try {
            $company_name = $data['company_name'];
            $registration_number = $data['registration_number'];
            $company_address = $data['company_address'];
            $company_email = $data['company_email'];

            $cac_certificate_name = $file['cac_certificate']['name'];
            $cac_certificate_tmp = $file['cac_certificate']['tmp_name'];

            $operational_license_name = $file['operational_license']['name'];
            $operational_license_tmp = $file['operational_license']['tmp_name'];

            $proof_of_office_address_name = $file['proof_of_office_address']['name'];
            $proof_of_office_address_tmp = $file['proof_of_office_address']['tmp_name'];

            $name = $data['name'];
            $role = !empty($data['role']) ? $data['role'] : '';
            $email = $data['email'];
            $mobile_number = $data['mobile_number'];

            $id_upload_name = $file['id_upload']['name'];
            $id_upload_tmp = $file['id_upload']['tmp_name'];

            $address = !empty($data['address']) ? $data['address'] : '';





            $stn = $this->pdo->prepare('SELECT * FROM `admin` WHERE `company_name` = :company_name AND `registration_number` = :registration_number AND `company_email` = :company_email AND `email`=:email');
            $stn->bindParam(':company_name', $company_name, PDO::PARAM_STR);
            $stn->bindParam(':registration_number', $registration_number, PDO::PARAM_STR);
            $stn->bindParam(':company_email', $company_email, PDO::PARAM_STR);
            $stn->bindParam(':email', $email, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);

            if($row){
                header("HTTP/1.0 409 Conflict");
                $response = array(
                    'status' => 'failed',
                    'message' => 'email already have registered and updated.'
                );
                return $response;
            }

            $companyNameUse = strtolower(str_replace(' ', '', $company_name));
            $companyEmailUse = strtolower($company_email);

            $companyUserName = $companyNameUse . '_' . $companyEmailUse;

            // $companyUserName = makeUniqueUsername($companyUserName);


            function generateRandomPassword($length = 12) {
                // Define a set of characters to use for the password
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
            
                // Create an empty password
                $password = '';
            
                // Generate the password randomly
                for ($i = 0; $i < $length; $i++) {
                    $randomChar = $chars[random_int(0, strlen($chars) - 1)];
                    $password .= $randomChar;
                }
            
                return $password;
            }


            $randomPassword = generateRandomPassword();

            $storagePath = __DIR__ . '/../adminRegisterFiles';

            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            $destination_cac_certificate = $storagePath . '/' . $cac_certificate_name;
            $destination_operational_license = $storagePath . '/' . $operational_license_name;
            $destination_proof_of_office_address = $storagePath . '/' . $proof_of_office_address_name;
            $destination_id_upload = $storagePath . '/' . $id_upload_name;

           

            

            if(move_uploaded_file($cac_certificate_tmp, $destination_cac_certificate) && move_uploaded_file($operational_license_tmp, $destination_operational_license) && move_uploaded_file($proof_of_office_address_tmp, $destination_proof_of_office_address) && move_uploaded_file($id_upload_tmp, $destination_id_upload)){
                $stn = $this->pdo->prepare('INSERT INTO `admin`(`company_name`, `registration_number`, `company_address`, `company_email`, `cac_certificate`, `operational_license`, `proof_of_office_address`, `name`, `role`, `email`, `mobile_number`, `id_upload`, `address`, `company_user_name`, `password`) VALUES (:company_name, :registration_number, :company_address, :company_email, :cac_certificate_name, :operational_license_name, :proof_of_office_address_name, :name, :role, :email, :mobile_number, :id_upload_name, :address, :company_user_name, :password)');
                $stn->bindParam(':company_name', $company_name, PDO::PARAM_STR);
                $stn->bindParam(':registration_number', $registration_number, PDO::PARAM_STR);
                $stn->bindParam(':company_address', $company_address, PDO::PARAM_STR);
                $stn->bindParam(':company_email', $company_email, PDO::PARAM_STR);
                $stn->bindParam(':cac_certificate_name', $cac_certificate_name, PDO::PARAM_STR);
                $stn->bindParam(':operational_license_name', $operational_license_name, PDO::PARAM_STR);
                $stn->bindParam(':proof_of_office_address_name', $proof_of_office_address_name, PDO::PARAM_STR);
                $stn->bindParam(':name', $name, PDO::PARAM_STR);
                $stn->bindParam(':role', $role, PDO::PARAM_STR);
                $stn->bindParam(':email', $email, PDO::PARAM_STR);
                $stn->bindParam(':mobile_number', $mobile_number, PDO::PARAM_STR);
                $stn->bindParam(':id_upload_name', $id_upload_name, PDO::PARAM_STR);
                $stn->bindParam(':address', $address, PDO::PARAM_STR);
                $stn->bindParam(':company_user_name', $companyUserName, PDO::PARAM_STR);
                $stn->bindParam(':password', $randomPassword, PDO::PARAM_STR);


                $stn->execute();


                if($stn){
                    $response = array(
                        'status'=>'success',
                        'message'=>'your details have been successfully uploaded',
                        'admin_username'=>"$companyUserName",
                        'admin_password'=>"$randomPassword"
                    );

                    return $response;
                }else{
                    header("HTTP/1.0 500 Internal Server Error");
                    $response = array(
                        'status' => 'failed',
                        'message' => 'failed to upload details to the database.'
                    );
                    return $response;
                }
            }else{
                header("HTTP/1.0 500 Internal Server Error");
                    $response = array(
                        'status' => 'failed',
                        'message' => 'failed to upload the files to the storage path.'
                    );
                    return $response; 
            }


            

           
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
        
    }

    public function adminLogin($data){
        if(empty($data['company_user_name']) || empty($data['password'])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'all feilds are required.'
            );
            return $response;
        }

        try {
           $company_user_name = $data['company_user_name'];
           $password = $data['password'];

           $stn = $this->pdo->prepare('SELECT * FROM `admin` WHERE `company_user_name` = :company_user_name AND `password` = :password');
           $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
           $stn->bindParam(':password', $password, PDO::PARAM_STR);
            $stn->execute();

            $result = $stn->fetch(PDO::FETCH_ASSOC);

            // return $result;

            if($result){
                return $result;
            }else{
                header("HTTP/1.0 409 Conflict");
                $response = array(
                    'status'=>'failed',
                    'message'=>'company details does not match in the database'
                );

                return $response;
            }

        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }
    
    public function adminResetPassword($data){
        if(empty($data["company_email"])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'Company_Email field is required.'
            );
            return $response;
        }
        try {
            $company_email = $data["company_email"];
            $stn = $this->pdo->prepare('SELECT * FROM `admin` WHERE `company_email` = :company_email');
            $stn->bindParam(':company_email', $company_email, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);
    
            if($row){
               $reset_code = substr(md5($company_email), 0, 5);
               $response = array(
                    'status' => 'success',
                    'message' => 'Reset code retrieval.',
                    'reset_code' => $reset_code
               );
    
               return $response;
            } else {
                header("HTTP/1.0 404 Not Found");
                $response = array(
                    'status' => 'failed',
                    'message' => 'Company email does not exist.'
                );
                return $response;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $errorMessage;
            // return $this->generateErrorResponse($errorMessage);
        }
    }
    
    public function adminResetPasswordThird($data, $admin_email){
        try {
            $password = $data['password'];

           

            $stn = $this->pdo->prepare('UPDATE `admin` SET `password`= :password WHERE `company_email` = :company_email');
            $stn->bindParam(':password', $password, PDO::PARAM_STR);
            $stn->bindParam(':company_email', $admin_email, PDO::PARAM_STR);
            $stn->execute();

            
            if($stn){
                $response = array(
                    'status' => 'success',
                    'message' => 'You have successfully reset your password..'
                );

                return $response;
            }else{
                header("HTTP/1.0 500 Internal Server Error");
                $response = array(
                    'status' => 'failed',
                    'message' => 'failed to reset your password, kindly contact the admin please'
                );

                return $response;
            }

        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function coverageArea($data, $company_user_name){
        if(empty($data["area"]) || empty($data["country"]) || empty($data["state"])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'All feilds are required.'
            );
            return $response;
        }
        try {
            $area = $data["area"];
            $country = $data["country"];
            $state = $data["state"];
            $company_user_name	= $company_user_name;

            $stn = $this->pdo->prepare('SELECT * FROM `coverage` WHERE `company_user_name` = :company_user_name');
            $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
            $stn->execute();
            $row = $stn->fetch(PDO::FETCH_ASSOC);

            if($row){
                $stn = $this->pdo->prepare('UPDATE `coverage` SET `area` = :area, `country` = :country, `state` = :state WHERE `company_user_name` = :company_user_name');
                $stn->bindParam(':area', $area, PDO::PARAM_STR);
                $stn->bindParam(':country', $country, PDO::PARAM_STR);
                $stn->bindParam(':state', $state, PDO::PARAM_STR);
                $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);

                if($stn->execute()){
                    $response = array(
                        'status'=>'success',
                        'message'=>'updated your coverage area successfully'
                    );

                    return $response;
                }else{
                    header("HTTP/1.0 500 Internal Server Error");
                    $response = array(
                        'status' => 'failed',
                        'message' => 'failed to update coverage details to the database.'
                    );
                    return $response;
                }
            }else{
                $stn = $this->pdo->prepare('INSERT INTO `coverage`(`company_user_name`, `area`, `country`, `state`) VALUES (:company_user_name, :area, :country, :state)');
                $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
                $stn->bindParam(':area', $area, PDO::PARAM_STR);
                $stn->bindParam(':state', $state, PDO::PARAM_STR);
                $stn->bindParam(':country', $country, PDO::PARAM_STR);
                
                if($stn->execute()){
                    $response = array(
                        'status'=>'success',
                        'message'=>'successfully updated your coverage information'
                    );

                    return $response;
                }else{
                    header("HTTP/1.0 500 Internal Server Error");
                    $response = array(
                        'status' => 'failed',
                        'message' => 'failed to update your coverage details to the database.'
                    );
                    return $response;
                }
            }

            
            
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function adminPricing($data, $company_user_name){
        if(empty($data["weight_price"]) || empty($data["space_price"]) || empty($data["distance_price"]) || empty($data["currency"]) || empty($data["delivery_status"]) || empty($data["type_of_goods"])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'failed',
                'message' => 'all fields are required.'
            );

            return $response;
        }
        try {
            $weight_price = $data["weight_price"];
            $space_price = $data["space_price"];
            $distance_price = $data["distance_price"];
            $currency = $data["currency"];
            $delivery_status = $data["delivery_status"];
            $type_of_goods = $data["type_of_goods"];

            $allAdminDetails = $this->getAdminDetailsById($company_user_name);

            $company_name = $allAdminDetails["company_name"];
            $company_email = $allAdminDetails["company_email"];


            $stn = $this->pdo->prepare("SELECT * FROM `goods` WHERE `company_user_name` = :company_user_name AND `type_of_goods` = :type_of_goods");
            $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
            $stn->bindParam(':type_of_goods', $type_of_goods, PDO::PARAM_STR);
            $stn->execute();

            $result = $stn->fetch(PDO::FETCH_ASSOC);

            if($result){
                $response = array(
                    'status' => 'failed',
                    'message' => 'this specific data already exist in the database'
                );
                
                return $response;
            }else{
                $stn = $this->pdo->prepare("INSERT INTO `goods` (`company_user_name`, `company_name`, `company_email`, `currency`, `weight_price`, `space_price`, `distance_price`, `delivery_status`, `type_of_goods`) VALUES (:company_user_name, :company_name, :company_email, :currency, :weight_price, :space_price, :distance_price, :delivery_status, :type_of_goods)");

                $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
                $stn->bindParam(':company_name', $company_name, PDO::PARAM_STR);
                $stn->bindParam(':company_email', $company_email, PDO::PARAM_STR);
                $stn->bindParam(':currency', $currency, PDO::PARAM_STR);
                $stn->bindParam(':weight_price', $weight_price, PDO::PARAM_STR);
                $stn->bindParam(':space_price', $space_price, PDO::PARAM_STR);
                $stn->bindParam(':distance_price', $distance_price, PDO::PARAM_STR);
                $stn->bindParam(':delivery_status', $delivery_status, PDO::PARAM_STR);
                $stn->bindParam(':type_of_goods', $type_of_goods, PDO::PARAM_STR);
                $stn->execute();

                if($stn){
                    $response = array(
                        'status' => 'success',
                        'message' => 'successfully added a pricing system.'
                    );

                    return $response;
                }else{
                    header('HTTP/1.0 500 Internal Server Error');
                    $response = array(
                        'status' => 'success',
                        'message' => 'Failed to add a pricing system.'
                    );

                    return $response;
                }
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function adminAccount($data, $company_user_name){
        if(empty($data["bank_name"]) || empty($data["bank_account"])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'success',
                'meassage' => 'all fields are required'
            );

            return $response;
        }
        try {
           $bank_name = $data["bank_name"];
           $bank_account = $data["bank_account"];

           $adminDetails = $this->getAdminDetailsById($company_user_name);

           $company_name = $adminDetails["company_name"];

           $stn = $this->pdo->prepare("SELECT * FROM `adminaccount` WHERE `company_user_name` = :company_user_name");
           $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
           $stn->execute();
           $result = $stn->fetch(PDO::FETCH_ASSOC);
            if($result){
                $stn = $this->pdo->prepare("UPDATE `adminaccount` SET `company_name` = :company_name, `bank_name` = :bank_name, `bank_account`= :bank_account WHERE `company_user_name` = :company_user_name");
                $stn->bindParam(':company_name', $company_name, PDO::PARAM_STR);
                $stn->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
                $stn->bindParam(':bank_account', $bank_account, PDO::PARAM_STR);
                $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);

                $stn->execute();

                if($stn){
                    $response = array(
                        'status' => 'success',
                        'message' => 'you have successfully updated your acount details.'
                    );

                    return $response;
                }else{
                    header('HTTP/1.0 5O0 Internal Server Error');
                    $response = array(
                        'status' => 'failed',
                        'message' => 'failed to update your account details in the database.'
                    );

                    return $response;
                }
            }else{
                $stn = $this->pdo->prepare("INSERT INTO `adminaccount`(`company_user_name`, `company_name`, `bank_name`, `bank_account`) VALUES (:company_user_name, :company_name, :bank_name, :bank_account)");
                $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
                $stn->bindParam(':company_name', $company_name, PDO::PARAM_STR);
                $stn->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
                $stn->bindParam(':bank_account', $bank_account, PDO::PARAM_STR);
                $stn->execute();

                if($stn){
                    $response = array(
                        'status' => 'success',
                        'message' => 'You have successfully entered your bank details.'
                    );

                    return $response;
                }else{
                    header('HTTP/1.0 5O0 Internal Server Error');
                    $response = array(
                        'status' => 'failed',
                        'message' => 'Failed to save your bank details'
                    );

                    return $response;
                }
            }


        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            // return $e->getMessage();
            return $this->generateErrorResponse($errorMessage);
        }
        
    }

    public function adminWithdraw($data, $company_data){
        if(empty($data["bank_name"]) || empty($data["bank_account"]) || empty($data["currency"]) || empty($data["amount"])){
            header("HTTP/1.0 400 Bad Request");
            $response = array(
                'status' => 'success',
                'meassage' => 'all fields are required'
            );

            return $response;
        }
        try {
           $bank_name = $data["bank_name"];
           $bank_account = $data["bank_account"];
           $currency = $data["currency"];
           $amount = $data["amount"];
           $company_user_name = $company_data["company_user_name"];
           $company_name = $company_data["company_name"];
           $company_email = $company_data["company_email"];

           $stn = $this->pdo->prepare("INSERT INTO `pricing`( `company_user_name`, `company_name`, `company_email`, `currency`, `bank_name`, `bank_account`, `amount`) VALUES (:company_user_name, :company_name, :company_email, :currency, :bank_name, :bank_account, :amount)");
           $stn->bindParam(':company_user_name', $company_user_name, PDO::PARAM_STR);
           $stn->bindParam(':company_name', $company_name, PDO::PARAM_STR);
           $stn->bindParam(':company_email', $company_email, PDO::PARAM_STR);
           $stn->bindParam(':currency', $currency, PDO::PARAM_STR);
           $stn->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
           $stn->bindParam(':bank_account', $bank_account, PDO::PARAM_STR);
           $stn->bindParam(':amount', $amount, PDO::PARAM_STR);
           $stn->execute();

            if($stn){
                $response = array(
                    'status' => 'success',
                    'message' => 'uploaded successfully'
                );

                return $response;
            }else{
                header('HTTP/1.0 5O0 Internal Server Error');

                $response = array(
                    'status' => 'failed',
                    'message' => 'failed to upload withdraw request'
                );

                return $response;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function adminTransaction($company_data){
        if(empty($company_data)){
            $response  = array(
                'status' => 'failed',
                'message' => 'unable to connect to the adminTranaction model'
            );
        }
        try {
            $company_user_name = $company_data["company_user_name"];
            $company_email = $company_data["company_email"];

            $stn = $this->pdo->prepare("SELECT * FROM `withdraw` WHERE `company_user_name` = :company_user_name	 AND `company_email` = :company_email");
            $stn->bindparam(':company_user_name', $company_user_name, PDO::PARAM_STR);
            $stn->bindparam(':company_email', $company_email, PDO::PARAM_STR);
            $stn->execute();
            $result = $stn->fetchAll(PDO::FETCH_ASSOC);
            if($result){
                return $result;
            }else{
                header('HTTP/1.0 5O0 Internal Server Error');

                $response = array(
                    'status' => 'failed',
                    'message' => 'failed to fetch details from database'
                );

                return $response;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        } //throw $th;
        
    }

    public function calculatorOne($data){
        if(empty($data["good"])){
            $response = array(
                'status' => 'failed',
                'message' => 'all feild are required'
            );

            return $response;
        }

        try {
            $good = $data["good"];

            $stn = $this->pdo->prepare("SELECT *FROM `goods` WHERE  `type_of_goods` = :type_of_goods");
            $stn->bindParam(':type_of_goods', $good, PDO::PARAM_STR);
            $stn->execute();
            $result = $stn->fetchAll(PDO::FETCH_ASSOC);

            // return $result;
            if($result){
                return $result;
            }else{
                header('HTTP/1.0 5O0 Internal Server Error');
                $response = array(
                    'status' => 'failed',
                    'message' => 'no data found'
                );

                return $response;
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function calculatorTwo($id){
        try {
            $stn = $this->pdo->prepare("SELECT * FROM `goods` WHERE `id` = :id");
            $stn->bindParam(':id', $id, PDO::PARAM_STR);
            $stn->execute();
            $result = $stn->fetch(PDO::FETCH_ASSOC);
            if($result){
                return $result;
            }else{
                $response = array(
                    'status' => 'failed',
                    'message' => 'failed to fetch data with this specific id.'
                );
                return $response;
            }
        }catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
    
            $errorMessage = "A database error occurred. Please contact the administrator.";
    
            return $e->getMessage();
            // return $this->generateErrorResponse($errorMessage);
        }
    }

    public function checkout($good, $email, $price, $currency, $generateTrackingID, $company_user_name, $company_email){

    }
    
    protected function generateErrorResponse($message)
    {
        header("HTTP/1.0 500 Internal Server Error");
        $response = array(
            'status' => 'failed',
            'message' => $message
        );

        return $response;
    }

    private function getAdminDetailsById($company_user_name) {
        $stmt = $this->pdo->prepare("SELECT * FROM `admin` WHERE `company_user_name` = :company_user_name");
        $stmt->bindParam(':company_user_name', $company_user_name, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserDetailsById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `user` WHERE `id` = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}