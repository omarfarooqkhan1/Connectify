<?php
	
	include '../init.php'; 
 
	if(isset($_POST) && !empty($_POST)){
		$status = $getFromU->checkInput($_POST['status']);
		$user_id = $_SESSION['user_id'];
		$tweetImage = '';
		
		if (!empty($status) or !empty($_FILES['file']['name'])){
			if(!empty($_FILES['file']['name'][0])){
				$tweetImage = $getFromU->uploadImage($_FILES['file']);
			}
			if(strlen($status)>140){
				$error = "The text of your tweet is too long!";
			}
			$tweet_id=$getFromU->create('tweets',array('status' => $status, 'tweetBy' => $user_id,'tweetImage' => $tweetImage,'postedOn' => date('Y-m-d H:i:s')));
			preg_match_all("/#+([a-zA-Z0-9_]+)/i",$status,$hashtag);
			if(!empty($hashtag)){
				$getFromT->addTrend($status);
			}
			$getFromT->addMention($status,$user_id,$tweet_id);


			$result['success'] = "Tweet posted successfully!";
			echo json_encode($result);

		} else{
			$error = "Please type something or choose an image first!";
		}
		
		if(isset($error)){
			$result['error'] = $error;
			echo json_encode($result);
		}
	}
?>