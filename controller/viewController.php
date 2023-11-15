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
  }