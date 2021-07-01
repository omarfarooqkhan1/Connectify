<?php
	include '../init.php';
	if ($_SERVER['REQUEST_METHOD']=="GET" && realpath(__file__)==realpath($_SERVER['SCRIPT_FILENAME'])) {
		header('Location: http://localhost/Connectify/index.php');
	}
	
	if (isset($_POST['search']) && !empty($_POST['search']) ) {
		$user_id=$_SESSION['user_id'];
		$search=$getFromU->checkInput($_POST['search']);
		$result=$getFromU->search($search);
		echo '<h4>People</h4>
<div class="message-recent" > ';

		foreach ($result as $user) {
		if ($user->user_id != $user_id) {
				echo('
<div class="people-message" data-user="'.$user->user_id.'">
	<div class="people-inner" data-user="http://localhost/Connectify/'.$user->user_id.'">
		<div class="people-img">
			<img src="http://localhost/Connectify/'.$user->profileImage.'"/>
		</div>
		<div class="name-right">
			<span><a>'.$user->screenName.'</a></span><span>@'.$user->username.'</span>
		</div>
	</div>
 </div>
');
			}
		}


		echo '</div>';
	}


?>

