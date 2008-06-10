<?php

/**
 * start a valid session with name
 *
 * @param   string	session name
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
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
			if (preg_match('/^[a-z0-9]{32}$/', $sid))
			{
				session_id($sid);
			}
		}
		
	// start session now and set cookie
	session_start();
	$_COOKIE[$name] = session_id();
}

/**
 * autoload class files
 *
 * @param   string	class name
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
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

/**
 * check syntax of php file (config file)
 *
 * @param   string	filename to check
 * 
 * @return  boolean	true: valid syntax, false: errors
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
function __check_syntax($filename)
{
	$source = file_get_contents($filename);
	ob_start();
	$eval = @eval('?>'.$source);
	$cont = ob_get_contents();
	ob_end_clean();
	return $eval === NULL ? true : false;
}

/**
 * convert php.ini values bytes to KB, MB, GB... 
 *
 * @param   int     value in bytes
 * @param   int     precision
 * @param   int     shifting amount
 * 
 * @return  string  formatted bytes
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function __from_bytes( $val, $prec = 0, $cnt = 0 )
 {
  $ext = array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
  $val = trim($val);
  if(!is_numeric($val)) return $val;
  else while($val>=1024){
   $val/=1024.0;
   $cnt++;
  }
  return number_format( $val, $prec, ",", "." )." ".$ext[$cnt];
 }

?>