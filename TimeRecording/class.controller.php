<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Controller:: class implements the main-controller for
 * the sytem and takes care of all registered session-objects
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __construct()
		{
			$this->reinit();
		}
		
		/**
		 * reinit loads global config file and initializes
		 * default session variables, register session-objects
		 * if not set, reset needed timers
		 *
		 * @access  private
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function reinit()
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
					$_SESSION["_MaxWorkingH"]    = (isset($_SETTINGS["Main"][5])) ? $_SETTINGS["Main"][5] : 8;
					$_SESSION["_TplSqlTable"]    = (isset($_SETTINGS["Template"][3])) ? $_SETTINGS["Template"][3] : false;
				}
				
			// try to register needed objects for current session or die
			try
				{
					$this->register("Memory",array(),"MEMORY");
					
					$this->register("Timer",array("Controller",true),"TIMER.PHP");
					$_SESSION["TIMER.PHP"]->reset();
					$_SESSION["TIMER.PHP"]->start();
					
					$this->register("MySql", $_SETTINGS["MySql"], "MYSQL");
					$_SESSION["TIMER.MYSQL"]->reset();
					
					$this->register("Template", $_SETTINGS["Template"], "HTML");
					$this->register("Session",  array(), "CLIENT");
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
		 * @author  patrick.kracht, thorsten.moll
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
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function register($classname, $parameters = array(), $alias = "")
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
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function unregister($classname, $alias = "")
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
		 * to string returns null
		 *
		 * @return	null
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __toString()
		{
			return;
		}
		
		/**
		 * creates user menu or not
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function create_menu()
		{
			if (! $_SESSION["CLIENT"]->is_user())
				{
					return;
				}
			$_SESSION["HTML"]->menu_insert_entry("Abmelden", "./?action=logout", "Q");
			$_SESSION["HTML"]->menu_insert_spacer();
			$_SESSION["HTML"]->menu_insert_entry("&Uuml;bersicht", "./?page=home", "H", ($_SESSION["_PageID.current"]=="home"));
			$_SESSION["HTML"]->menu_insert_entry("Mitarbeiter", "./?page=users", "E", ($_SESSION["_PageID.current"]=="users"));
			$_SESSION["HTML"]->menu_insert_entry("Einstellungen", "./?page=setup", "E", ($_SESSION["_PageID.current"]=="setup"));
			$_SESSION["HTML"]->menu_insert_spacer();
			$_SESSION["HTML"]->menu_insert_entry("[<u>KOMMEN</u>]", "./?action=book&amp;id=1", "K");
			$_SESSION["HTML"]->menu_insert_entry("[<u>GEHEN</u>]", "./?action=book&amp;id=0", "G");
		}
		
		/**
		 * creates user specific page
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function create_page()
		{
			if (! $_SESSION["CLIENT"]->is_user())
				{
					if ($_SESSION["_PageID.current"] == "passwd")
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
			else
				{
					switch ($_SESSION["_PageID.current"])
						{
						case "users":
							$this->show_user_table();
							$_SESSION["HTML"]->output("users.html");
							break;
						case "setup":
							//TODO ALL
							$_SESSION["HTML"]->output("setup.html");
							break;
						case "home":
						default:
							$_SESSION["HTML"]->assign("index.html", "<!--MID-->", $_SESSION["_UserData"]["mid"]);
							$_SESSION["HTML"]->assign("index.html", "<!--FIRST_NAME-->", $_SESSION["_UserData"]["firstname"]);
							$_SESSION["HTML"]->assign("index.html", "<!--LAST_NAME-->", $_SESSION["_UserData"]["lastname"]);
							$_SESSION["HTML"]->assign("index.html", "<!--LOGIN_NAME-->", $_SESSION["_UserData"]["email"]);
							$_SESSION["HTML"]->assign("index.html", "<!--IP-->", $_SESSION["_UserData"]["ip"]);
							$_SESSION["HTML"]->assign("index.html", "<!--GROUP_NAME-->", $_SESSION["_UserData"]["groupname"]);
							
							// list last bookings
							$this->show_last_bookings();
							
							// summary calculation and assignment for current week and month
							$array = $this->get_booking_sums("WEEK");
							$_SESSION["HTML"]->assign("index.html", "<!--SUM_WEEK_NOW-->",$array["Stunden"]);
							$_SESSION["HTML"]->assign("index.html", "{{SUM_WEEK_NOW_TL}}",$array["Von"]." - ".$array["Bis"]);
							$array = $this->get_booking_sums("MONTH");
							$_SESSION["HTML"]->assign("index.html", "<!--SUM_MONTH_NOW-->",$array["Stunden"]);
							$_SESSION["HTML"]->assign("index.html", "{{SUM_MONTH_NOW_TL}}",$array["Von"]." - ".$array["Bis"]);
							
							// summary calculation and assignment for last week and month
							$array = $this->get_booking_sums("WEEK", 1);
							$_SESSION["HTML"]->assign("index.html", "<!--SUM_WEEK_LAST-->",$array["Stunden"]);
							$_SESSION["HTML"]->assign("index.html", "{{SUM_WEEK_LAST_TL}}",$array["Von"]." - ".$array["Bis"]);
							$array = $this->get_booking_sums("MONTH", 1);
							$_SESSION["HTML"]->assign("index.html", "<!--SUM_MONTH_LAST-->",$array["Stunden"]);
							$_SESSION["HTML"]->assign("index.html", "{{SUM_MONTH_LAST_TL}}",$array["Von"]." - ".$array["Bis"]);
							
							// send page to browser
							$_SESSION["HTML"]->output("index.html");
							break;
						}
				}
		}
		
		/**
		 * prepare templates for session
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function prepare_templates()
		{
			if (! isset($_SESSION["_cached"]) || ! $_SESSION["_cached"])
				{
					$_SESSION["HTML"]->import();
					$_SESSION["HTML"]->preload();
					$_SESSION["_cached"] = true;
				}
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
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function get_booking_sums($range = "DAY", $offset = 0, $limit = 1)
		{
			$group = "";
			$start = "";
			$stop  = "";
			$redat = "";
			switch (strtoupper($range))
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
			$query .= "DATE_FORMAT( $start , '%d.%m.%Y' ) AS Von, ";
			$query .= "DATE_FORMAT( $stop , '%d.%m.%Y' ) AS Bis, ";
			$query .= "SEC_TO_TIME( IFNULL( SUM( UNIX_TIMESTAMP( stamp_2 ) - UNIX_TIMESTAMP( stamp_1 ) ), 0 ) ) as Stunden, ";
			$query .= "IF( bookid = NULL, 0, COUNT( bookid ) ) AS Besuche FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' AND ";
			$query .= "DATE( stamp_1 ) >= DATE( $start ) AND DATE( stamp_1 ) <= DATE( $stop );";
			
			$array = $_SESSION[$_SESSION["_SqlType"]]->query_all($query);
			
			// future use, if multiple row read implemented
			if ($limit > 1)
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
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function show_last_bookings($limit=10)
		{
			$query  = "SELECT COUNT( stamp_1 ) AS total FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' GROUP BY mid;";
			$entry  = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			$plink = new PageLink($entry["total"]);
			
			$query  = "SELECT DATE_FORMAT( stamp_1, '%d.%m.%Y' ) AS Datum, ";
			$query .= "DATE_FORMAT( stamp_1, '%T Uhr' ) AS Gekommen, ";
			$query .= "DATE_FORMAT( stamp_2, '%T Uhr' ) AS Gegangen, ";
			$query .= "SEC_TO_TIME( UNIX_TIMESTAMP( stamp_2 ) - UNIX_TIMESTAMP( stamp_1 ) ) AS Anwesend ";
			$query .= "FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' ";
			$query .= "ORDER BY stamp_1 DESC ".$plink->get_query_limit();
			
			$book = $this->query2table($query,"last_bookings");
			$_SESSION["HTML"]->assign("index.html", "<!--HISTORY_DATA-->",$book);
			$_SESSION["HTML"]->assign("index.html", "<!--HISTORY_PAGE_LINK-->",$plink);
		}
		
		/**
		 * generate html user table
		 *
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function show_user_table($limit=10)
		{
			$query  = "SELECT COUNT( email ) AS total FROM tr_users;";
			$entry  = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			$plink = new PageLink($entry["total"]);
			
			$query  = "SELECT u.mid AS MitarbeiterID, u.email AS Email, ";
			$query .= "u.firstname AS Vorname, u.lastname AS Nachname, g.groupname AS Gruppe ";
			//$query .= "IF( b.stamp_2 = NULL, 'Nein', 'Ja' ) AS Anwesend ";
			$query .= "FROM tr_users u ";
			//$query .= "LEFT JOIN tr_bookings b USING ( mid ) ";
			$query .= "LEFT JOIN tr_groups g USING ( gid ) ";
			$query .= "ORDER BY mid ASC ".$plink->get_query_limit();
			
			$users = $this->query2table($query,"user_table");
			$_SESSION["HTML"]->assign("users.html", "<!--USER_TABLE-->",$users);
			$_SESSION["HTML"]->assign("users.html", "<!--USER_PAGE_LINK-->",$plink);
		}
		
		
		/**
		 * private query to html table converter
		 *
		 * @param   string		sql query to get rows from
		 * @param 	string		id-tag (css stylesheet)
		 *
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function query2table($query,$id)
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
			if ($first) $dump .= "<tr><td>keine Eintr&auml;ge gefunden...</td></tr>";
			// close table
			$dump  .= "</table>";
			return $dump;
		}
		
		/**
		 * manage page actions and execute state-dependend methods
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function prepare_actions()
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