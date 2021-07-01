 <?php


	class Tweet extends User {
		function __construct($pdo){
			$this->pdo = $pdo;
		}
		public function tweets($user_id,$limit){
			$stmt = $this->pdo->prepare("SELECT * FROM `tweets` LEFT JOIN `users` ON `tweetBy` = `user_id` WHERE `tweetBy` = :user_id AND `retweet_id` = '0' OR `tweetBy` = `user_id` AND `retweetBy` != :user_id AND `tweetBy` IN (SELECT `receiver` FROM `follow` WHERE `sender` = :user_id) ORDER BY `tweet_id` DESC LIMIT :limiter");
			$stmt->bindValue(':limiter',$limit,PDO::PARAM_INT);
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();
			$tweets = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($tweets as $tweet){
				$likes = $this->likes($user_id,$tweet->tweet_id);
				$retweet = $this->checkRetweet($user_id,$tweet->tweet_id);
				$user = $this->userData($tweet->retweetBy);
				echo '<div class="all-tweet">
						<div class="t-show-wrap">	
						  <div class="t-show-inner">
						  '.(($tweet->retweet_id === '$retweet["retweet_id"]' OR $tweet->retweet_id > 0) ? '
							<div class="t-show-banner">
								<div class="t-show-banner-inner">
									<span><i class="fa fa-retweet" aria-hidden="true"></i></span><span class = "t-h-c-dis"><a href="'.$user->username.'">@'.$user->username.'</a></span> Retweeted<b>&nbsp;'.$retweet['retweetMsg'].'</b>
								</div>
							</div>' : '').'

							<div class="t-show-popup" data-tweet="'.$tweet->tweet_id.'">
								<div class="t-show-head">
									<div class="t-show-img">
										<img src="'.$tweet->profileImage.'"/>
									</div>
									<div class="t-s-head-content">
										<div class="t-h-c-name">
											<span><a href="'.$tweet->username.'">'.$tweet->screenName.'</a></span>
											<span>@'.$tweet->username.'</span>
											<span>'.$this->timeAgo($tweet->postedOn).'</span>
										</div>
										<div class="t-h-c-dis">
											'.$this->getTweetLinks($tweet->status).'
										</div>
									</div>
								</div>';
				if(!empty($tweet->tweetImage)){
									echo '<!--tweet show head end-->
											<div class="t-show-body">
											  <div class="t-s-b-inner">
											   <div class="t-s-b-inner-in">
											     <img src="'.$tweet->tweetImage.'" class="imagePopup"/>
											   </div>
											  </div>
											</div>
										   <!--tweet show body end-->';
				}
				echo '</div>
							<div class="t-show-footer">
								<div class="t-s-f-right">
									<ul> 
										<li><button><a href="javascript:"><i class="fa fa-share" aria-hidden="true"></i></a></button></li>
										<li>'.(("$tweet->tweet_id" === '$retweet["retweet_id"]') ? '<button class="retweeted" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.$tweet->retweetCount.'</span></a></button>':'<button class="retweet" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.(($tweet->retweetCount > 0) ? $tweet->retweetCount : '').'</span></a></button>').'</li>
										<li>'.(('$likes["likeOn"]' === $tweet->tweet_id) ? '<button class="unlike-btn" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">'.$tweet->likesCount.'</span></a></button>' : '<button class="like-btn" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '').'</span></a></button>' ).'</li>
											'.(($tweet->tweetBy === $user_id) ? '
											<li>
											<a href="javascript: void(0);" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
											<ul> 
											  <li><label class="deleteTweet" data-tweet="'.$tweet->tweet_id.'">Delete Tweet</label></li>
											</ul>
											</li>' : '').'
									</ul>
								</div>
							</div>
						  </div>
						</div>
					  </div>';
			}
		}

		public function getUserTweets($user_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `tweets` LEFT JOIN `users` ON `tweetBy` = `user_id` WHERE `tweetBy` = :user_id AND `retweet_id` = '0' OR `retweetBy` = :user_id");
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}		

		public function addLike($user_id, $tweet_id, $get_id){
			$stmt = $this->pdo->prepare("UPDATE `tweets` SET `likesCount` = `likesCount` +1 WHERE `tweet_id` = :tweet_id");
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			$this->create('likes',array('likeBy' => $user_id,'likeOn' => $tweet_id));

	//		if($get_id != $user_id){
	//			Message::$getFromM->sendNotification($get_id,$user_id,$tweet_id,'like');
	//		}
		}

		public function unlike($user_id, $tweet_id, $get_id){
			$stmt = $this->pdo->prepare("UPDATE `tweets` SET `likesCount` = `likesCount` -1 WHERE `tweet_id` = :tweet_id");
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			$stmt = $this->pdo->prepare("DELETE FROM `likes` WHERE `likeBy` = :user_id AND `likeOn` = :tweet_id");
 			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
		}

		public function likes($user_id, $tweet_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `likes` WHERE `likeBy` = :user_id AND `likeOn` = :tweet_id");
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
			 



		}

		public function getTrendByHash($hashtag){
			$stmt = $this->pdo->prepare("SELECT * FROM `trends` WHERE `hashtag` LIKE :hashtag LIMIT 5");
			$stmt->bindValue(':hashtag',$hashtag.'%');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function getMention($mention){
			$stmt = $this->pdo->prepare("SELECT `username`,`screenName`,`profileImage` FROM `users` WHERE `username` LIKE :mention OR `screenName` LIKE :mention LIMIT 5");
			$stmt->bindValue(':mention',$mention.'%');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function addTrend($hashtag){
			preg_match_all("/#+([a-zA-Z0-9_]+)/i",$hashtag,$matches);
			if($matches){
				$result = array_values($matches[1]);
			}
			$sql = "INSERT INTO `trends` (`hashtag`,`createdOn`) VALUES(:hashtag,CURRENT_TIMESTAMP)";
			foreach($result as $trend){
				if($stmt = $this->pdo->prepare($sql)){
					$stmt->execute(array(':hashtag' => $trend));
				}
			}
		}

		public function addMention($status,$user_id,$tweet_id){
		//	$getFromM->sendNotification($user_id,$user_id,$tweet_id,'mention');

			preg_match_all("/@+([a-zA-Z0-9_]+)/i",$status,$matches);
			if($matches){
				$result = array_values($matches[1]);
			}
			$sql =	"SELECT * FROM `users` where `username`:mention";
			foreach($result as $trend){
				if($stmt = $this->pdo->prepare($sql)){
					$stmt->execute(array(':mention' => $trend));
					$data=$stmt->fetch(PDO::FETCH_OBJ);
				}
			}
		//	if ($data->user_id!= $user_id) {
		//		Message::sendNotification($data->user_id,$user_id,$tweet_id,'mention');
				
		//	}


		}






		public function getTweetLinks($tweet){
			$tweet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blank'>$0</a>", $tweet);
			$tweet = preg_replace("/#([\w]+)/","<a href='hashtag/$1'>$0</a>",$tweet);
			$tweet = preg_replace("/@([\w]+)/","<a href='$1'>$0</a>",$tweet);
			return $tweet;
		}

		public function getPopupTweet($tweet_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `tweets`,`users` WHERE `tweet_id` = :tweet_id AND `tweetBy` = `user_id`");
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_OBJ);
		}

	  	public function retweet($tweet_id, $user_id, $get_id, $comment){
			$stmt = $this->pdo->prepare("UPDATE `tweets` SET `retweetCount` = `retweetCount` +1 WHERE `tweet_id` = :tweet_id");
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			$stmt = $this->pdo->prepare("INSERT INTO `tweets` (`status`,`tweetBy`,`tweetImage`,`retweet_id`,`retweetBy`,`postedOn`,`likesCount`,`retweetCount`,`retweetMsg`) SELECT `status`,`tweetBy`,`tweetImage`,`tweet_id`,:user_id,`postedOn`,`likesCount`,`retweetCount`,:comment FROM `tweets` WHERE `tweet_id` = :tweet_id");
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->bindValue(':comment',$comment,PDO::PARAM_STR);
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			Message::sendNotification($get_id,$user_id,$tweet_id,'retweet');

		}

		public function checkRetweet($user_id,$tweet_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `tweets` WHERE `retweet_id` = :tweet_id AND `retweetBy` = :user_id OR `tweet_id` = :tweet_id AND `retweetBy` = :user_id");
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

		public function comments($tweet_id){
			$stmt = $this->pdo->prepare("SELECT * FROM `comments` LEFT JOIN `users` ON `commentBy` = `user_id` WHERE `commentOn` = :tweet_id  ORDER BY `commentAt` DESC");
			$stmt->bindValue(':tweet_id',$tweet_id,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		public function countTweets($user_id){
			$stmt = $this->pdo->prepare("SELECT COUNT(`tweet_id`) AS `totalTweets` FROM `tweets` WHERE `tweetBy` = :user_id AND `retweet_id` = '0' OR `retweetBy` = :user_id");
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();
			$count = $stmt->fetch(PDO::FETCH_OBJ);
			echo $count->totalTweets;
		}

		public function countLikes($user_id){
			$stmt = $this->pdo->prepare("SELECT COUNT(`like_id`) AS `totalLikes` FROM `likes` WHERE `likeBy` = :user_id");
			$stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
			$stmt->execute();
			$count = $stmt->fetch(PDO::FETCH_OBJ);
			echo $count->totalLikes;
		}

		public function trends(){
			$stmt = $this->pdo->prepare("SELECT  *,COUNT(`tweet_id`) AS `tweetsCount` FROM `trends` INNER JOIN `tweets` ON `status` LIKE CONCAT('%#',`hashtag`,'%') or `retweetMsg` LIKE CONCAT('%#',`hashtag`,'%')  GROUP BY `hashtag` ORDER BY `tweet_id`");
			$stmt->execute();
			$trends = $stmt->fetchAll(PDO::FETCH_OBJ);
			echo '<div class="trend-wrapper"><div class="trend-inner"><div class="trend-title"><h3>Trending</h3></div>';
			foreach($trends as $trend){
				echo '<div class="trend-body">
						<div class="trend-body-content">
							<div class="trend-link">
								<a href="hashtag/'.$trend->hashtag.'">#'.$trend->hashtag.'</a>
							</div>
							<div class="trend-tweets">
								'.$trend->tweetsCount.' <span>tweets</span>
							</div>
						</div>
					  </div>';
			}
			echo '</div></div>';
		}

		public function getTweetsByHash($hashtag){


			$stmt=$this->pdo->prepare("SELECT * FROM `tweets` LEFT JOIN `users` ON `tweetBy`=`user_id` WHERE `status` LIKE :hashtag OR `retweetMsg` LIKE :hashtag " );

			$stmt->bindValue(':hashtag','%#'.$hashtag.'%', PDO::PARAM_STR);

			 $stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
				

		}


		public function getUsersByHash($hashtag){
			$stmt = $this->pdo->prepare("SELECT DISTINCT * FROM `tweets` INNER JOIN `users` ON `tweetBy`=`user_id` WHERE `status` LIKE :hashtag OR `retweetMsg` LIKE :hashtag GROUP BY `user_id` ");

			$stmt->bindValue(':hashtag','%#'.$hashtag.'%', PDO::PARAM_STR);
			 $stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}















	}
?>