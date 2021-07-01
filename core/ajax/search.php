<?php 
	
	include '../init.php';
 	$user_id = $_SESSION['user_id'];
	
	if(isset($_POST['search']) && !empty($_POST['search'])){
		
		$search = $getFromU->checkInput($_POST['search']);
		$result = $getFromU->search($search);

		if(!empty($result)){
		
			echo '<div class="nav-right-down-wrap"><ul>';
		
			foreach($result as $user){
				echo '<li>
				  		<div class="nav-right-down-inner">
							<div class="nav-right-down-left">
								<a href="'.$user->username.'"><img src="http://localhost/Connectify/'.$user->profileImage.'"></a>
							</div>
							<div class="nav-right-down-right">
								<div class="nav-right-down-right-headline">
									<a href="'.$user->username.'">'.$user->screenName.'
									<span>@'.$user->username.'</span></a>
								</div>
								<div class="nav-right-down-right-body">
								 
							    </div>
							</div>
						</div> 
		 			  </li>';
			}
		
			echo '</ul></div>';
		
		}
	
	}

?>