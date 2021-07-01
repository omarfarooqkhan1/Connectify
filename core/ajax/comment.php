<?php
	include '../init.php';
 
	if(isset($_POST['comment']) && !empty($_POST['comment'])){
		$comment = $getFromU->checkInput($_POST['comment']);
		$user_id = $_SESSION['user_id'];
		$tweet_id = $_POST['tweet_id'];
		if(!empty($comment)){
			$getFromU->create('comments',array('comment' => $comment, 'commentOn' => $tweet_id, 'commentBy' => $user_id,'commentAt' => date('Y-m-d H:i:s')));
			$comments = $getFromT->comments($tweet_id);
			$tweet = $getFromT->getPopupTweet($tweet_id);
			foreach($comments as $comment){
	 			echo '<div class="tweet-show-popup-comment-box">
						<div class="tweet-show-popup-comment-inner">
							<div class="tweet-show-popup-comment-head">
								<div class="tweet-show-popup-comment-head-left">
									 <div class="tweet-show-popup-comment-img">
									 	<img src="'.$comment->profileImage.'">
									 </div>
								</div>
								<div class="tweet-show-popup-comment-head-right">
									  <div class="tweet-show-popup-comment-name-box">
									 	<div class="tweet-show-popup-comment-name-box-name"> 
									 		<a href="'.$comment->username.'">'.$comment->screenName.'</a>
									 	</div>
									 	<div class="tweet-show-popup-comment-name-box-tname">
									 		<a href="'.$comment->username.'">@'.$comment->username.''.$getFromU->timeAgo($comment->commentAt).'</a>
									 	</div>
									 </div>
									 <div class="tweet-show-popup-comment-right-tweet">
									 		<p><a href="'.$comment->username.'">@'.$tweet->username.'</a> '.$comment->comment.'</p>
									 </div>
								 	<div class="tweet-show-popup-footer-menu">
										<ul>
											<li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>
											<li><a href="javascript: void(0);"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
											'.(($comment->commentBy === $user_id) ? '
														<li>
														<a href="javascript: void(0);" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
														<ul> 
														  <li><label class="deleteComment" data-tweet="'.$tweet->tweet_id.'" data-comment="'.$comment->comment_id.'">Delete Comment</label></li>
														</ul>
														</li>' : '').'
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!--TWEET SHOW POPUP COMMENT inner END-->
					</div>';
	 		}

		}
	}
?>