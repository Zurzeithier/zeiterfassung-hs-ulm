<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Session:: class manages login functions and security using sessions
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
 */
class Session extends Controller
	{
	
		/**
		 * constructor
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __construct()
		{
		}
		
		/**
		 * destructor (nop)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __destruct()
		{
		}
		
		/**
		 * logout destroys session and starts
		 * a new one in guest mode and reload page
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function logout($error_message="")
		{
			// kill session and array
			session_unset();
			session_destroy();
			session_regenerate_id(true);
			
			// savely remove complete array and reload page
			$_SESSION = array();
			$this->return_to("./");
		}
		
		/**
		 * perform login with POST values $_POST["LoginUsername"] and $_POST["LoginPassword"]
		 *
		 * @return  boolean   success of login action
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function login()
		{
			if (isset($_POST["LoginUsername"]) && isset($_POST["LoginPassword"]))
				{
					$username = trim($_POST["LoginUsername"]);
					$password = md5(trim($_POST["LoginPassword"]));
					if (empty($username))
						{
							throw new Exception("Zugriff verweigert! Kein Benutzername eingegeben!",301);
						}
				}
			else
				{
					// if no post values for user and pass, init save state (guest)
					$this->logout();
				}
				
			// check, if user with md5-pass exists in database
			$query  = "SELECT u.mid, u.gid, u.email, u.firstname, u.lastname, g.groupname ";
			$query .= "FROM tr_users u LEFT JOIN ";
			$query .= "tr_groups g USING ( gid ) ";
			$query .= "WHERE u.email = '$username' AND u.password = '$password';";
			$found = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			if (! isset($found["mid"]))
				{
					throw new Exception("Die Email oder das Kennwort ist falsch!",302);
				}
				
			$_SESSION["_UserData"] = $found;
			
			// save current timestamp and ip to verify session
			$_SESSION["_UserData"]["timestamp"] = time();
			$_SESSION["_UserData"]["ip"]        = $_SERVER["REMOTE_ADDR"];
			
			$this->extend();
			
			return true;
		}
		
		/**
		 * reset a password for a given username (email)
		 * and returns new generated password
		 *
		 * @param   string   username (email)
		 *
		 * @return  string   new generated password
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function passwd()
		{
			if (isset($_POST["LoginUsername"]))
				{
					$username = trim($_POST["LoginUsername"]);
					if (empty($username))
						{
							throw new Exception("Sie haben keine Emailadresse angegeben!",303);
						}
				}
			else
				{
					throw new Exception("Sie haben keine Emailadresse angegeben!",303);
				}
				
			// check, if user with md5-pass exists in database
			$query  = "SELECT mid, firstname, lastname FROM tr_users WHERE email = '$username';";
			$result = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
			
			// only if one hit
			if (! isset($result["mid"]))
				{
					throw new Exception("Die Emailadresse '$username' ist mir unbekannt!",304);
				}
			else
				{
					$passwd  = $this->generate_password();
					$passmd5 = md5($passwd);
					$query   = "UPDATE tr_users SET password = '$passmd5' WHERE email = '$username';";
					$_SESSION[$_SESSION["_SqlType"]]->query($query);
					$count   = $_SESSION[$_SESSION["_SqlType"]]->affected_rows();
					
					// successful updated database
					if ($count == 1)
						{
							$tpl = "passwd.email.html";
							
							$email = new Email(array($tpl,"Sie haben Ihr Passwort vergessen?"));
							$email->set_sender("omega2k@omega2k.de","Webmaster");
							$email->set_to($username, $result["firstname"]." ".$result["lastname"]);
							$email->assign($tpl,"{{URL}}","http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/");
							$email->assign($tpl,"{{USER}}",$username);
							$email->assign($tpl,"{{PASS}}",$passwd);
							if ($email->send())
								{
									throw new Exception("Es wurde ein neues Passwort an '$username' geschickt!",305);
								}
							else
								{
									throw new Exception("Die Email konnte nicht gesendet werden! Wir arbeiten daran...",306);
								}
						}
					else
						{
							throw new Exception("Es gab Probleme mit der Datenbank! Wir arbeiten daran...",307);
						}
				}
		}
		
		/**
		 * get new token and save in session
		 *
		 * @return  string		md5 token
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function get_token()
		{
			$_SESSION["_AsyncToken"] = md5($this->generate_password(10));
			return $_SESSION["_AsyncToken"];
		}
		
		/**
		 * validate token and return true, if valid
		 *
		 * @return  boolean		is valid token?
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function is_valid_token()
		{
			$ignore = false;
			
			// for async booking, check if token is valid
			if (isset($_SESSION["_AsyncToken"]) && isset($_POST["ig"]))
				{
					$ignore = ($_SESSION["_AsyncToken"] == $_POST["ig"]);
					unset($_SESSION["_AsyncToken"]);
				}
				
			return $ignore;
		}
		
		/**
		 * booking method with symbol for userid
		 *
		 * @param	int		symbol id to set
		 * @param	int		userid to set (optional)
		 *
		 * @return	int		affected rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function book()
		{
			// check if token is set, for async booking
			$ignore = $this->is_valid_token();
			$symid  = (isset($_GET["id"])) ? intval($_GET["id"]) : -1;
			$symid  = (isset($_POST["id"]) && $symid == -1) ? intval($_POST["id"]) : $symid;
			
			if (! $this->is_user())
				{
					throw new Exception("Sie sind kein bekannter Benutzer!",308);
				}
			else if ($symid < 0 || $symid > 1)
				{
					throw new Exception("Keine zugelassene Buchungs-Aktion!",309);
				}
			else
				{
					$mid    = $_SESSION["_UserData"]["mid"];
					
					// check if booking is out of cycle
					$query  = "SELECT bookid, stamp_1, ( UNIX_TIMESTAMP() - UNIX_TIMESTAMP( stamp_1 ) ) AS away, stamp_2 FROM tr_bookings ";
					$query .= "WHERE mid = '$mid' ORDER BY bookid DESC;";
					$last   = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
					
					$case_1 = ($symid == 1 && isset($last["stamp_1"]) && $last["stamp_2"] == NULL);
					$case_2 = ($last["away"] > ($_SESSION["_MaxWorkingH"] * 3600) && $symid == 0);
					
					// case_1: new check in but last check out is missing (async booking!)
					if ($case_1 && $ignore)
						{
							$query  = "INSERT INTO tr_bookings ( mid ) ";
							$query .= "VALUES ( '$mid' );";
							$_SESSION[$_SESSION["_SqlType"]]->query($query);
						}
					// case_1: new check in but last check out is missing
					else if ($case_1)
						{
							$token = $this->get_token();
							$question  = "<form action=\"./\" method=\"post\" id=\"question\">";
							$question .= "<input type=\"hidden\" name=\"ig\"     value=\"$token\"/>";
							$question .= "<input type=\"hidden\" name=\"id\"     value=\"$symid\"/>";
							$question .= "<input type=\"hidden\" name=\"page\"   value=\"home\" />";
							$question .= "<input type=\"hidden\" name=\"sid\"    value=\"".session_id()."\" />";
							$question .= "<input type=\"hidden\" name=\"action\" value=\"book\" />";
							$question .= "<input type=\"submit\" name=\"submit\" value=\"Ignorieren\" />";
							$question .= "<input type=\"button\" onclick=\"location.href='./?page=home';\" value=\"Abbrechen\" />";
							$question .= "</form>";
							
							throw new Exception("Azyklische Buchungen sind nicht zugelassen!$question",311);
						}
					// case_2: more than maximum hours
					else if ($case_2)
						{
							$message  = "Sie haben die Arbeitszeit von maximal ".$_SESSION["_MaxWorkingH"];
							$message .= " Stunden &uuml;berschritten!";
							throw new Exception($message,312);
						}
					// update going stamp only
					else if ($symid == 0)
						{
							$query  = "UPDATE tr_bookings SET stamp_2 = CURRENT_TIMESTAMP ";
							$query .= "WHERE bookid = '".$last["bookid"]."';";
							$_SESSION[$_SESSION["_SqlType"]]->query($query);
						}
					// insert new stamp
					else if ($symid == 1)
						{
							$query  = "INSERT INTO tr_bookings ( mid ) ";
							$query .= "VALUES ( '$mid' );";
							$_SESSION[$_SESSION["_SqlType"]]->query($query);
						}
					else
						{
							throw new Exception("Unbekannter Fehler!",399);
						}
				}
		}
		
		/**
		 * get complete array of users [mid] => [email]
		 *
		 * @return	array	userlist
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function get_user_array()
		{
			$select = array("-1" => "&gt; BENUTZERAUSWAHL");
			$query  = "SELECT mid, CONCAT( lastname, ', ', firstname, ' &lt;', email, '&gt;' ) AS name ";
			$query .= "FROM tr_users ORDER BY lastname, firstname, email;";
			$array  = $_SESSION[$_SESSION["_SqlType"]]->query_all($query);
			
			// reformat array for creating select menu
			foreach($array as $key => $value)
			{
				$select[$value["mid"]] = $value["name"];
			}
			
			return $select;
		}
		
		/**
		 * prepare update save values, if not set
		 *
		 * @access  private
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function init_update_forms()
		{
			if (! isset($_SESSION["_updated"]))
				{
					$_SESSION["_updated"] = array("user"=>-1,"group"=>-1,"edit"=>array("name1" => "", "name2" => ""),"new" => array("name1"=>"","name2"=>"","email"=>"","passw"=>""));
				}
		}
		
		/**
		 * update method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function update()
		{
			$this->init_update_forms();
			$edit = array("name1" => "", "name2" => "");
			
			// check for selected item
			$selected_user  = (isset($_POST["edit_mid"]))?intval($_POST["edit_mid"]):-1;
			$selected_group = (isset($_POST["new_group"])&&$selected_user!=-1)?intval($_POST["new_group"]):-1;
			
			if ($selected_user >= 0)
				{
					$edit["name1"]  = ucfirst(trim($_POST["new_firstname"]));
					$edit["name2"]  = ucfirst(trim($_POST["new_lastname"]));
					if ($_POST["submit"] == "LADEN" || $_POST["submit"] == "ABBRECHEN")
						{
							$query  = "SELECT mid, firstname, lastname, gid FROM ";
							$query .= "tr_users WHERE mid='$selected_user';";
							$array  = $_SESSION[$_SESSION["_SqlType"]]->query_all($query);
							$edit["name1"]  = $array[0]["firstname"];
							$edit["name2"]  = $array[0]["lastname"];
							$selected_user  = $array[0]["mid"];
							$selected_group = $array[0]["gid"];
						}
					else if ($_POST["submit"] == "SPEICHERN")
						{
							if ($selected_group == 1 || $selected_group < 0 || $selected_group > 3)
								{
									$_SESSION["_Errors"] .= "Sie haben keine bekannte Gruppe selektiert! ";
								}
							else if (empty($edit["name1"]) || empty($edit["name2"]))
								{
									$_SESSION["_Errors"] .= "Sie haben einen Namen nicht eingetragen! ";
								}
							else
								{
									$query  = "UPDATE tr_users SET ";
									$query .= "firstname = '".$edit["name1"]."', ";
									$query .= "lastname = '".$edit["name2"]."', ";
									$query .= "gid = '$selected_group' ";
									$query .= "WHERE mid='$selected_user';";
									$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
									if ($_SESSION[$_SESSION["_SqlType"]]->affected_rows($result))
										{
											$_SESSION["_Errors"] .= "Der Benutzer wurde aktualisiert! ";
										}
								}
						}
					else if ($_POST["submit"] == "LOESCHEN")
						{
							$ignore = $this->is_valid_token();
							
							if ($ignore && $selected_user == 0)
								{
									$_SESSION["_Errors"] .= "Der Administrator kann nicht gel&ouml;scht werden!";
								}
							else if ($ignore)
								{
									$this->delete($selected_user);
								}
							else
								{
									$token  = $this->get_token();
									
									$question  = "<form action=\"./\" method=\"post\" id=\"question\">";
									$question .= "<input type=\"hidden\" name=\"ig\"       value=\"$token\"/>";
									$question .= "<input type=\"hidden\" name=\"edit_mid\" value=\"$selected_user\"/>";
									$question .= "<input type=\"hidden\" name=\"page\"     value=\"setup\" />";
									$question .= "<input type=\"hidden\" name=\"sid\"      value=\"".session_id()."\" />";
									$question .= "<input type=\"hidden\" name=\"action\"   value=\"update\" />";
									$question .= "<input type=\"submit\" name=\"submit\"   value=\"LOESCHEN\" />";
									$question .= "<input type=\"submit\" name=\"submit\"   value=\"ABBRECHEN\" />";
									$question .= "</form>";
									
									$_SESSION["_Errors"] .= "Sind Sie sicher, dass Sie den Benutzer l&ouml;schen wollen? $question";
								}
						}
				}
			$_SESSION["_updated"]["user"]  = $selected_user;
			$_SESSION["_updated"]["group"] = $selected_group;
			$_SESSION["_updated"]["edit"]  = $edit;
		}
		
		/**
		 * delete method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function delete($userid)
		{
			$query  = "DELETE FROM tr_users ";
			$query .= "WHERE mid='$userid' LIMIT 1;";
			$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
			
			if ($_SESSION[$_SESSION["_SqlType"]]->affected_rows($result))
				{
					$_SESSION["_Errors"] .= "Der Benutzer wurde gel&ouml;scht!";
				}
			else
				{
					$_SESSION["_Errors"] .= "Fehler! Benutzer nicht gefunden!";
				}
		}
		
		/**
		 * create method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function create()
		{
			$new["email"] = (isset($_POST["new_email"]))?strtolower(trim($_POST["new_email"])):"";
			$new["name1"] = (isset($_POST["new_firstname"]))?ucfirst(trim($_POST["new_firstname"])):"";
			$new["name2"] = (isset($_POST["new_lastname"]))?ucfirst(trim($_POST["new_lastname"])):"";
			$new["passw"] = (isset($_POST["new_password"]))?$_POST["new_password"]:"";
			$_SESSION["_updated"]["new"] = $new;
			
			if (! empty($new["passw"]))
				{
					$new["passw"] = md5($new["passw"]);
				}
				
			if ($_POST["submit"] == "ERSTELLEN" && $this->valid_email($new["email"], true))
				{
					$_SESSION["_updated"]["new"] = array("email"=>"","name1"=>"","name2"=>"","passw"=>"");
					
					$query  = "INSERT INTO tr_users ( email, firstname, lastname, password ) ";
					$query .= "VALUES ( '".$new["email"]."','".$new["name1"]."','".$new["name2"]."','".$new["passw"]."' );";
					$_SESSION[$_SESSION["_SqlType"]]->query($query);
					
					$_SESSION["_Errors"] .= "Neuer Benutzer '".$new["email"]."' wurde erstellt! ";
				}
			elseif($_POST["submit"] == "RESET")
			{
				$_SESSION["_updated"]["new"] = array("email"=>"","name1"=>"","name2"=>"","passw"=>"");
			}
		}
		
		/**
		 * generates a new random password with $length
		 *
		 * @param  int   length of the generated password
		 *
		 * @return  string   new generated password
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function generate_password($length = 8)
		{
			$this->randomize();
			$password = "";
			$possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY";
			$i = 0;
			while ($i < $length)
				{
					$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
					if (! strstr($password, $char))
						{
							$password .= $char;
							$i++;
						}
				}
			return $password;
		}
		
		/**
		 * checks current user for admin rights
		 *
		 * @return  boolean   TRUE = is admin
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function is_admin()
		{
			if (! isset($_SESSION["_UserData"]["gid"])) return false;
			return ($_SESSION["_UserData"]["gid"] == 0);
		}
		
		/**
		 * checks current user for admin rights
		 *
		 * @return  boolean   TRUE = is admin
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function is_accounting()
		{
			if (! isset($_SESSION["_UserData"]["gid"])) return false;
			return ($_SESSION["_UserData"]["gid"] == 3);
		}
		
		/**
		 * checks current user for user rights
		 *
		 * @return  boolean   TRUE = is user
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function is_user()
		{
			if (! isset($_SESSION["_UserData"]["gid"])) return false;
			return ($_SESSION["_UserData"]["gid"] != 1);
		}
		
		/**
		 * redirect to any $url using header-location
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function return_to($url)
		{
			session_write_close();
			header("Location: ".$url);
			exit();
		}
		
		/**
		 * extend current session, if valid anymore, or logout after timeout const SESSION_TIMEOUT seconds
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function extend()
		{
			if ($this->started())
				{
					if ($this->valid())
						{
							// extend session
							$_SESSION["_UserData"]["timestamp"] = time();
						}
					else
						{
							// kill session and logout
							$this->logout("Ihre Session ist abgelaufen! Bitte neu anmelden...");
						}
				}
			else
				{
					$_SESSION["_UserData"]["ip"] = $_SERVER["REMOTE_ADDR"];
				}
		}
		
		/**
		 * checks, if current session is started and valid (logout after timeout const SESSION_TIMEOUT seconds)
		 *
		 * @return  boolean   returns true, if session is valid
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function valid()
		{
			// no timestamp means never logged in
			if (!isset($_SESSION["_UserData"]["timestamp"]))
				{
					return false;
				}
			// if session timed out in case of inactivity, return false too
			// or if ip-address has changed (may be intruder)
			$away = (time() - $_SESSION["_UserData"]["timestamp"]);
			return (($_SERVER["REMOTE_ADDR"] == $_SESSION["_UserData"]["ip"]) && ($away < $_SESSION["_TimeOut"]));
		}
		
		/**
		 * checks, if current session is started
		 *
		 * @return  boolean   returns true, if session is started
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function started()
		{
			// only if having timestamp and ip return true
			return (isset($_SESSION["_UserData"]["timestamp"]) && isset($_SESSION["_UserData"]["ip"]));
		}
		
		/**
		 * restarts the mt_srand with some better randomized init values
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function randomize()
		{
			list($usec, $sec) = explode(' ', microtime());
			mt_srand((float) $sec + ((float) $usec * 100000));
		}
		
		/**
		 * check, is an $email is in database
		 *
		 * @param  	string		email to check
		 *
		 * @return  boolean		if the $email is in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function email_exists($email)
		{
			$query  = "SELECT mid FROM tr_users WHERE email='$email';";
			$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
			
			if ($_SESSION[$_SESSION["_SqlType"]]->num_rows($result) == 1)
				{
					$_SESSION["_Errors"] .= "Diese Email existiert bereits in der Datenbank! ";
					return true;
				}
			return false;
		}
		
		/**
		 * check, is an $email is valid (RegEx, DB and MX-check)
		 *
		 * @param  string   email address for checking
		 * @param  boolean   check email in database too?
		 *
		 * @return  boolean   if the $email is a valid one
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function valid_email($email, $check_db = false)
		{
			$error = false;
			
			if (! isset($_SESSION["_UserData"]["valid_mails"]))
				{
					$_SESSION["_UserData"]["valid_mails"] = array();
				}
				
			// avoid queries to mail servers, use session buffer
			if (in_array($email, $_SESSION["_UserData"]["valid_mails"]))
				{
					return (! $check_db) ? true : ! $this->email_exists($email);
				}
				
			$email = strtolower($email);
			
			// regex for checking syntactically correct email addresses
			if (ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $email))
				{
					list($username, $domain) = split('@', $email);
					$smtp = array("mail.$domain", "smtp.$domain");
					
					if (! function_exists("checkdnsrr"))
						{
							$error = true;
							echo("function checkdnsrr() not found!\n");
						}
					if (! function_exists("getmxrr"))
						{
							$error = true;
							echo("function getmxrr() not found!\n");
						}
					if (! function_exists("fsockopen"))
						{
							$error = true;
							echo("function fsockopen() not found!\n");
						}
						
					if ($error)
						{
							$_SESSION["_Errors"] .= "Fehler! Es fehlen grundlegende Funktionen! ";
							return false;
						}
						
					// query MX-records
					if (@checkdnsrr($domain, "MX"))
						{
							$mx = array();
							@getmxrr($domain, $mx);
							foreach($mx as $mxs) array_push($smtp, $mxs);
						}
					else
						{
							// unknown error, no MX-records?
							$_SESSION["_Errors"] .= "Fehler! Keine MX-Records gefunden! ";
							return false;
						}
						
					// contact each server
					foreach($smtp as $server)
					{
						if ($sock = @fsockopen($server, 25, $errno, $errstr, 3))
							{
								if (ereg("^220", $output = fgets($sock, 1024)))
									{
										fputs($sock, "HELO ".$_SERVER["HTTP_HOST"]."\r\n");
										$out = fgets($sock, 1024);
										fputs($sock, "MAIL FROM: <{$email}>\r\n");
										$gfr = fgets($sock, 1024);
										fputs($sock, "RCPT TO: <{$email}>\r\n");
										$gto = fgets($sock, 1024);
										fputs($sock, "QUIT\r\n");
										fclose($sock);
										
										// if sender and recipient valid, TRUE
										if ((ereg("^250", $gfr) && ereg("^250", $gto)) || (! empty($gfr) && ! ereg("unknown", $gfr)))
											{
												array_push($_SESSION["_UserData"]["valid_mails"], $email);
												return (! $check_db) ? true : ! $this->email_exists($email);
											}
									}
							}
					}
					// all mailservers checked... no success, FAILED
				}
			$_SESSION["_Errors"] .= "Die Email $email ist ung&uuml;ltig! ";
			return false;
		}
		
		/**
		 * get mx-records from host (optional: $type)
		 *
		 * @param  string   host to contact and query MX
		 * @param  string   (optional) type of record
		 *
		 * @return  array   complete list of records using nslookup
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function get_mx_records($host, $type = "MX")
		{
			if (! empty($host))
				{
					$result = array();
					exec("nslookup -type=$type $host", $result);
					foreach($result as $line)
					{
						if (eregi("^$host", $line))
							{
								return $result;
							}
					}
					return false;
				}
			return false;
		}
		
		/**
		 * proof choosen password for a better security
		 *
		 * @param  string   password to check
		 * @param  int        minimum length of password
		 *
		 * @return  boolean   if the $password is a good one
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function is_secure_password($password, $min_len = 8)
		{
			return (strlen($password) >= $min_len
			        && preg_match("@[A-Z]@", $password)
			        && preg_match("@[a-z]@", $password)
			        && preg_match("@[0-9]@", $password));
		}
		
	}

?>