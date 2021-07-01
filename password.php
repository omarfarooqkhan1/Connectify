<?php 
	include 'core/init.php';

	$user_id = $_SESSION['user_id'];
	$user = $getFromU->userData($user_id);
	$notify = $getFromM->getNotificationCount($user_id);

	if($getFromU->loggedIn() === false){
		header('Location: ../index.php');
	}

	if(isset($_POST['submit'])){ 
		$currentPwd = $_POST['currentPwd'];
		$newPassword = $_POST['newPassword'];
		$rePassword = $_POST['rePassword'];
		$error = array();
		if(!empty($currentPwd) && !empty($newPassword) && !empty($rePassword)){
			if($getFromU->checkPassword($currentPwd) === true){
				if(strlen($newPassword) < 6){
					$error['newPassword'] = "New password is too short!";
				} else if($newPassword != $rePassword){
					$error['rePassword'] = "Entered passwords don't match!";
				} else{
					$getFromU->update('users',$user_id,array('password' => md5($newPassword)));
					header('Location: ../'.$user->username);
				} 
			} else {
				$error['currentPwd'] = "Password is incorrect!";
			}

		} else{
			$error['fields'] = "All fields are required!";
		}
	}
?>

<html>
	<head>
		<title>Password settings page - Connectify</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css"/>
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<link rel="stylesheet" href="../assets/css/style-complete.css"/>
	</head>
	<!--Helvetica Neue-->
<body>
<div class="wrapper">
<!-- header wrapper -->
<div class="header-wrapper">

<div class="nav-container">
  <!-- Nav -->
   <div class="nav">
		 <div class="nav-left">
			<ul>
				<li><a href="../home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
 								<li><a href="../index.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
				<li><a href="http://localhost/Connectify/i/notifications"><i class="fa fa-bell" aria-hidden="true"></i>Notifications<span id ="notification"><?php if ($notify->totalN > 0) {
					echo '<span class="span-i">'. $notify->totalN .' </span>';
				}       ?>       </span></a></li>
				<li id="messagePopup"><i class="fa fa-envelope" aria-hidden="true"></i>Messages<span id ="messages"><?php if ($notify->totalM > 0) {
					echo '<span class="span-i">'. $notify->totalM .' </span>';
				}       ?>       </span></li>
 			</ul>
		</div>
		<!-- nav left ends-->
		<div class="nav-right">
			<ul>
 				<li class="hover"><label class="drop-label" for="drop-wrap1"><img src="<?php echo '../'.$user->profileImage;?>"/></label>
				<input type="checkbox" id="drop-wrap1">
				<div class="drop-wrap">
					<div class="drop-inner">
						<ul>
							<li><a href="<?php echo '../'.$user->username;?>"><?php echo $user->username;?></a></li>
							<li><a href="account">Settings</a></li>
							<li><a href="../includes/logout.php">Log out</a></li>
						</ul>
					</div>
				</div>
				</li>
				<li><label for="pop-up-tweet">Tweet</label></li>

			</ul>
		</div>
		<!-- nav right ends-->
 
	</div>
	<!-- nav ends -->
<script type="text/javascript" src="http://localhost/Connectify/assets/js/messages.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/delete.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/hashtag.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/notification.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/popupForm.js"></script>


</div><!-- nav container ends -->
</div><!-- header wrapper end -->
		
	<div class="container-wrap">

		<div class="lefter">
			<div class="inner-lefter">

				<div class="acc-info-wrap">
					<div class="acc-info-bg">
						<!-- PROFILE-COVER -->
						<img src="<?php echo '../'.$user->profileCover;?>"/>  
					</div>
					<div class="acc-info-img">
						<!-- PROFILE-IMAGE -->
						<img src="<?php echo '../'.$user->profileImage;?>"/>
					</div>
					<div class="acc-info-name">
						<h3><?php echo $user->screenName;?></h3>
						<span><a href="<?php echo '../'.$user->username;?>">@<?php echo $user->username;?></a></span>
					</div>
				</div><!--Acc info wrap end-->

				<div class="option-box">
					<ul> 
						<li>
							<a href="account" class="bold">
							<div>
								Account
								<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
							</div>
							</a>
						</li>
						<li>
							<a href="password">
							<div>
								Password
								<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
							</div>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div><!--LEFTER ENDS-->
	<div class="righter">
		<div class="inner-righter">
			<div class="acc">
				<div class="acc-heading">
					<h2>Password</h2>
					<h3>Change your password:</h3>
				</div>
				<form method="POST">
				<div class="acc-content">
					<div class="acc-wrap">
						<div class="acc-left">
							Current password:
						</div>
						<div class="acc-right">
							<input type="password" name="currentPwd"/>
							<span>
								<?php if(isset($error['currentPwd'])){echo $error['currentPwd'];}?>
							</span>
						</div>
					</div>

					<div class="acc-wrap">
						<div class="acc-left">
							New password:
						</div>
						<div class="acc-right">
							<input type="password" name="newPassword" />
							<span>
								<?php if(isset($error['newPassword'])){echo $error['newPassword'];}?>
							</span>
						</div>
					</div>

					<div class="acc-wrap">
						<div class="acc-left">
							Confirm password:
						</div>
						<div class="acc-right">
							<input type="password" name="rePassword"/>
							<span>
								<?php if(isset($error['rePassword'])){echo $error['rePassword'];}?>
							</span>
						</div>
					</div>
					<div class="acc-wrap">
						<div class="acc-left">
						</div>
						<div class="acc-right">
							<input type="Submit" name="submit" value="Save changes"/>
						</div>
						<div class="settings-error">
							<?php if(isset($error['fields'])){echo $error['fields'];}?>
 						</div>	
					</div>
				 </form>
				</div>
			</div>
			<div class="content-setting">
				<div class="content-heading">
					
				</div>
				<div class="content-content">
					<div class="content-left">
						
					</div>
					<div class="content-right">
						
					</div>
				</div>
			</div>
		</div>	
	</div>
	<!--RIGHTER ENDS-->
	<script type="text/javascript" src="assets/js/postMessage.js"></script>
	
</div>
<!--CONTAINER_WRAP ENDS-->
</div>
<!-- ends wrapper -->
</body>
</html>
