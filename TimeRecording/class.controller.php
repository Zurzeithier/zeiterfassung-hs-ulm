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
			self::reinit();
		}
		
		/**
		 * reinit loads global config file and initializes
		 * default session variables, register session-objects
		 * if not set, reset needed timers
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function reinit()
		{
			// load global config file or die
			if (!file_exists("./config.controller.php"))
				{
					die("config file 'config.controller.php' not found!");
				}
			else if (!__check_syntax("./config.controller.php"))
				{
					die("config file 'config.controller.php' is invalid!");
				}
			else
				{
					__session_start();
					include("./config.controller.php");
					$_SESSION["_Action"]         = "";
					$_SESSION["_Errors"]         = "";
					$_SESSION["_ErrNo"]          = "";
					$_SESSION["_PageID.current"] = "";
					$_SESSION["_PageID.last"]    = "";
					$_SESSION["_SqlType"]        = (isset($_SETTINGS["Main"][0])) ? $_SETTINGS["Main"][0] : false;
					$_SESSION["_TimeOut"]        = (isset($_SETTINGS["Main"][1])) ? $_SETTINGS["Main"][1] : 300;
					$_SESSION["_Cookies"]        = (isset($_SETTINGS["Main"][2])) ? $_SETTINGS["Main"][2] : false;
					$_SESSION["_Webmaster"]      = (isset($_SETTINGS["Main"][3])) ? $_SETTINGS["Main"][3] : "webmaster@localhost";
					$_SESSION["_Domain"]         = (isset($_SETTINGS["Main"][4])) ? $_SETTINGS["Main"][4] : "localhost";
					$_SESSION["_TplSqlTable"]    = (isset($_SETTINGS["Template"][3])) ? $_SETTINGS["Template"][3] : false;
				}
				
			// try to register needed objects for current session or die
			try
				{
					self::register("Timer",array("Controller",true),"TIMER.PHP");
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
		 * returns sum array of bookings (week, month - current and last)
		 *
		 * @param	string	type of range to sum from
		 * @param 	int		offset of first entry
		 * @param 	int		number of entries returned
		 * 
		 * @return	array	array of all sums
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get_booking_sums( $range = "DAY", $offset = 0, $limit = 1 )
		{
			$group = "";
			$start = "";
			$stop  = "";
			$redat = "";
			switch( strtoupper( $range ) )
			{
				case "YEAR":
					$redat = "DATE_SUB( NOW(), INTERVAL $offset YEAR )";
					$start = "DATE_FORMAT( $redat, '01.01.%Y' )";
					$stop  = "DATE_FORMAT( $redat, '31.12.%Y' )";
					$group = "%Y";
					$range = "SameYear";
					break;
				case "MONTH":
					$redat = "DATE_SUB( NOW(), INTERVAL $offset MONTH )";
					$start = "DATE_FORMAT( $redat, '%Y-%m-01' )";
					$stop  = "LAST_DAY( $redat )";
					$group = "%m";
					$range = "SameMonth";
					break;
				case "WEEK":
					$redat = "DATE_SUB( NOW(), INTERVAL $offset WEEK )";
					$start = "DATE_SUB( $redat, INTERVAL DAYOFWEEK( $redat ) - 2 DAY )";
					$stop  = "DATE_SUB( $redat, INTERVAL DAYOFWEEK( $redat ) - 8 DAY )";
					$group = "%u";
					$range = "SameWeek";
					break;
				case "DAY":
				default:
					$redat = "DATE_SUB( NOW(), INTERVAL $offset DAY )";
					$start = "DATE_SUB( NOW(), INTERVAL $offset DAY )";
					$stop  = "DATE_SUB( NOW(), INTERVAL $offset DAY )";
					$group = "%j";
					$range = "SameDay";
					break;
			}
			
			$query  = "SELECT DATE_FORMAT( $redat, '$group' ) AS $range, ";
			$query .= "DATE_FORMAT( $start , '%d.%m.%Y 00:00:00' ) AS Von, ";
			$query .= "DATE_FORMAT( $stop , '%d.%m.%Y 23:59:59' ) AS Bis, ";
			$query .= "SEC_TO_TIME( IFNULL( SUM( UNIX_TIMESTAMP( stamp_2 ) - UNIX_TIMESTAMP( stamp_1 ) ), 0 ) ) as Stunden, ";
			$query .= "IF( bookid = NULL, 0, COUNT( bookid ) ) AS Besuche FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' AND ";
			$query .= "DATE( stamp_1 ) >= DATE( $start ) AND DATE( stamp_1 ) <= DATE( $stop ) ";
			
			$array = $_SESSION[$_SESSION["_SqlType"]]->query_all($query);
			
			// future use, if multiple row read implemented
			if ( $limit > 1 )
			{
				return $array;
			}
			
			return $array[0];
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
		public function show_last_bookings($limit=9)
		{
			$query  = "SELECT DATE_FORMAT( stamp_1, '%d.%m.%Y' ) AS Datum, ";
			$query .= "DATE_FORMAT( stamp_1, '%T Uhr' ) AS Gekommen, ";
			$query .= "DATE_FORMAT( stamp_2, '%T Uhr' ) AS Gegangen, ";
			$query .= "SEC_TO_TIME( UNIX_TIMESTAMP( stamp_2 ) - UNIX_TIMESTAMP( stamp_1 ) ) AS Anwesend ";
			$query .= "FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' ";
			$query .= "ORDER BY stamp_1 DESC LIMIT 0,$limit";
			
			return $this->query2table($query,"last_bookings");
		}
		
		/**
		 * generate html user table
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_user_table($limit=8)
		{
			$query  = "SELECT u.mid AS MitarbeiterID, u.email AS Email, ";
			$query .= "u.firstname AS Vorname, u.lastname AS Nachname, g.groupname AS Gruppe ";
			//$query .= "IF( b.stamp_2 = NULL, 'Nein', 'Ja' ) AS Anwesend ";
			$query .= "FROM tr_users u ";
			//$query .= "LEFT JOIN tr_bookings b USING ( mid ) ";
			$query .= "LEFT JOIN tr_groups g USING ( gid ) ";
			$query .= "ORDER BY mid ASC LIMIT 0,$limit";
			
			return $this->query2table($query,"user_table");
		}
		
		
		/**
		 * private query to html table converter
		 * 
		 * @param   string		sql query to get rows from
		 * @param 	string		id-tag (css stylesheet)
		 *
		 * @access  private
		 *
		 * @author  patrick.kracht
		 */
		private function query2table($query,$id)
		{
			$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
			$first  = true;
			$dump   = "<table id=\"$id\">";
			while ($row = $_SESSION[$_SESSION["_SqlType"]]->fetch_array($result))
				{
					// append only keys on first line
					if ($first)
						{
							$dump .= "<tr><td><b>";
							$dump .= implode("</b></td><td><b>", array_keys($row));
							$dump .= "</b></td></tr>";
							$first = false;
						}
					// append data rows
					$dump .= "<tr><td>";
					$dump .= implode("</td><td>", $row);
					$dump .= "</td></tr>";	
				}
			$_SESSION[$_SESSION["_SqlType"]]->free_result($result);
			if ($first) $dump .= "<tr><td>keine Einstr&auml;ge gefunden...</td></tr>";
			// close table
			$dump  .= "</table>";
			return $dump;
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
					$_SESSION["_ErrNo"]   = $e->getCode();
				}
		}
		
	}
?>