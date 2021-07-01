<?php
	include '../init.php';
 
	if(isset($_GET['showNotification']) && !empty($_GET['showNotification'])){

		$user_id=$_SESSION['user_id'];
		$data = $getFromM->getNotificationCount($user_id);
//		$notification=$getFromM->notification($user_id);

		echo json_encode(array('notification'=> $data->totalN,'messages'=>$data->totalM));
		
	}else{
		header('Location: http://localhost/Connectify/index.php');
	}



?>