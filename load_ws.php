<?php
$student_id = "";
if(isset($_POST["student_id"])){
	$student_id = $_POST["student_id"];
}
$code = "";
if(isset($_POST["code"])){
	$code = $_POST["code"];
}
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$current_path = pathinfo($actual_link)["dirname"];
//code to validate start
$student_nickname = "";
$student_fullname = "";
$valid = false;
$url = $current_path."/"."validator_ws.php";
$data = array('student_id' => $student_id, 'code' => $code);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	'method'  => 'POST',
	'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) {
	$valid = false;
}else{
	try{
		$rj = json_decode($result);
		$student_nickname = $rj->nickname;
		$student_fullname = $rj->fullname;
		$valid = true;
	}catch(Exception $e){
		$valid = false;
	}
}
//code to validate end

if($valid){
	$student_script = file_get_contents("saved/".$student_id.".js");
	echo $student_script;
}else{
	http_response_code(204);
}
?>