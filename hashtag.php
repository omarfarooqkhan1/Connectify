<?php 
	include 'core/init.php';


	if(isset($_GET['hashtag']) && !empty($_GET['hashtag'])){
		$hashtag = $getFromU->checkInput($_GET['hashtag']);
		$user_id = $_SESSION['user_id'];
		$user = $getFromU->userData($user_id);
		$tweets =$getFromT->getTweetsByHash($hashtag);
		$accounts=$getFromT->getUsersByHash($hashtag);
		$notify = $getFromM->getNotificationCount($user_id);
		

	} else{
		header('Location: http://localhost/Connectify/index.php');
	}
?>

<html>
	<head>
		<title><?php echo '#'.$hashtag. '  hashtag on Connectify';?></title>
		  <meta charset="UTF-8" />
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css"/>  
 	  	  <link rel="stylesheet" href="../assets/css/style-complete.css"/>  
 	  	  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script> 	  
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
				<li><a href="../index.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
				<li><a href="http://localhost/Connectify/i/notifications"><i class="fa fa-bell" aria-hidden="true"></i>Notifications<span id ="notification"><?php if ($notify->totalN > 0) {
					echo '<span class="span-i">'. $notify->totalN .' </span>';
				}       ?>       </span></a></li>
				<li id="messagePopup"><i class="fa fa-envelope" aria-hidden="true"></i>Messages<span id ="messages"><?php if ($notify->totalM > 0) {
					echo '<span class="span-i">'. $notify->totalM .' </span>';
				}       ?>       </span></li>
			</ul>
		</div><!-- nav left ends-->

		<div class="nav-right">
			<ul>
				<li>
					<input type="text" placeholder="Search" class="search"/>
					<i class="fa fa-search" aria-hidden="true"></i>
					<div class="search-result">			
					</div>
				</li>

				<li class="hover"><label class="drop-label" for="drop-wrap1"><img src="<?php  echo 'http://localhost/Connectify/'.$user->profileImage;?>"/></label>
				<input type="checkbox" id="drop-wrap1">
				<div class="drop-wrap">
					<div class="drop-inner">
						<ul>
							<li><a href="../<?php echo $user->username;?>"><?php echo $user->username;?></a></li>
							<li><a href="../settings/account">Settings</a></li>
							<li><a href="../includes/logout.php">Log out</a></li>
						</ul>
					</div>
				</div>
				</li>
				<li><label class="addTweetBtn">Tweet</label></li>
			</ul>
		</div><!-- nav right ends-->

	</div><!-- nav ends -->

</div><!-- nav container ends -->

</div><!-- header wrapper end -->

<!--#hash-header-->
<div class="hash-header">
	<div class="hash-inner">
		<h1>#<?php echo $hashtag;?></h1>
	</div>
</div>	
<!--#hash-header end-->

<!--hash-menu-->
<div class="hash-menu">
	<div class="hash-menu-inner">
		<ul>
 			<li><a href="<?php echo '../hashtag/'.$hashtag;?>">Latest</a></li>
			<li><a href="<?php echo '../hashtag/'.$hashtag.'?f=users';?>">Accounts</a></li>
			<li><a href="<?php echo '../hashtag/'.$hashtag.'?f=photos';?>">Photos</a></li>
  		</ul>
	</div>
</div>
<!--hash-menu-->
<!---Inner wrapper-->

<div class="in-wrapper">
	<div class="in-full-wrap">
		
		<div class="in-left">
			<div class="in-left-wrap">
				<?php  $getFromF->whoToFollow($user_id,$user_id) ;    ?>
					<?php  $getFromT->trends() ;    ?>
			</div>
			<!-- in left wrap-->
		</div>
		<!-- in left end-->
<?php  if(strpos($_SERVER['REQUEST_URI'],'?f=photos')) :  ?>
<!-- TWEETS IMAGES  -->
   <div class="hash-img-wrapper"> 
 	<div class="hash-img-inner"> 
 		<?php
 		 foreach ($tweets as $tweet) {
 		 	$likes = $getFromT->likes($user_id,$tweet->tweet_id);
			$retweet = $getFromT->checkRetweet($user_id,$tweet->tweet_id);
			$user = $getFromU->userData($tweet->retweetBy);
 			 if (!empty($tweet->tweetImage)) {
 			 	echo '		 <div class="hash-img-flex">

		 	<img src="'.'../'.$tweet->tweetImage.'" data-tweet="'.$tweet->tweet_id.'"/>
		 	<div class="hash-img-flex-footer">
		 		<ul>
		 			'.(($getFromU->loggedIn() === true) ? '
										<li><button><a href="javascript:"><i class="fa fa-share" aria-hidden="true"></i></a></button></li>
										<li>'.(($tweet->tweet_id === '$retweet["retweet_id"]' OR $user_id === '$retweet["retweet_id"]') ? '<button class="retweeted" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.$tweet->retweetCount.'</span></a></button>':'<button class="retweet" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.(($tweet->retweetCount > 0) ? $tweet->retweetCount : '').'</span></a></button>').'</li>
										<li>'.(('$likes["likeOn"]' === $tweet->tweet_id) ? '<button class="unlike-btn" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">'.$tweet->likesCount.'</span></a></button>' : '<button class="like-btn" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '').'</span></a></button>' ).'</li>
											'.(($tweet->tweetBy === $user_id) ? '
											<li>
											<a href="javascript: void(0);" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
											<ul> 
											  <li><label class="deleteTweet" data-tweet="'.$tweet->tweet_id.'">Delete Tweet</label></li>
											</ul>
											</li>' : '').'
											' : '<li><button><a href="javascript:"><i class="fa fa-share" aria-hidden="true"></i></a></button></li>
												  <li><button><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i></a></button></li>
												  <li><button><a href="javascript:"><i class="fa fa-heart" aria-hidden="true"></i></a></button></li>').'
		 		</ul>
		 	</div>
		</div>';
 			 }
 		}   
 		?>

	</div>
