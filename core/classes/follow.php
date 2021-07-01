<?php 
	class Follow extends User {
		function __construct($pdo){
			$this->pdo = $pdo;
		}

		public function checkFollow($profile_id,$user_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `follow` WHERE `sender` = :user_id AND `receiver` = :profile_id");
			$stmt->bindValue(":user_id",$user_id,PDO::PARAM_INT);
			$stmt->bindValue(":profile_id",$profile_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

		public function followBtn($profile_id,$user_id,$follow_id){
			$data = $this->checkFollow($profile_id,$user_id);
			if($this->loggedIn() === true){
				if($profile_id != $user_id){
					if('$data["receiver"]' == $profile_id){
						return "<button class='f-btn following-btn follow-btn' data-follow='$profile_id' data-profile='$follow_id'>Following</button>";
					} else{
						return "<button class='f-btn follow-btn' data-follow='$profile_id' data-profile='$follow_id'><i class='fa fa-user-plus'></i>Follow</button>";
					}
				} else{
					return "<button class='f-btn' onclick=location.href='profileEdit.php'>Edit Profile&nbsp;&nbsp;</button>";
				}
			} else{
				return "<button class='f-btn' onclick=location.href='index.php'><i class='fa fa-user-plus'></i>Follow</button>";
			}
		}

		public function follow($profile_id,$user_id,$follow_id){
			$this->create('follow',array('sender' => $user_id, 'receiver' => $profile_id, 'followOn' => date("Y-M-D H:i:s")));
			$this->addFollowCount($profile_id,$user_id);
			$stmt = $this->pdo->prepare("SELECT `user_id`,`following`,`followers` FROM `users` LEFT JOIN `follow` ON `sender` = :user_id AND CASE WHEN `receiver` = :user_id THEN `sender` = `user_id` END WHERE `user_id` = :follow_id");
			$stmt->execute(array("user_id" => $user_id,"follow_id" => $follow_id));
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			echo json_encode($data);
			
			
		

		}

		public function unfollow($profile_id,$user_id,$follow_id){
			$this->delete('follow',array('sender' => $user_id, 'receiver' => $profile_id));
			$this->removeFollowCount($profile_id,$user_id);
			$stmt = $this->pdo->prepare("SELECT `user_id`,`following`,`followers` FROM `users` LEFT JOIN `follow` ON `sender` = :user_id AND CASE WHEN `receiver` = :user_id THEN `sender` = `user_id` END WHERE `user_id` = :follow_id");
			$stmt->execute(array("user_id" => $user_id,"follow_id" => $follow_id));
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			echo json_encode($data);
		}

		public function addFollowCount($profile_id,$user_id){
			$stmt=$this->pdo->prepare("UPDATE `users` SET `following` = `following` + 1 WHERE `user_id` = :user_id; UPDATE `users` SET `followers` = `followers` + 1 WHERE `user_id` = :profile_id");
			$stmt->execute(array("user_id" => $user_id, "profile_id" => $profile_id));
		}

		public function removeFollowCount($profile_id,$user_id){
			$stmt=$this->pdo->prepare("UPDATE `users` SET `following` = `following` - 1 WHERE `user_id` = :user_id;
				UPDATE `users` SET `followers` = `followers` - 1 WHERE `user_id` = :profile_id");
			$stmt->execute(array("user_id" => $user_id, "profile_id" => $profile_id));
		}

		public function followingList($profile_id,$user_id,$follow_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `users` LEFT JOIN `follow` ON `receiver` = `user_id` AND CASE WHEN `sender` = :user_id THEN `receiver` = `user_id` END WHERE `sender` IS NOT NULL");
			$stmt->bindValue(":user_id",$profile_id,PDO::PARAM_INT);
			$stmt->execute();
			$followings = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($followings as $following){
				echo '<div class="follow-unfollow-box">
						<div class="follow-unfollow-inner">
							<div class="follow-background">
								<img src="../'.$following->profileCover.'"/>
							</div>
							<div class="follow-person-button-img">
								<div class="follow-person-img"> 
								 	<img src="../'.$following->profileImage.'"/>
								</div>
								<div class="follow-person-button"> 
								 	'.$this->followBtn($following->user_id,$user_id,$follow_id).'
								</div>
							</div>
							<div class="follow-person-bio">
								<div class="follow-person-name">
									<a href="../'.$following->username.'">'.$following->screenName.'</a>
								</div>
								<div class="follow-person-tname">
									<a href="../'.$following->username.'">@'.$following->username.'</a>
								</div>
								<div class="follow-person-dis">
									'.Tweet::getTweetLinks($following->bio).'
								</div>
							</div>
						</div>
					  </div>';
			}
		}

		public function followersList($profile_id,$user_id,$follow_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `users` LEFT JOIN `follow` ON `sender` = `user_id` AND CASE WHEN `receiver` = :user_id THEN `sender` = `user_id` END WHERE `receiver` IS NOT NULL");
			$stmt->bindValue(":user_id",$profile_id,PDO::PARAM_INT);
			$stmt->execute();
			$followings = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($followings as $following){
				echo '<div class="follow-unfollow-box">
						<div class="follow-unfollow-inner">
							<div class="follow-background">
								<img src="../'.$following->profileCover.'"/>
							</div>
							<div class="follow-person-button-img">
								<div class="follow-person-img"> 
								 	<img src="../'.$following->profileImage.'"/>
								</div>
								<div class="follow-person-button"> 
								 	'.$this->followBtn($following->user_id,$user_id,$follow_id).'
								</div>
							</div>
							<div class="follow-person-bio">
								<div class="follow-person-name">
									<a href="../'.$following->username.'">'.$following->screenName.'</a>
								</div>
								<div class="follow-person-tname">
									<a href="../'.$following->username.'">@'.$following->username.'</a>
								</div>
								<div class="follow-person-dis">
									'.Tweet::getTweetLinks($following->bio).'
								</div>
							</div>
						</div>
					  </div>';
			}
		}

		public function whoToFollow($user_id,$profile_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `user_id` != :user_id AND `user_id` NOT IN (SELECT `receiver` FROM `follow` WHERE `sender` = :user_id) ORDER BY rand() LIMIT 5");
			$stmt->bindValue(":user_id",$user_id,PDO::PARAM_INT);
			$stmt->execute();
			$suggestions = $stmt->fetchAll(PDO::FETCH_OBJ);

			echo '<div class="follow-wrap"><div class="follow-inner"><div class="follow-title"><h3>Who to follow</h3></div>';
	
			foreach($suggestions as $profile){
				echo '<div class="follow-body">
					  	<div class="follow-img">
					  	  <img src="http://localhost/Connectify/'.$profile->profileImage.'"/>
					    </div>
					    
					  	<div class="follow-content">
					  		<div class="fo-co-head">
					  			<a href="'.$profile->username.'">'.$profile->screenName.'</a><span> @'.$profile->username.'</span>
					   		</div>
					   		'.$this->followBtn($profile->user_id,$user_id,$profile_id).'
					   	</div>
					  </div>';
			}
	
			echo '</div></div>';
		}

	}

?>