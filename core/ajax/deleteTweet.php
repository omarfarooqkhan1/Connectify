<?php
	include '../init.php';
	 
	if(isset($_POST['deleteTweet']) && !empty($_POST['deleteTweet'])){
		$user_id = $_SESSION['user_id'];
		$tweet_id = $_POST['deleteTweet'];
		$getFromU->delete('tweets',array('tweet_id' => $tweet_id,'tweetBy' => $user_id));
	}
	if(isset($_POST['showPopup']) && !empty($_POST['showPopup'])){
		$user_id = $_SESSION['user_id'];
		$tweet_id = $_POST['showPopup'];
		$tweet = $getFromT->getPopupTweet($tweet_id);
		?>
		<div class="retweet-popup">
		  <div class="wrap5">
		    <div class="retweet-popup-body-wrap">
		      <div class="retweet-popup-heading">
		        <h3>Are you sure you want to delete this Tweet?</h3>
		        <span><button class="close-retweet-popup"><i class="fa fa-times" aria-hidden="true"></i></button></span>
		      </div>
		       <div class="retweet-popup-inner-body">
		        <div class="retweet-popup-inner-body-inner">
		          <div class="retweet-popup-comment-wrap">
		             <div class="retweet-popup-comment-head">
		              <img src="<?php echo $tweet->profileImage;?>"/>
		             </div>
		             <div class="retweet-popup-comment-right-wrap">
		               <div class="retweet-popup-comment-headline">
		                <a><?php echo $tweet->screenName;?></a><span>‏ @<?php echo $tweet->username.' '.$tweet->postedOn;?></span>
		               </div>
		               <div class="retweet-popup-comment-body">
		               	&nbsp;<?php echo $tweet->status.' '.$tweet->tweetImage;?>
		               </div>
		             </div>
		          </div>
		         </div>
		      </div>
		      <div class="retweet-popup-footer"> 
		        <div class="retweet-popup-footer-right">
		          <button class="cancel-it f-btn">Cancel&nbsp;&nbsp;&nbsp;</button><button class="delete-it" type="submit">&nbsp;Delete&nbsp;</button>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		<?php
	}
?>