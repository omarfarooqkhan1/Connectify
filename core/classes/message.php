<?php
	/**
	 * 
	 */

	class Message extends User
	{
		
		function __construct($pdo)
		{
			$this->pdo = $pdo;
		}

		public function recentMessages($user_id){
			$stmt= $this->pdo->prepare("SELECT * FROM `messages` LEFT JOIN `users` on `messageFrom` =`user_id` where `messageTo`=:user_id ");
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}



		public function getMessages($messageFrom,$user_id){
			$stmt= $this->pdo->prepare("SELECT * FROM `messages`  LEFT JOIN `users` on `messageFrom` =`user_id` where `messageFrom`=:messageFrom AND  `messageTo`=:user_id OR `messageTo`=:messageFrom AND `messageFrom`=:user_id");

			$stmt->bindParam(':messageFrom',$messageFrom,PDO::PARAM_INT);
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();		
		$messages= $stmt->fetchAll(PDO::FETCH_OBJ);


			foreach ($messages as $message) {
				if ($message->messageFrom===$user_id) {

					echo'
 <!-- Main message BODY RIGHT START -->
<div class="main-msg-body-right">
		<div class="main-msg">
			<div class="msg-img">
				<a href="#"><img src="http://localhost/Connectify/'.$message->profileImage.'"/></a>
			</div>
			<div class="msg">'.$message->message.'
				<div class="msg-time">
					'.$this->timeAgo($message->messageOn).'				  
				</div>
			</div>
			<div class="msg-btn">
				<a><i class="fa fa-ban" aria-hidden="true"></i></a>
				<a class="deleteMsg" data-message="'.$message->messageID.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>';
				}else{

					echo'
	<!--Main message BODY LEFT START-->
		<div class="main-msg-body-left">
			<div class="main-msg-l">
				<div class="msg-img-l">
					<a href="#"><img src="http://localhost/Connectify/'.$message->profileImage.'"/></a>
				</div>
				<div class="msg-l">'.$message->message.'
					<div class="msg-time-l">
					'.$this->timeAgo($message->messageOn).'				  
					    

					</div>	
				</div>
				<div class="msg-btn-l">	
					<a><i class="fa fa-ban" aria-hidden="true"></i></a>
					<a class="deleteMsg" data-message="'.$message->messageID.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
				</div>
			</div>
		</div> 
';

				}
			}







		}




		public function  deleteMsg($messageID,$user_id){

			$stmt= $this->pdo->prepare("DELETE FROM `messages` where `messageID`=:messageID AND `messageFrom`=:user_id or `messageID`=:messageID AND `messageTo`=:user_id  ");

			$stmt->bindParam(':messageID',$messageID,PDO::PARAM_INT);
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
		 
		    $stmt->execute();
		    
		}

		public function getNotificationCount($user_id){

			$stmt= $this->pdo->prepare("SELECT COUNT(`messageID`) as `totalM`, (SELECT COUNT(`ID`) FROM `notification` where `notificationFor`=:user_id AND `status`='0') as `totalN` FROM `messages` WHERE `messageTo`=:user_id AND `status` = '0' ");


			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ);
		}


		public function messageViewed($user_id){
			$stmt=$this->pdo->prepare("UPDATE `messages` SET `status`='1' WHERE `messageTo`=:user_id and `status`='0' ");
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();


		}
		public function notification($user_id){
			$stmt=$this->pdo->prepare("SELECT * FROM `notification` N 
					LEFT JOIN `users` as U ON N.`notificationForm`=U.`user_id`
					LEFT JOIN  `tweets` as T ON N.`target` = T.`tweet_id`
					LEFT JOIN `likes` as L ON N.`target`=L.`LikeOn`
					LEFT JOIN `follow` as F ON N.`notificationForm`=F.`sender` and N.`notificationFor`=F.`receiver`
					where N.`notificationFor`=:user_id AND  N.`notificationForm`!=:user_id

					 ");
	//		$stmt->execute(array('user_id'=>$user_id));
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
			
		}
		public function notificationViewed($user_id){
			$stmt=$this->pdo->prepare("UPDATE `notification` SET `status`='1' WHERE `notificationFor`=:user_id and `status`='0' ");
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();


		}
		public function sendNotification($get_id,$user_id,$target,$type){
			$this->create('notification',array('notificationFor'=>$get_id, 'notificationForm'=>$user_id,'target'=>$target,'type'=>$type ));


			$fields=array('notificationFor'=>$get_id,'notificationForm'=>$user_id,'target'=>$target,'type'=>$type,'time'=>date('Y-m-d H:i:s'));
			$table=`notification`;
			$columns = implode(',', array_keys($fields));
			$values = ':'.implode(', :', array_keys($fields));
			$sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
			if($stmt = $this->pdo->prepare($sql)){
				foreach ($fields as $key => $data){
					$stmt->bindValue(':'.$key, $data);
				}
				$stmt->execute();
				return $this->pdo->lastInsertId();
			}
		 


/*
			$stmt = $this->pdo->prepare("INSERT INTO `notification` (`notificationFor`,`notificationForm`,`target`,`type`,`time` ) VALUES(:notificationFor,:notificationForm,:target,:type,date('Y-m-d H:i:s'))");
			 
			$stmt->bindValue(":notificationFor",$get_id,PDO::PARAM_INT);
			$stmt->bindValue(":notificationForm",$user_id,PDO::PARAM_INT);
			$stmt->bindValue(":target",$target,PDO::PARAM_INT);
			$stmt->bindValue(":type",$type,PDO::PARAM_STR);
			$stmt->execute();


*/

		}
















	}

?>