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

    public function calculatorTwo($file, $id){
      $calculatorTwo = new Model($this->pdo);
      $calculatorTwo = $calculatorTwo->calculatorTwo($id);

      if(isset($calculatorTwo["status"])){
        echo json_encode($calculatorTwo);
      }else{
        session_start();

        $_SESSION["calculator"] = $calculatorTwo;

        echo json_encode($_SESSION["calculator"]);
      }
      
    }

   

}