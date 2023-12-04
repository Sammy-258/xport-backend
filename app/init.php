<?php

require_once("Router.php");

// admin route
Router::post("/adminRegister", "controller", "adminRegister");
Router::post("/adminLogin", "controller", "adminLogin");
Router::post("/adminResetPassword", "postController", "adminResetPassword");
Router::post("/adminResetPasswordSecond", "postController", "adminResetPasswordSecond");
Router::post("/adminResetPasswordThird", "postController", "adminResetPasswordThird");
Router::post("/coverageArea", "postController", "coverageArea");
Router::post("/adminPricing", "postController", "adminPricing");
Router::post("/adminAccount", "postController", "adminAccount");
Router::post("/adminWithdraw", "postController", "adminWithdraw");



// admin protected get
Router::get("/adminTransaction", "viewController", "adminTransaction");


// user route
Router::post("/userRegistration", "controller", "userRegistration");
Router::post("/userLogin", "controller", "userLogin");
Router::post("/userProfileRegistration", "controller", "userProfileRegistration");
Router::post("/userPasswordReset", "postController", "userPasswordReset");
Router::post("/userPasswordResetSecond", "postController", "userPasswordResetSecond");
Router::post("/userPasswordResetThird", "postController", "userPasswordResetThird");
Router::post("/calculatorOne", "postController", "calculatorOne");
Router::post("/mainCalculator", "postController", "mainCalculator");



Router::post("/checkout", "postController", "checkout");


// uer_get
Router::get("/calculatorTwo", "viewController", "calculatorTwo");
Router::get("/payOnDelivery", "viewController", "payOnDelivery");


// super admin route
Router::post("/superAdminLogin", "postController", "superAdminLogin");



// logout route
Router::get('/logout', "viewController", "logout");







// not found 
header("HTTP/1.0 404 Not Found");
$response = array(
    'status' => 'failed',
    'message' => 'this route is not found on this server'
);

echo json_encode($response);
exit();