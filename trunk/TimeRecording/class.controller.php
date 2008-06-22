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
		private $cache_on = true;
		
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
		 * @access  private
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function __toString()
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
					// no menu for guests!!
					return;
				}
				
			$is_users = false;
			$is_home  = false;
			$is_setup = false;
			
			switch ($_SESSION["_PageID.current"])
				{
				case "setup":
					$is_setup = true;
					break;
				case "users":
				case "details":
					$is_users = true;
					break;
				case "home":
				default:
					$is_home = true;
					break;
				}
				
			$_SESSION["HTML"]->menu_insert_entry("Abmelden", "./?action=logout", "Q");
			$_SESSION["HTML"]->menu_insert_spacer();
			$_SESSION["HTML"]->menu_insert_entry("&Uuml;bersicht", "./?page=home", "H", $is_home);
			$_SESSION["HTML"]->menu_insert_entry("Mitarbeiter", "./?page=users", "E", $is_users);
			
			// only admins can edit users
			if ($_SESSION["CLIENT"]->is_admin())
				{
					$_SESSION["HTML"]->menu_insert_entry("Einstellungen", "./?page=setup", "S", $is_setup);
				}
				
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
						case "details":
							$this->show_user_details();
							$_SESSION["HTML"]->output("details.html");
							break;
						case "setup":
							if ($_SESSION["CLIENT"]->is_admin())
								{
									$this->show_setup();
									$_SESSION["HTML"]->output("setup.html");
									break;
								}
							$_SESSION["_Errors"] = "Warnung! Sie sind dazu nicht berechtig!";
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
					$_SESSION["_cached"] = $this->cache_on;
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
			$this->set_table_order("last_bookings", array("stamp_1", "Gekommen", "Gegangen", "Anwesend"), 10);
			
			$query  = "SELECT COUNT( stamp_1 ) AS total FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' GROUP BY mid;";
			$entry  = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			$plink = new PageLink("last_bookings", $entry["total"]);
			
			$query  = "SELECT DATE_FORMAT( stamp_1, '%d.%m.%Y' ) AS Datum, ";
			$query .= "DATE_FORMAT( stamp_1, '%T Uhr' ) AS Gekommen, ";
			$query .= "DATE_FORMAT( stamp_2, '%T Uhr' ) AS Gegangen, ";
			$query .= "SEC_TO_TIME( UNIX_TIMESTAMP( stamp_2 ) - UNIX_TIMESTAMP( stamp_1 ) ) AS Anwesend ";
			$query .= "FROM tr_bookings ";
			$query .= "WHERE mid = '".$_SESSION["_UserData"]["mid"]."' ";
			$query .= "ORDER BY ".$_SESSION["_OrderBy"]["last_bookings"]." ".$plink->get_query_limit();
			
			$book = $this->query2table($query,"last_bookings",array(),false,true);
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
			$this->set_table_order("user_table", array("mid", "email", "firstname", "lastname", "groupname"), 0);
			
			$query  = "SELECT COUNT( email ) AS total FROM tr_users;";
			$entry  = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			$plink = new PageLink("user_table", $entry["total"]);
			
			$query  = "SELECT u.mid AS MID, u.email AS Email, ";
			$query .= "u.firstname AS Vorname, u.lastname AS Nachname, g.groupname AS Gruppe ";
			$query .= "FROM tr_users u ";
			$query .= "LEFT JOIN tr_groups g USING ( gid ) ";
			$query .= "GROUP BY mid ";
			$query .= "ORDER BY ".$_SESSION["_OrderBy"]["user_table"]." ".$plink->get_query_limit();
			
			$users = $this->query2table($query,"user_table", array(60, 290, 200, 200), true, true);
			$_SESSION["HTML"]->assign("users.html", "<!--USER_TABLE-->",$users);
			$_SESSION["HTML"]->assign("users.html", "<!--USER_PAGE_LINK-->",$plink);
		}
		
		/**
		 * show setup page, if admin
		 *
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function show_setup()
		{
			$select = array("-1" => "");
			$edit   = array("name1" => "", "name2" => "", "pass" => "");
			
			$query  = "SELECT mid, email FROM tr_users ORDER BY email;";
			$array  = $_SESSION[$_SESSION["_SqlType"]]->query_all($query);
			
			// reformat array for creating select menu
			foreach($array as $key => $value)
			{
				$select[$value["mid"]] = $value["email"];
			}
			
			// check for selected item
			$selected_user  = -1;
			$selected_group = -1;
			
			if (isset($_POST["edit_mid"]))
				{
					$selected_user = @intval($_POST["edit_mid"]);
					if ($_POST["submit"] == "LADEN" && $selected_user >= 0)
						{
							$query  = "SELECT mid, firstname, lastname, gid FROM ";
							$query .= "tr_users WHERE mid='$selected_user';";
							$array  = $_SESSION[$_SESSION["_SqlType"]]->query_all($query);
							
							$edit["name1"]  = $array[0]["firstname"];
							$edit["name2"]  = $array[0]["lastname"];
							$selected_user  = $array[0]["mid"];
							$selected_group = $array[0]["gid"];
						}
					else if ($_POST["submit"] == "SPEICHERN" && $selected_user >= 0)
						{
							$edit["name1"]  = ucfirst(trim($_POST["new_firstname"]));
							$edit["name2"]  = ucfirst(trim($_POST["new_lastname"]));
							$selected_user  = @intval($_POST["edit_mid"]);
							$selected_group = @intval($_POST["new_group"]);
							
							if ($selected_group == 1 || $selected_group < 0 || $selected_group > 3)
								{
									$_SESSION["_Errors"] = "Sie haben keine bekannte Gruppe selektiert!";
								}
							else
								{
									$query  = "UPDATE tr_users SET ";
									$query .= "firstname = '".$edit["name1"]."', ";
									$query .= "lastname = '".$edit["name2"]."', ";
									$query .= "gid = '$selected_group' ";
									$query .= "WHERE mid='$selected_user';";
									$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
								}
						}
				}
				
			$users_select = $_SESSION["HTML"]->create_select($select, $selected_user);
			$group_select = $_SESSION["HTML"]->create_select(array(1=>"",0=>"Administrator",2=>"Mitarbeiter",3=>"Buchhaltung"), $selected_group);
			
			$_SESSION["HTML"]->assign("setup.html", "{{EDIT_FIRSTNAME}}", $edit["name1"]);
			$_SESSION["HTML"]->assign("setup.html", "{{EDIT_LASTNAME}}",  $edit["name2"]);
			$_SESSION["HTML"]->assign("setup.html", "{{EDIT_PASSWORD}}",  $edit["pass"]);
			$_SESSION["HTML"]->assign("setup.html", "<!--USER_SELECT-->", $users_select);
			$_SESSION["HTML"]->assign("setup.html", "<!--GROUP_SELECT-->", $group_select);
		}
		
		/**
		 * generate html details for specific userid
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function show_user_details()
		{
			if (! isset($_GET["id"]))
				{
					throw new Exception("Sie haben keine Benutzer-ID angegeben!",999);
				}
			else if (! $_SESSION["CLIENT"]->is_admin() && ! $_SESSION["CLIENT"]->is_accounting())
				{
					throw new Exception("Sie sind dazu nicht berechtigt!",998);
				}
				
			$mid = intval($_GET["id"]);
			
			$query    = "SELECT firstname, lastname FROM tr_users WHERE mid = '$mid';";
			$details  = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			$query  = "SELECT DATE_FORMAT( stamp_1, '%d.%m.%Y' ) AS Datum, ";
			$query .= "SEC_TO_TIME( IFNULL( SUM( UNIX_TIMESTAMP( stamp_2 ) - UNIX_TIMESTAMP( stamp_1 ) ), 0 ) ) as 'Stunden anwesend', ";
			$query .= "IF( bookid = NULL, 0, COUNT( bookid ) ) AS 'Anzahl Buchungen' ";
			$query .= "FROM tr_bookings ";
			$query .= "WHERE mid = '$mid' GROUP BY Datum ORDER BY Datum ASC";
			
			$content = $this->query2table($query,"booking_details");
			
			$_SESSION["HTML"]->assign("details.html", "<!--FIRST_NAME-->",$details["firstname"]);
			$_SESSION["HTML"]->assign("details.html", "<!--LAST_NAME-->", $details["lastname"]);
			$_SESSION["HTML"]->assign("details.html", "<!--DETAILS-->",   $content);
		}
		
		/**
		 * set sorting query
		 *
		 * @param   string		tableid for sorting
		 * @param 	array		order by values, 0 = default [left to right]
		 * @param 	int			(optional) default value
		 *
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function set_table_order($tableid, $ordervalues, $default = 0)
		{
			if (! is_array($_SESSION["_OrderBy"]) || ! is_array($_SESSION["_OrderID"]))
				{
					$_SESSION["_OrderID"] = array();
					$_SESSION["_OrderBy"] = array();
				}
				
			if (! isset($_SESSION["_OrderBy"][$tableid]) || isset($_GET["order"]))
				{
					$_SESSION["_OrderID"][$tableid] = (isset($_GET["order"]))?(intval($_GET["order"])-10):($default-10);
					$direc = ($_SESSION["_OrderID"][$tableid]>=0)?"DESC":"ASC";
					$order = ($_SESSION["_OrderID"][$tableid]<0)?($_SESSION["_OrderID"][$tableid]+10):$_SESSION["_OrderID"][$tableid];
					
					if (isset($ordervalues[$order]))
						{
							$order = $ordervalues[$order];
						}
					else
						{
							$order = $ordervalues[0];
						}
					$_SESSION["_OrderBy"][$tableid] = "$order $direc";
				}
		}
		
		/**
		 * private query to html table converter
		 *
		 * @param   string		sql query to get rows from
		 * @param 	string		id-tag (css stylesheet)
		 * @param 	array		(optional) width in pixel for td
		 * @param	boolean		(optional) add col with dtails link to userid
		 *
		 * @access  protected
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		protected function query2table($query,$id,$width = array(),$details = false, $orderon = false)
		{
			$details = $details && ($_SESSION["CLIENT"]->is_admin() || $_SESSION["CLIENT"]->is_accounting());
			$result  = $_SESSION[$_SESSION["_SqlType"]]->query($query);
			$first   = true;
			$dump    = "<table id=\"$id\">";
			while ($row = $_SESSION[$_SESSION["_SqlType"]]->fetch_array($result))
				{
					// append only keys on first line
					if ($first)
						{
							$dump .= "<tr>";
							$sid   = "";
							
							if (ini_get("session.use_cookies") != "1")
								{
									$sid = "&amp;".ini_get("session.name")."=".session_id();
								}
								
							foreach(array_keys($row) as $key => $value)
							{
								$td_width = "";
								
								// correct width if set (in pixels)
								if (isset($width[$key]))
									{
										$td_width = " style=\"width:".$width[$key]."px;\"";
									}
									
								$dump .= "<td{$td_width}><b>";
								
								if ($orderon)
									{
										$order = ($_SESSION["_OrderID"][$id]<0)?($_SESSION["_OrderID"][$id]+10):$_SESSION["_OrderID"][$id];
										$arrow_up = "";
										$arrow_dn = "";
										
										// highlight active buttons
										if ($order == $key)
											{
												if ($_SESSION["_OrderID"][$id] < 0)
													$arrow_up = "_active";
												else
													$arrow_dn = "_active";
											}
											
										$dump .= "<a href=\"./?page=".$_SESSION["_PageID.current"]."&amp;order=".$key.$sid."\" target=\"_self\"><img src=\"./images/order_arrow_up{$arrow_up}.png\" alt=\"aufsteigend nach $value sortiert\" title=\"aufsteigend nach $value sortiert\" border=\"0\" width=\"12\" height=\"12\" /></a>&nbsp;";
										$dump .= "<a href=\"./?page=".$_SESSION["_PageID.current"]."&amp;order=".($key+10).$sid."\" target=\"_self\"><img src=\"./images/order_arrow_down{$arrow_dn}.png\" alt=\"absteigend nach $value sortiert\" title=\"absteigend nach $value sortiert\" border=\"0\" width=\"12\" height=\"12\" /></a>&nbsp;";
									}
								$dump .= $value;
								$dump .= "</b></td>";
							}
							
							if ($details)
								{
									$dump .= "<td>&nbsp;</td>";
								}
								
							$dump .= "</tr>";
							$first = false;
						}
					// append data rows
					$dump .= "<tr><td>";
					
					$dump .= implode("</td><td>", $row);
					
					if ($details)
						{
							$dump .= "<td><a href=\"./?page=details&amp;id=".$row["MID"].$sid."\" target=\"_self\"><img src=\"./images/more_infos.png\" alt=\"Details zu ".$row["Vorname"]." ".$row["Nachname"]."\" title=\"Details zu ".$row["Vorname"]." ".$row["Nachname"]."\" border=\"0\" width=\"12\" height=\"12\" /></a></td>";
						}
						
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