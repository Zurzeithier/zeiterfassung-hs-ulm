<?php

// do some init stuff here
error_reporting(E_ALL);
setlocale(LC_ALL, 'de_DE');


// set ini parameters for session (force use of cookies)
ini_set("url_rewriter.tags", "");
ini_set("session.name", "TR_SESSION");
ini_set("session.use_only_cookies", "1");
ini_set("session.cookie_path", "/");
ini_set("session.cookie_domain", "");


// configure and start session
session_cache_limiter("private");
session_cache_expire(300);
session_start();


// create clean output buffer
ob_start();
ob_clean();


// die, if cookies are disabled at login
if (count($_SESSION) == 0 && isset($_POST["action"]))
	{
		die("necessary cookies for login are disabled!");
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
			die("unable to load class '${class_name}'! include file '${class_file}' not found! ");
		}
}


// create controller and check for actions to do
$Controller =& new Controller();
$Controller->switch_actions();


// temporary update and preload all templates (for development purpose)
$_SESSION["HTML"]->import();
$_SESSION["HTML"]->preload();


$output = "";


// ************************* pages for admin users ***************************
if ($_SESSION["CLIENT"]->is_admin())
	{
		// create top menu
		$menu  = '<ul id="main_menu">';
		$menu .= $_SESSION["HTML"]->menu_get_entry("Abmelden", "./?action=logout", "Q");
		$menu .= $_SESSION["HTML"]->menu_get_entry("&Uuml;bersicht", "./?page=home", "H", ($_SESSION["_PageID.current"]=="home"));
		$menu .= $_SESSION["HTML"]->menu_get_entry("Einstellungen", "./?page=setup", "P", ($_SESSION["_PageID.current"]=="setup"));
		$menu .= $_SESSION["HTML"]->menu_get_entry("Statistiken", "./?page=stats", "S", ($_SESSION["_PageID.current"]=="stats"));
		$menu .= '</ul>';
		
		// call page specific methods
		switch ($_SESSION["_PageID.current"])
			{
			case "setup":
				$_SESSION["HTML"]->load("setup.html");
				$_SESSION["HTML"]->assign("setup.html", "<!--MENU-->", $menu);
				$_SESSION["HTML"]->assign("setup.html", "<!--OUTPUT-->",$_out);
				$_SESSION["HTML"]->output("setup.html");
				break;
			case "stats":
				$_SESSION["HTML"]->load("stats.html");
				$_SESSION["HTML"]->assign("stats.html", "<!--MENU-->", $menu);
				$_SESSION["HTML"]->assign("stats.html", "<!--OUTPUT-->",$_out);
				$_SESSION["HTML"]->output("stats.html");
				break;
			case "home":
			default:
				$book = $Controller->show_last_bookings();
				$_SESSION["HTML"]->load("index.html");
				$_SESSION["HTML"]->assign("index.html", "<!--MID-->", $_SESSION["_UserData"]["mid"]);
				$_SESSION["HTML"]->assign("index.html", "<!--FIRST_NAME-->", $_SESSION["_UserData"]["firstname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--LAST_NAME-->", $_SESSION["_UserData"]["lastname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--LOGIN_NAME-->", $_SESSION["_UserData"]["email"]);
				$_SESSION["HTML"]->assign("index.html", "<!--IP-->", $_SESSION["_UserData"]["ip"]);
				$_SESSION["HTML"]->assign("index.html", "<!--GROUP_NAME-->", $_SESSION["_UserData"]["groupname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--HISTORY_DATA-->",$book);
				$_SESSION["HTML"]->assign("index.html", "<!--MENU-->", $menu);
				$_SESSION["HTML"]->assign("index.html", "<!--OUTPUT-->",$output);
				$_SESSION["HTML"]->output("index.html");
				break;
			}
	}
// ************************* pages for a none admins *************************
else if ($_SESSION["CLIENT"]->is_user())
	{
		// create top menu
		$menu  = '<ul id="main_menu">';
		$menu .= $_SESSION["HTML"]->menu_get_entry("Abmelden", "./?action=logout", "Q");
		$menu .= $_SESSION["HTML"]->menu_get_entry("&Uuml;bersicht", "./?page=home", "H", ($_SESSION["_PageID.current"]=="home"));
		$menu .= $_SESSION["HTML"]->menu_get_entry("Einstellungen", "./?page=setup", "P", ($_SESSION["_PageID.current"]=="setup"));
		$menu .= '</ul>';
		
		// call page specific methods
		switch ($_SESSION["_PageID.current"])
			{
			case "setup":
				$_SESSION["HTML"]->load("setup.html");
				$_SESSION["HTML"]->assign("setup.html", "<!--MENU-->", $menu);
				$_SESSION["HTML"]->assign("setup.html", "<!--OUTPUT-->",$output);
				$_SESSION["HTML"]->output("setup.html");
				break;
			case "home":
			default:
				$book = $Controller->show_last_bookings();
				$_SESSION["HTML"]->load("index.html");
				$_SESSION["HTML"]->assign("index.html", "<!--MID-->", $_SESSION["_UserData"]["mid"]);
				$_SESSION["HTML"]->assign("index.html", "<!--FIRST_NAME-->", $_SESSION["_UserData"]["firstname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--LAST_NAME-->", $_SESSION["_UserData"]["lastname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--LOGIN_NAME-->", $_SESSION["_UserData"]["email"]);
				$_SESSION["HTML"]->assign("index.html", "<!--IP-->", $_SESSION["_UserData"]["ip"]);
				$_SESSION["HTML"]->assign("index.html", "<!--GROUP_NAME-->", $_SESSION["_UserData"]["groupname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--HISTORY_DATA-->",$book);
				$_SESSION["HTML"]->assign("index.html", "<!--MENU-->", $menu);
				$_SESSION["HTML"]->assign("index.html", "<!--OUTPUT-->",$output);
				$_SESSION["HTML"]->output("index.html");
				break;
			}
	}
// ************************* page for guests (force login) *******************
else
	{
		$_SESSION["HTML"]->load("login.html");
		$_SESSION["HTML"]->output("login.html");
	}


?>