<?php

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

error_reporting(E_ALL);
setlocale(LC_ALL, 'de_DE');

ini_set("session.name", "TR_SESSION");
ini_set("url_rewriter.tags", "");
ini_set("session.use_only_cookies", "1");
ini_set("session.cookie_path", "/");
ini_set("session.cookie_domain", "");

session_cache_limiter("private");
session_cache_expire(300);
session_start();

ob_start();
ob_clean();

$Controller =& new Controller();

$Controller->switch_actions();

$_tpl = "index.html";
$_out = "";

$_SESSION["HTML"]->import();
$_SESSION["HTML"]->preload();

// TODO: admin and user specific
if ($_SESSION["CLIENT"]->is_user())
	{
		switch ($_SESSION["_PageID.current"])
			{
			case "setup":
				$_tpl = "setup.html";
				break;
			case "stats":
				$_tpl = "stats.html";
				break;
			case "home":
			default:
				$_tpl = "index.html";
				break;
			}
		$_SESSION["HTML"]->load($_tpl);
		$menu  = '<ul id="main_menu">';
		$menu .= $_SESSION["HTML"]->menu_get_entry("Abmelden", "./?action=logout", "Q");
		$menu .= $_SESSION["HTML"]->menu_get_entry("&Uuml;bersicht", "./?page=home", "H", ($_SESSION["_PageID.current"]=="home"));
		$menu .= $_SESSION["HTML"]->menu_get_entry("Einstellungen", "./?page=setup", "P", ($_SESSION["_PageID.current"]=="setup"));
		$menu .= $_SESSION["HTML"]->menu_get_entry("Statistiken", "./?page=stats", "S", ($_SESSION["_PageID.current"]=="stats"));
		$menu .= '</ul>';
		
		$book = $Controller->show_last_bookings();
		
		$_SESSION["HTML"]->assign($_tpl, "<!--MId-->", $_SESSION["_UserData"]["mid"]);
		$_SESSION["HTML"]->assign($_tpl, "<!--Vornamen-->", $_SESSION["_UserData"]["firstname"]);
		$_SESSION["HTML"]->assign($_tpl, "<!--Namen-->", $_SESSION["_UserData"]["lastname"]);
		$_SESSION["HTML"]->assign($_tpl, "<!--LoginNamen-->", $_SESSION["_UserData"]["email"]);
		$_SESSION["HTML"]->assign($_tpl, "<!--ClientIP-->", $_SESSION["_UserData"]["ip"]);
		$_SESSION["HTML"]->assign($_tpl, "<!--GroupNAME-->", $_SESSION["_UserData"]["groupname"]);
		$_SESSION["HTML"]->assign($_tpl, "<!--MENU-->", $menu);
		$_SESSION["HTML"]->assign($_tpl, "<!--HISTORY_DATA-->",$book);
		$_SESSION["HTML"]->assign($_tpl, "<!--OUTPUT-->",$_out);
		$_SESSION["HTML"]->output($_tpl);
	}
else
	{
		$_SESSION["HTML"]->load("login.html");
		$_SESSION["HTML"]->output("login.html");
	}

?>