<?php

function timeSince($time) {
	$since = time() - strtotime($time);
	$string = '';
	$chunks = array(
		array(60 * 60 * 24 * 365 , 'year'),
		array(60 * 60 * 24 * 30 , 'month'),
		array(60 * 60 * 24 * 7, 'week'),
		array(60 * 60 * 24 , 'day'),
		array(60 * 60 , 'hour'),
		array(60 , 'minute'),
		array(1 , 'second')
	);
	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
		$name = $chunks[$i][1];
		if (($count = floor($since / $seconds)) != 0) {
			break;
		}
	}
	$string = ($count == 1) ? '1 ' . $name . ' ago' : $count . ' ' . $name . 's ago';
	return $string;
}
