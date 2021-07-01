<?php 
 
	if(isset($_POST['signup'])){
		$screenName = $_POST['screenName'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$Error = '';
		if(empty($screenName) or empty($password) or empty($email)){
			$Error = 'All fields are required in order to signup!';
		} else{
			$email = $getFromU->checkInput($email);
			$screenName = $getFromU->checkInput($screenName);
			$password = $getFromU->checkInput($password);
			if(!filter_var($email)){
				$Error = 'Invalid email format!';
			} else if(strlen($screenName) > 20){
				$Error = 'Name must be between 6-20 characters!';
			} else if(strlen($password) < 5){
				$Error = 'Password is too short!';
			} else {
				if($getFromU->checkEmail($email) === true){
					$Error = 'Email is already in use!';
				} else{
					$user_id = $getFromU->create('users',array('email' => $email, 'password' => md5($password), 'screenName' => $screenName, 'profileImage' => 'assets/images/defaultProfileImage.png', 'profileCover' => 'assets/images/defaultCoverImage.png'));
					$_SESSION['user_id'] = $user_id;
					header('Location: includes/signup.php?step=1');
				}
			}  
		}
	}

?>
<form method="post">
<div class="signup-div"> 
	<h3>Sign up </h3>
	<ul>
		<li>
		    <input type="text" name="screenName" placeholder="Full Name"/>
		</li>
		<li>
		    <input type="email" name="email" placeholder="Email"/>
		</li>
		<li>
			<input type="password" name="password" placeholder="Password"/>
		</li>
		<li>
			<input type="submit" name="signup" Value="Signup!">
		</li>
		<?php 
			if(isset($Error)){
				echo ' <li class="error-li">
					  <div class="span-fp-error">'.$Error.'</div>
					  </li> ';
			}
		?>
	</ul>
</div>
</form>