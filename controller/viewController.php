<?php

  require_once('app/controller.php');
  require_once("app/model.php");

  class viewController extends controller
  {
    public function logout($file, $data){
      session_start();
      session_destroy();

      $response = array(
          'status' => 'success',
          'message' => 'You have successfully logout.'
      );

      echo json_encode($response);
    }

    public function adminTransaction(){
      session_start();

      if(isset($_SESSION["admin_data"])){
        $company_data = $_SESSION["admin_data"];
        $adminTransaction = new Model($this->pdo);
        $adminTransaction = $adminTransaction->adminTransaction($company_data);

        echo json_encode($adminTransaction);
      }else{
        header('HTTP/1.0 5O0 Internal Server Error');
        $response = array(
          'status' => 'failed',
          'message' => 'you are not an authenticated admin to access this route'
        );

        echo json_encode($response);
      }
    }

    public function calculatorTwo($file, $id) {
      session_start();

      if(isset($_SESSION['totalResult'])) {
          $totalResult = $_SESSION['totalResult'];

          $selectedResult = array_values(array_filter($totalResult, function($result) use ($id) {
              return isset($result['id']) && $result['id'] == $id;
          }));

          if(empty($selectedResult)) {
            header('HTTP/1.0 500 Internal Server Error');
            $response = array(
              'status' => 'failed',
              'message' => 'details was not found'
            );

            echo json_encode($response);
          }else {
            // Save the specific result in the session
            $_SESSION['selectedResult'] = $selectedResult[0];
            $id = $_SESSION['selectedResult']['id'];
            $good = $_SESSION['selectedResult']['good'];
            $price = $_SESSION['selectedResult']['result'];
            $currency = $_SESSION['selectedResult']['currency'];

            // echo json_encode($_SESSION['selectedResult']);

            // Unset or delete the totalResult from the session
            unset($_SESSION['totalResult']);

            $calculatorTwo = new Model($this->pdo);
            $calculatorTwo = $calculatorTwo->calculatorTwo($id);

            

            $company_user_name = $calculatorTwo["company_user_name"];
            $company_name = $calculatorTwo["company_name"];
            $company_email = $calculatorTwo["company_email"];

            $user_data = $_SESSION["user_data"];

            $email = $user_data["email"];

            $timestamp = time();

            // Generate a random 3-digit number
            $randomNumber = mt_rand(100, 999);

            // Generate a random 3 characters
            $randomCharacters = chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90));

            // Combine all components to create a unique tracking ID
            $uniqueInfo = $email . $timestamp . $good . $price . $randomNumber . $randomCharacters;
            $tracking_id = md5($uniqueInfo);


            $result = array(
              'email' => $email,
              'good' => $good,
              'tracking_id' => $tracking_id,
              'price' => $price,
              'currency' => $currency,
              'company_name' => $company_name,
              'company_user_name' => $company_user_name,
              'company_email' => $company_email
            );

            $_SESSION["checkout_data"] = $result;

            echo json_encode($result);
          }
      }else{
        header('HTTP/1.0 404 Not Found');
        $response = array(
            'status' => 'failed',
            'message' => 'routing not found/expired.'
        );

        echo json_encode($response);
      }
    }
    
    public function payOnDelivery(){
      session_start();

      if(isset($_SESSION["checkout_data"])){
          
        $checkout_data = $_SESSION["checkout_data"];
            
        $payOnDelivery = new Model($this->pdo);
        $payOnDelivery = $payOnDelivery->payOnDelivery($checkout_data);

        
        echo json_encode($payOnDelivery);

        unset($_SESSION['checkout_data']);

        
          
      }else{
        header("HTTP/1.0 404 Not Found");
        $response = array(
            'status' => 'failed',
            'message' => 'kindly return back to the user page.'
        );

        echo json_encode($response);
      }
    }
    
    public function adminOrder(){
      session_start();

      if(isset($_SESSION["admin_data"])){
        $company_data = $_SESSION["admin_data"];
        $adminOrder = new Model($this->pdo);
        $adminOrder = $adminOrder->adminOrder($company_data);

        echo json_encode($adminOrder);
      }else{
        header('HTTP/1.0 5O0 Internal Server Error');
        $response = array(
          'status' => 'failed',
          'message' => 'you are not an authenticated admin to access this route'
        );

        echo json_encode($response);
      }
    }

    public function userOrder(){
      session_start();

      if(isset($_SESSION["user_data"])){
        $user_data = $_SESSION["user_data"];
        $userOrder = new model($this->pdo);
        $userOrder = $userOrder->userOrder($user_data);

    
        if($userOrder){
          echo json_encode($userOrder);
        }else{
            echo json_encode($userOrder);
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

    public function trackingSystem($file, $id) {
      session_start();

      if(isset($_SESSION["user_data"])){
        $trackingSystem = new model($this->pdo);
        $trackingSystem = $trackingSystem->trackingSystem_get($id);

    
        if($trackingSystem){
          echo json_encode($trackingSystem);
        }else{
            echo json_encode($trackingSystem);
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
  }