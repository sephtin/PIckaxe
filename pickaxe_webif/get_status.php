<?php
	require_once 'auth.php';
	require_once 'bfg_api.php';
	require_once 'status.php';

	# not really html in its entirely (no outer html/body tags) -- just the html we want to set in the status section
	# therefore set content-type to text/plain
	header('Content-Type: text/plain');

	$validated = (read_ini_file('StatusWOLogin') == "enabled");
	if(!$validated)
	{
		$validated = validPasswordCookie(true, null, $GLOBALS['session_timeout']);
	}
	if($validated)
	{
		$summary = request('{"command":"summary"}');
		$devs = null;
		if($summary != null)
		{
			$devs = request('{"command":"devs"}'); 
		}
		$status_html =  generateStatusHtml($summary, $devs);
		echo "$status_html";
	}
?>
