<?php
date_default_timezone_set('Asia/Colombo');
$time = time();

//HTTP_GB_API_TOKEN values (gb-api-token)
$api_token_global = "dskghdfkhgjdf54546456546";

//logs
$api_log = "api.log";
$error_log = "error.log";

//tables
$customer_table = "customer";

//other variables
$user_token_lifetime = 30*24*60*60; //30 days

//status codes
$ResponseCodeDesc = array(
    "0001" => "User Registered Successfully",
    "0002" => "Email already registered",
    "0004" => "User logged in successfully­",
    "0005" => "Incorrect email or password",
    "0014" => "Autherization failed",
    
    
    );

?>