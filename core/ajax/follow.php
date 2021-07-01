<?php 
	include '../init.php';
 
	if(isset($_POST['unfollow']) && !empty($_POST['unfollow'])){
		$profile_id = $_POST['unfollow'];
		$user_id = $_SESSION['user_id'];
		$follow_id = $_POST['profile_id'];		
		$getFromF->unfollow($profile_id,$user_id,$follow_id);
	//	$getFromF->removeFollowCount($profile_id,$user_id)
	}
	if(isset($_POST['follow']) && !empty($_POST['follow'])){
		$profile_id = $_POST['follow'];
		$user_id = $_SESSION['user_id'];
		$follow_id = $_POST['profile_id'];
		$getFromM->sendNotification($profile_id,$user_id,$profile_id,'follow');
		$getFromF->follow($profile_id,$user_id,$follow_id);


	//	$getFromF->addFollowCount($profile_id,$user_id);

	}
?>