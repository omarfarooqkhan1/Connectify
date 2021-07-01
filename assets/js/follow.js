$(function(){
	$('.follow-btn').click(function(){
		var follow_id = $(this).data('follow');
		var profile_id = $(this).data('profile');
		$button = $(this);
		if($button.hasClass('following-btn')){
			$.post('http://localhost/Connectify/core/ajax/follow.php',{unfollow:follow_id,profile_id:profile_id},function(data){
				data = JSON.parse(data);
				$button.removeClass('following-btn');
				$button.removeClass('unfollow-btn');
				$button.html('<i class="fa fa-user-plus"></i>Follow');
				$('.count-following').text(data.following);
				$('.count-followers').text(data.followers);
			});
		} else{
			$.post('http://localhost/Connectify/core/ajax/follow.php',{follow:follow_id,profile_id:profile_id},function(data){
				data = JSON.parse(data);
				$button.removeClass('follow-btn');
				$button.addClass('following-btn');
			//	$button.text('Following');
				$button.html('<i class="fa fa-user-plus"></i>Following');

				$('.count-following').text(data.following);
				$('.count-followers').text(data.followers);
			});
		}
	});
	$('.follow-btn').hover(function(){
		$button = $(this);
		if($button.hasClass('following-btn')){
			$button.addClass('unfollow-btn');
			$button.text('Unfollow');
		}
	},function(){
		if($button.hasClass('following-btn')){
			$button.removeClass('unfollow-btn');
			$button.text('Following');
		}
	});
});