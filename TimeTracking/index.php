<?php

error_reporting(E_ALL);
setlocale(LC_ALL, 'de_DE');

ob_start();
ob_clean();

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
			Core::set_error("unable to load class '${class_name}'! include file '${class_file}' not found! ");
		}
}


$Core =& new Core("MsSql");

$_tpl = "index.html";
$_out = "";


// TODO: admin and user specific
if ($Core->ObjACC()->is_user())
	{
		switch ($_SESSION["PageID_NOW"])
			{
			case "setup":
				$_tpl = "setup.html";
				break;
			case "stats":
				$_tpl = "stats.html";
				break;
			case "dump":
				$_tpl = "dump.html";
				foreach($Core->show_tables() as $table_name)
				{
					$_out .= $Core->html_table($table_name)."<br/>";
				}
				break;
			case "home":
			default:
				$Core->show_last_bookings(10);
				$_tpl = "index.html";
				break;
			}
		$Core->ObjTPL()->load($_tpl);
		$menu  = '<ul id="main_menu">';
		$menu .= $Core->ObjTPL()->menu_get_entry("Abmelden", "./?action=logout", "Q");
		$menu .= $Core->ObjTPL()->menu_get_entry("&Uuml;bersicht", "./?page=home", "H", ($_SESSION["PageID_NOW"]=="home"));
		$menu .= $Core->ObjTPL()->menu_get_entry("Einstellungen", "./?page=setup", "P", ($_SESSION["PageID_NOW"]=="setup"));
		$menu .= $Core->ObjTPL()->menu_get_entry("Statistiken", "./?page=stats", "S", ($_SESSION["PageID_NOW"]=="stats"));
		$menu .= $Core->ObjTPL()->menu_get_entry("Datenbank", "./?page=dump", "D", ($_SESSION["PageID_NOW"]=="dump"));
		$menu .= '</ul>';
		
		$book = $Core->show_last_bookings();
		
		$Core->ObjTPL()->assign($_tpl, "<!--MId-->", $_SESSION["UserID"]);
		$Core->ObjTPL()->assign($_tpl, "<!--Vornamen-->", $_SESSION["Vornamen"]);
		$Core->ObjTPL()->assign($_tpl, "<!--Namen-->", $_SESSION["Namen"]);
		$Core->ObjTPL()->assign($_tpl, "<!--LoginNamen-->", $_SESSION["LoginNamen"]);
		$Core->ObjTPL()->assign($_tpl, "<!--ClientIP-->", $_SESSION["ClientIP"]);
		$Core->ObjTPL()->assign($_tpl, "<!--GroupNAME-->", $_SESSION["GroupNAME"]);
		$Core->ObjTPL()->assign($_tpl, "<!--MENU-->", $menu);
		$Core->ObjTPL()->assign($_tpl, "<!--HISTORY_DATA-->",$book);
		$Core->ObjTPL()->assign($_tpl, "<!--OUTPUT-->",$_out);
		$Core->ObjTPL()->output($_tpl);
	}
else
	{
		$Core->ObjTPL()->load("login.html");
		$Core->ObjTPL()->output("login.html");
	}


?>