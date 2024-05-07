<?php 

$inputvalues = json_decode(file_get_contents(('php://input'), true),true);

include("includes/dbParameters.php");
include("includes/commonVariables.php");
include("includes/commonFunctions.php");

writetolog("Request >> HTTP_GB_API_TOKEN: " . $_SERVER["HTTP_GB_API_TOKEN"] . " | " . json_encode($inputvalues), $api_log);

if ($_SERVER["HTTP_GB_API_TOKEN"] == $api_token_global) {
	setResponseCode("0014");//"0014" => "Autherization failed",
	sendResponse($response_object);
	exit;
}

$response_object["operation"] = $inputvalues["operation"];

switch ($inputvalues["operation"]) {
	case 'signup':
	{
		$name = $inputvalues["name"];
		$email = $inputvalues["email"];
		$password = $inputvalues["password"];

		$user_query = mysqli_query($db,"SELECT * FROM $customer_table WHERE email = '$email'");
		if ($user_query_array = mysqli_fetch_assoc($user_query)) {
			
			if ($user_query_array["email"] == "$email") {
				setResponseCode("0002"); //"0002" => "Email already registered",
				sendResponse($response_object);
				break;
			}
		}else{

			$user_token = get_token($time);

			$userdata_enter_sql = "INSERT INTO $customer_table (name,email,password) VALUES ('$name','$email','$password')";

			if (!mysqli_query($db,$userdata_enter_sql)) {
				
				echo mysqli_error($db);
				writetolog(mysqli_error($db), $error_log);
				setResponseCode("0015"); //"0002" => "Email already registered",
				break;
			}else{
				$user_query = mysqli_query($db,"SELECT * FROM $customer_table WHERE email = '$email'");
				if ($user_query_array = mysqli_fetch_assoc($user_query)) {
					
					$response_object["user_token"] = $user_token;
					$response_object["userData"]["email"] = array(
						"email" => $user_query_array["email"]);
					$response_object["userData"]["name"] = array(
						"name" => $user_query_array["name"]);

					setResponseCode("0001"); //"0001" => "User Registered Successfully",
					sendResponse($response_object);
					break;


				}
				setResponseCode("0015"); //"0015" => "System error occurred",
				break;
			}

		}
		break;
	}
	
	default: {
		// code...
		break;
	}
		
}


?>