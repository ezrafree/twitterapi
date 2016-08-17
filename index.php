<?php
	require_once('config.php');
	require_once('functions.php');
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Twitter API</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/main.css">
	</head>
	<body>

		<?php
			date_default_timezone_set($timezone);
			ini_set('display_errors', 1);
			require_once('TwitterAPIExchange.php');

			/** Search API **/
			$url = 'https://api.twitter.com/1.1/search/tweets.json';
			$requestMethod = 'GET';
			$search_value = 'apple';
			$getfield = '?q=%23' . $search_value . '&result_type=recent';
			$twitter = new TwitterAPIExchange($settings);
			$json = $twitter->setGetfield($getfield)
						 ->buildOauth($url, $requestMethod)
						 ->performRequest();
			$array = json_decode($json, true);
			$statuses = $array['statuses'];
			foreach($statuses AS $tweet) {

				// save values to variables
				$text = $tweet['text'];
				$created_at = $tweet['created_at'];
				$name = $tweet['user']['name'];
				$screen_name = $tweet['user']['screen_name'];
				$profile_image_url = $tweet['user']['profile_image_url'];

				// format the time since value
				$date = new DateTime($created_at);
				$date->setTimezone(new DateTimeZone($timezone));
				$interval = $date->diff(new \DateTime('now'));
				$created_ago = $interval->format('%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
				$created_parts = explode(',', $created_ago);
				$time_since = '';
				foreach($created_parts AS $segment) {
					if( ' ' == substr($segment, 0, 1) ) $segment = substr($segment, 1, strlen($segment));
					if( '0' != substr($segment, 0, 1) ) $time_since .= $segment . ', ';
				}
				$time_since = substr($time_since, 0, strlen($time_since)-2 );

				// set up media
				if( isset($tweet['extended_entities']['media']) && $tweet['extended_entities']['media'] ) {
					$media = $tweet['extended_entities']['media'];
					foreach($media AS $key => $item) {
						$media_type = $item['type'];
						$media_url = $item['media_url'];
					}
				}

				?>
				<div class="container">
					<?php if( isset($media) && $media ) { ?>
						<div class="media">
							<?php if( isset($media_type) && $media_type == 'photo' ) { ?>
								<img src="<?= $media_url ?>" alt="">
							<?php } ?>
						</div>
					<?php } ?>
					<div class="text"><?= $text ?></div>
					<div class="created"><?= $time_since ?> ago</div>
					<div class="author">
						<img class="avatar" src="<?= $profile_image_url ?>" alt="">
						<span class="username"><?= $screen_name ?></span>
					</div>
				</div>
				<?php

			}
		?>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/main.js"></script>
	</body>
</html>