</div>   
<!-- TWEETS IMAGES -->
<?php elseif(strpos($_SERVER['REQUEST_URI'],'?f=users')):  ?>
<!--TWEETS ACCOUTS-->
 <div class="wrapper-following">
<div class="wrap-follow-inner">
<?php  foreach($accounts as $users) :    ?>
 <div class="follow-unfollow-box">
	<div class="follow-unfollow-inner">
		<div class="follow-background">
			<img src="<?php  echo '../'.$users->profileCover;?>"/>
		</div>
		<div class="follow-person-button-img">
			<div class="follow-person-img">
			 	<img src="<?php  echo '../'.$users->profileImage;?>"/>
			</div>
			<div class="follow-person-button">
			   <?php  echo $getFromF->followBtn($users->user_id,$user_id,$user_id);              ?>
			</div>
		</div>
		<div class="follow-person-bio">
			<div class="follow-person-name">
				<a href="<?php  echo '../'.$users->username;?>"><?php  echo $users->screenName;?></a>
			</div>
			<div class="follow-person-tname">
				<a href="<?php  echo '../'.$users->username;?>">@<?php  echo $users->username;?></a>
			</div>
			<div class="follow-person-dis">
			    <?php echo $getFromT->getTweetLinks($users->bio)  ;?>
			</div>
		</div>
	</div>
</div>
<?php endforeach;   ?>
</div>
</div> 
<!-- TWEETS ACCOUNTS -->
	<?php  else :  ?>	
	 <div class="in-center">
		<div class="in-center-wrap">
		<?php
			 
		
		foreach($tweets as $tweet){
				
				$likes = $getFromT->likes($user_id,$tweet->tweet_id);
				$retweet = $getFromT->checkRetweet($user_id,$tweet->tweet_id);
				$user = $getFromU->userData($tweet->retweetBy);
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
										<img src="../'.$tweet->profileImage.'"/>
									</div>
									<div class="t-s-head-content">
										<div class="t-h-c-name">
											<span><a href="'.$tweet->username.'">'.$tweet->screenName.'</a></span>
											<span>@'.$tweet->username.'</span>
											<span>'.$getFromU->timeAgo($tweet->postedOn).'</span>
										</div>
										<div class="t-h-c-dis">
											'.$getFromT->getTweetLinks($tweet->status).'
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
										'.(($getFromU->loggedIn() === true) ? '
										<li><button><a href="javascript:"><i class="fa fa-share" aria-hidden="true"></i></a></button></li>
										<li>'.(($tweet->tweet_id === '$retweet["retweet_id"]' OR $user_id === '$retweet["retweet_id"]') ? '<button class="retweeted" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.$tweet->retweetCount.'</span></a></button>':'<button class="retweet" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.(($tweet->retweetCount > 0) ? $tweet->retweetCount : '').'</span></a></button>').'</li>
										<li>'.(('$likes["likeOn"]' === $tweet->tweet_id) ? '<button class="unlike-btn" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">'.$tweet->likesCount.'</span></a></button>' : '<button class="like-btn" data-tweet="'.$tweet->tweet_id.'" data-user="'.$tweet->tweetBy.'"><a href="javascript:"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '').'</span></a></button>' ).'</li>
											'.(($tweet->tweetBy === $user_id) ? '
											<li>
											<a href="javascript: void(0);" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
											<ul> 
											  <li><label class="deleteTweet" data-tweet="'.$tweet->tweet_id.'">Delete Tweet</label></li>
											</ul>
											</li>' : '').'
											' : '<li><button><a href="javascript:"><i class="fa fa-share" aria-hidden="true"></i></a></button></li>
												  <li><button><a href="javascript:"><i class="fa fa-retweet" aria-hidden="true"></i></a></button></li>
												  <li><button><a href="javascript:"><i class="fa fa-heart" aria-hidden="true"></i></a></button></li>').'
									</ul>
								</div>
							</div>
						  </div>
						</div>
					  </div>';
			}


		?>
		</div>
	</div>
		<?php  endif  ;  ?>

		<div class="popupTweet"></div>


	</div><!--in full wrap end-->
</div><!-- in wrappper ends-->

</div><!-- ends wrapper -->

</body>


 
<script type="text/javascript" src="http://localhost/Connectify/assets/js/search.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/popupTweets.js"></script>
 <script type="text/javascript" src="http://localhost/Connectify/assets/js/like.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/retweet.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/delete.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/comment.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/popupForm.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/fetch.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/search.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/popupTweets.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/hashtag.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/messages.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/postMessage.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/follow.js"></script>
<script type="text/javascript" src="http://localhost/Connectify/assets/js/notification.js"></script>


</html>