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


// user route
Router::post("/userProfileRegistration", "controller", "userProfileRegistration");
Router::post("/userPasswordReset", "postController", "userPasswordReset");
Router::post("/userPasswordResetSecond", "postController", "userPasswordResetSecond");
Router::post("/userPasswordResetThird", "postController", "userPasswordResetThird");


// super admin route
Router::post("/superAdminLogin", "postController", "superAdminLogin");



// logout route
Router::get('/logout', "postController", "logout");







// not found 
header("HTTP/1.0 404 Not Found");
$response = array(
    'status' => 'success',
    'message' => 'this route is not found on this server'
);

echo json_encode($response);
exit();