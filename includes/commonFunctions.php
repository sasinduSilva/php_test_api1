<?php 
date_default_timezone_set('Asia/Colombo');
$time = time();

$db = mysqli_connect($db_host, $db_username ,$db_password, $db_name);
mysqli_set_charset($db,"utf8");



function setResponseCode($RespCode, $additional_note = null)
{
	global $response_object;
	global $ResponseCodeDesc;
	global $msisdn;
	global $mode;
	global $display_msisdn;
	global $package_id;
	global $service_name;		

	$response_object["ResponseCode"] = $RespCode;
	$response_object["ResponseDesc"] = str_replace(array("<mobile>","<package_id>","<service>"),array($display_msisdn,$package_id,$service_name), $ResponseCodeDesc[$response_object["ResponseCode"]]);
	
	if(isset($additional_note))
	{
		$response_object["ResponseDesc"] .= "(".$additional_note.")";
	}
}

function get_token($input)
{
	return base64_encode((string) rand(1000000,999999) * $input);
}

function validate_user_token($token)
{
	global $db, $gb_users_table;
	$user_token_query = mysqli_query($db,"SELECT * FROM $gb_users_table WHERE user_token = '$token'");  
	if($user_token_query_array = mysqli_fetch_assoc($user_token_query))
	{
		return $user_token_query_array["id"];
	}else{
		return false;
	}
}

function sendResponse($response_object)
{	
	global $api_log;
	
	writetolog("Response << ".json_encode($response_object), $api_log);
	echo json_encode($response_object);
	exit;
}

function writetolog($content, $logfile)
{
	$logfile = "logs/$logfile";
	$fp = fopen($logfile, 'a');
	fwrite($fp, date("g:i A dmy")."|".$content."\n");
	fclose($fp);
}



?> 