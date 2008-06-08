<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Controller:: class implements the main-controller for
 * the sytem and takes care of all registered session-objects
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 */
class Controller
	{
		/**
		 * constructor loads global config file and initializes
		 * default session variables, register session-objects if
		 * not set, reset needed timers
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct()
		{
			// load global config file or die
			if (!file_exists("./config.controller.php"))
				{
					die("config file 'config.controller.php' not found!");
				}
			else
				{
					__session_start();
					include("./config.controller.php");
					$_SESSION["_Action"]         = "";
					$_SESSION["_Errors"]         = "";
					$_SESSION["_PageID.current"] = "";
					$_SESSION["_PageID.last"]    = "";
					$_SESSION["_SqlType"]        = (isset($_SETTINGS["Main"][0])) ? $_SETTINGS["Main"][0] : false;
					$_SESSION["_TimeOut"]        = (isset($_SETTINGS["Main"][1])) ? $_SETTINGS["Main"][1] : 300;
					$_SESSION["_Cookies"]        = (isset($_SETTINGS["Main"][2])) ? $_SETTINGS["Main"][2] : false;
					$_SESSION["_TplSqlTable"]    = (isset($_SETTINGS["Template"][3])) ? $_SETTINGS["Template"][3] : false;
				}
				
			// try to register needed objects for current session or die
			try
				{
					self::register("Timer",array("system runtime",true),"TIMER.PHP");
					$_SESSION["TIMER.PHP"]->reset();
					$_SESSION["TIMER.PHP"]->start();
					
					self::register("MySql", $_SETTINGS["MySql"], "MYSQL");
					$_SESSION["TIMER.MYSQL"]->reset();
					
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
		 * register new session-object, if not allready present
		 *
		 * @param 	string		name of object to create
		 * @param 	array		specific parameters for constructor
		 * @param 	string		(optional) alias of object
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
			else if (! isset($_SESSION[$classname]) && $alias == "")
				{
					$_SESSION[$classname] =& new $classname($parameters);
				}
		}
		
		/**
		 * unregister
		 *
		 * @param 	string		name of object to delete
		 * @param 	string		(optional) alias of object
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
		 * to string returns var_dump of itself
		 *
		 * @return	string		var_dump( $this )
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __toString()
		{
			return print_r($_SESSION).";\n";
		}
		
		/**
		 * returns array of all tables in database
		 *
		 * @param 	int		(optional) number of lines to query
		 *
		 * @return	array	array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_last_bookings($limit=10)
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
		 * manage page actions and execute state-dependend methods
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function switch_actions()
		{
			// clean previous errors
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
				
			// extend session of user, if valid
			$_SESSION["CLIENT"]->extend();
			
			// try to execute session actions
			try
				{
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
					$_SESSION["_Errors"] .= $e->getMessage();
				}
		}
		
	}
?>