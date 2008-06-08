<?php

// magic session setup function
function __session_start($name="sid")
{
	// do some init stuff here
	error_reporting(E_ALL);
	setlocale(LC_ALL, 'de_DE');
	
	// create clean output buffer
	ob_start();
	ob_clean();
	
	// configure and start session
	session_cache_limiter("private_no_expire");
	session_cache_expire(60);
	
	// set ini parameters for session (force use of cookies)
	ini_set("session.use_cookies", "1");
	ini_set("session.use_only_cookies", "1");
	ini_set("session.use_trans_sid", "");
	ini_set("url_rewriter.tags", "");
	ini_set("session.name", $name);
	ini_set("session.cookie_path", "/");
	ini_set("session.cookie_domain", "");
	
	// if no session cookie exists, try to switch to cookieless mode
	if (!isset($_COOKIE[$name]))
		{
			if (empty($_SERVER["QUERY_STRING"]))
				{
					session_start();
					$_COOKIE[$name] = session_id();
					session_write_close();
					header("Location: ./?page=restart");
					exit();
				}
			ini_set("session.use_cookies", "");
			ini_set("session.use_only_cookies", "");
			ini_set("session.use_trans_sid", "1");
			$sid = isset($_POST[$name])?$_POST[$name]:(isset($_GET[$name])?$_GET[$name]:"");
			if (preg_match('/^[a-z0-9]{32}$/', $sid)) session_id($sid);
		}
		
	// start session now
	session_start();
	$_COOKIE[$name] = session_id();
}

// magic autoload function for dynamically loading class files
function __autoload($class_name)
{
	$class_name = strtolower($class_name);
	$class_file = "./class.${class_name}.php";
	if (file_exists($class_file))
		{
			require_once $class_file;
		}
	else
		{
			die("unable to load class '${class_name}'! include file '${class_file}' not found!");
		}
}

?>