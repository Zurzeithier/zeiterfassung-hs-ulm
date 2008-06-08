<?php

// fetch global functions and start session init
require_once "./functions.php";

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
				$book = $Controller->show_last_bookings(15);
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
				$book = $Controller->show_last_bookings(15);
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