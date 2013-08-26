<?php



require_once  "auth.php" ;


if(!validPasswordCookie(true, null, $GLOBALS['session_timeout']))
{
	header('Location: /index.php');

}
else
{

	$config_path="/usr/local/share/bfgminer/bfgminer.conf";

	$fh = fopen($config_path, "r");
	$config_data = fread($fh, filesize($config_path));
	fclose($fh);
	$json_config = json_decode($config_data, true);


	$need_miner_restart = false;
	$pool_variables = [ "primary_url", "primary_user", "primary_pass", "fallback_url", "fallback_user", "fallback_pass" ] ;
	foreach($pool_variables as $pv) 
	{
		$pool_num = preg_match('/^primary/', $pv) == 1 ? 0 : 1;
		$pool_var = preg_replace('/^.*_/', '', $pv);
		if($json_config["pools"][$pool_num][$pool_var] != $_POST[$pv])
		{
			$need_miner_restart = true;
			$json_config["pools"][$pool_num][$pool_var] = $_POST[$pv];
		}
	}

	$config_data = json_encode($json_config, JSON_PRETTY_PRINT);
	$fh = fopen($config_path, "w");
	fwrite($fh, $config_data);
	fclose($fh);


	if(isset($_POST['nl_status']))
	{
		write_ini_file('StatusWOLogin', "enabled");
	}
	else
	{
		write_ini_file('StatusWOLogin', "disabled");
	}


	if(isset($_POST['use_tor']))
	{
		if(!read_ini_file('Tor') == "enabled")
		{
			write_ini_file('Tor', "enabled");
			$need_miner_restart = true;
			system("sudo /etc/PIckaxe/TOR.sh enable ; sleep 10 ;");
		}
	}
	else
	{
		if(!read_ini_file('Tor') == "enabled")
		{
			$need_miner_restart = true;
			write_ini_file('Tor', "disabled");
			system("sudo /etc/PIckaxe/TOR.sh disable; sleep 10 ;");
		}
	}


	if($_POST["web_pass_1"] ==  $_POST["web_pass_2"] && $_POST["web_pass_1"] != "")
	{
		$hashed_pass = setPassword($_POST["web_pass_1"]);
		setPasswordCookie($hashed_pass);
	}


	setcookie( "control_message", "Settings Saved", 0, "/");
	setcookie( "control_message_class", "data_span_good", 0, "/");


	if($need_miner_restart)
	{
		system("sudo /etc/init.d/bfgminer restart ");
		sleep(10);
	}

	header('Location: /index.php');
}

?>
