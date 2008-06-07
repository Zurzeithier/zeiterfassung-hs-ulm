<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Controller:: class
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 */
class Controller
	{
		/**
		 * constructor
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct()
		{
			if (file_exists("./config.controller.php"))
				{
					include("./config.controller.php");
					$_SESSION["_TplSqlTable"] = (isset($_SETTINGS["Template"][3])) ? $_SETTINGS["Template"][3] : false;
					$_SESSION["_SqlType"]     = (isset($_SETTINGS["Main"][0])) ? $_SETTINGS["Main"][0] : false;
					$_SESSION["_TimeOut"]     = (isset($_SETTINGS["Main"][1])) ? $_SETTINGS["Main"][1] : 300;
				}
			else
				{
					die("config file 'config.controller.php' not found!");
				}
				
			try
				{
					self::register("Timer",array("controller runtime",true),"TIMER.PHP");
					$_SESSION["TIMER.PHP"]->reset();
					$_SESSION["TIMER.PHP"]->start();
					
					self::register("MySql", $_SETTINGS["MySql"], "MYSQL");
					$_SESSION["TIMER.MYSQL"]->reset();
					
					self::register("MsSql", $_SETTINGS["MsSql"], "MSSQL");
					$_SESSION["TIMER.MSSQL"]->reset();
					
					self::register("Template", $_SETTINGS["Template"], "HTML");
					self::register("Session",  array(), "CLIENT");
				}
			catch (Exception $e)
				{
					die($e->getMessage());
				}
		}
		
		/**
		 * destructor
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __destruct()
		{
		}
		
		/**
		 * register
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function register($classname, $parameters = array(), $alias = "")
		{
			if ($alias != "" && ! isset($_SESSION[$alias]))
				{
					$_SESSION[$alias] =& new $classname($parameters);
				}
			else if (! isset($_SESSION[$classname]))
				{
					$_SESSION[$classname] =& new $classname($parameters);
				}
		}
		
		/**
		 * unregister
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function unregister($classname, $alias = "")
		{
			if (isset($_SESSION[$classname]))
				{
					unset($_SESSION[$classname]);
				}
			if ($alias != "" && isset($_SESSION[$alias]))
				{
					unset($_SESSION[$alias]);
				}
		}
		
		/**
		 * to string
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __toString()
		{
			return var_dump($this).";\n";
		}
		
		/**
		 * returns array of all tables in database
		 *
		 * @param 	string	(optional) type of sql to query from [MySql|MsSql]
		 *
		 * @return	array	array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_last_bookings($limit=15)
		{
			$return = "";
			$query  = "SELECT s.symbolname, s.symid, DATE_FORMAT( b.stamp, '%d.%m.%Y %H:%i:%s') AS Datum ";
			$query .= "FROM tr_bookings b LEFT JOIN tr_symbols s ";
			$query .= "USING ( symid ) WHERE b.mid = '".$_SESSION["_UserData"]["mid"]."' ";
			$query .= "ORDER BY b.stamp DESC LIMIT 0,$limit";
			$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
			while ($row = $_SESSION[$_SESSION["_SqlType"]]->fetch_array($result))
				{
					$return .= '<div class="win_attr">'.$row["symbolname"].'</div><div class="win_value">'.$row["Datum"].'</div><br clear="all"/>';
				}
			return $return;
		}
		
		/**
		 * execute action dependend methods
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function switch_actions()
		{
			$_SESSION["_Errors"] = "";
			
			// save post or get value from "page"
			if (isset($_POST["page"]))
				{
					$_SESSION["_PageID.last"] = isset($_SESSION["_PageID.current"]) ? $_SESSION["_PageID.current"] : "home";
					$_SESSION["_PageID.current"] = $_POST["page"];
				}
			else if (isset($_GET["page"]))
				{
					$_SESSION["_PageID.last"] = isset($_SESSION["_PageID.current"]) ? $_SESSION["_PageID.current"] : "home";
					$_SESSION["_PageID.current"] = $_GET["page"];
				}
			else
				{
					$_SESSION["_PageID.current"] = "home";
					$_SESSION["_PageID.last"] = "home";
				}
				
			// save post or get value from "action"
			if (isset($_POST["action"]))
				{
					$_SESSION["_Action"] = $_POST["action"];
				}
			else if (isset($_GET["action"]))
				{
					$_SESSION["_Action"] = $_GET["action"];
				}
			else
				{
					$_SESSION["_Action"] = "";
				}
				
			$_SESSION["CLIENT"]->extend();
			
			try
				{
					// do session actions
					switch ($_SESSION["_Action"])
						{
						case "login":
							$_SESSION["CLIENT"]->login();
							break;
						case "logout":
							$_SESSION["CLIENT"]->logout();
							break;
						case "book":
							$_SESSION["CLIENT"]->book();
							break;
						case "update":
							$_SESSION["CLIENT"]->update();
							break;
						case "delete":
							$_SESSION["CLIENT"]->delete();
							break;
						case "create":
							$_SESSION["CLIENT"]->create();
							break;
						case "passwd":
							$_SESSION["CLIENT"]->passwd();
							break;
						case "import":
							echo $_SESSION["HTML"]->import();
							break;
						}
				}
			catch (Exception $e)
				{
					$_SESSION["_Errors"] = $e->getMessage();
				}
		}
		
	}
?>