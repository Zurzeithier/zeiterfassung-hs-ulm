<?php

// fetch global functions and start session init
require_once "./functions.php";


// create controller and check for actions to do
$Controller =& new Controller();
$Controller->switch_actions();


// temporary update and preload all templates (for development purpose)
$_SESSION["HTML"]->import();
$_SESSION["HTML"]->preload();


// ************************* pages for users ***********************
if ($_SESSION["CLIENT"]->is_user())
	{
		// create top menu
		$_SESSION["HTML"]->menu_insert_entry("Abmelden", "./?action=logout", "Q");
		$_SESSION["HTML"]->menu_insert_spacer();
		$_SESSION["HTML"]->menu_insert_entry("&Uuml;bersicht", "./?page=home", "H", ($_SESSION["_PageID.current"]=="home"));
		$_SESSION["HTML"]->menu_insert_entry("Mitarbeiter", "./?page=users", "E", ($_SESSION["_PageID.current"]=="users"));
		$_SESSION["HTML"]->menu_insert_entry("Einstellungen", "./?page=setup", "E", ($_SESSION["_PageID.current"]=="setup"));
		$_SESSION["HTML"]->menu_insert_spacer();
		$_SESSION["HTML"]->menu_insert_entry("[<u>KOMMEN</u>]", "./?action=book&amp;id=1", "K");
		$_SESSION["HTML"]->menu_insert_entry("[<u>GEHEN</u>]", "./?action=book&amp;id=0", "G");
		
		// call page specific methods
		switch ($_SESSION["_PageID.current"])
			{
			case "users":
				// load template 
				$_SESSION["HTML"]->load("users.html");
				
				// list users
				$users = $Controller->show_user_table();
				$_SESSION["HTML"]->assign("users.html", "<!--USER_TABLE-->",$users);
				
				//TODO MORE
				
				// send page to browser
				$_SESSION["HTML"]->output("users.html");
				break;
			case "setup":
				// load template 
				$_SESSION["HTML"]->load("setup.html");
				
				//TODO ALL
				
				// send page to browser
				$_SESSION["HTML"]->output("setup.html");
				break;
			case "home":
			default:
				// load template 
				$_SESSION["HTML"]->load("index.html");
				
				$_SESSION["HTML"]->assign("index.html", "<!--MID-->", $_SESSION["_UserData"]["mid"]);
				$_SESSION["HTML"]->assign("index.html", "<!--FIRST_NAME-->", $_SESSION["_UserData"]["firstname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--LAST_NAME-->", $_SESSION["_UserData"]["lastname"]);
				$_SESSION["HTML"]->assign("index.html", "<!--LOGIN_NAME-->", $_SESSION["_UserData"]["email"]);
				$_SESSION["HTML"]->assign("index.html", "<!--IP-->", $_SESSION["_UserData"]["ip"]);
				$_SESSION["HTML"]->assign("index.html", "<!--GROUP_NAME-->", $_SESSION["_UserData"]["groupname"]);
				
				// list last bookings
				$book = $Controller->show_last_bookings();
				$_SESSION["HTML"]->assign("index.html", "<!--HISTORY_DATA-->",$book);
				
				// summary calculation and assignment
				$array = $Controller->get_booking_sums( "WEEK" );
				$_SESSION["HTML"]->assign("index.html", "<!--SUM_WEEK_NOW-->",$array["Stunden"]);
				$array = $Controller->get_booking_sums( "MONTH" );
				$_SESSION["HTML"]->assign("index.html", "<!--SUM_MONTH_NOW-->",$array["Stunden"]);
				$array = $Controller->get_booking_sums( "WEEK", 1 );
				$_SESSION["HTML"]->assign("index.html", "<!--SUM_WEEK_LAST-->",$array["Stunden"]);
				$array = $Controller->get_booking_sums( "MONTH", 1 );
				$_SESSION["HTML"]->assign("index.html", "<!--SUM_MONTH_LAST-->",$array["Stunden"]);
				
				// send page to browser
				$_SESSION["HTML"]->output("index.html");
				break;
			}
	}
// ***************** page for guests (force login) *****************
else
	{
		if ( $_SESSION["_PageID.current"] == "passwd" )
		{
			// load template 
			$_SESSION["HTML"]->load("passwd.html");
			// send page to browser
			$_SESSION["HTML"]->output("passwd.html");
		}
		else
		{
			// load template 
			$_SESSION["HTML"]->load("login.html");
			// send page to browser
			$_SESSION["HTML"]->output("login.html");
		}
	}


?>