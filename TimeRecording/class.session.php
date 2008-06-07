<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Session:: class manages login functions and security using sessions
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 *
 *
 */
class Session extends Controller
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
		}
		
		/**
		 * destructor (nop)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
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
		 * @author  patrick.kracht
		 */
		public function logout()
		{
			session_destroy();
			unset($_SESSION);
			$this->reload();
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
		 * @author  patrick.kracht
		 */
		public function passwd()
		{
			//TODO
		}
		
		/**
		 * booking method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function book()
		{
			//TODO
		}
		
		/**
		 * update method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function update()
		{
			//TODO
		}
		
		/**
		 * delete method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function delete()
		{
			//TODO
		}
		
		/**
		 * create method
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function create()
		{
			//TODO
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
		 * @author  patrick.kracht
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
		 * @author  patrick.kracht
		 */
		public function is_admin()
		{
			if (! isset($_SESSION["_UserData"]["gid"])) return false;
			return ($_SESSION["_UserData"]["gid"] == 0);
		}
		
		/**
		 * checks current user for user rights
		 *
		 * @return  boolean   TRUE = is user
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function is_user()
		{
			if (! isset($_SESSION["_UserData"]["gid"])) return false;
			return ($_SESSION["_UserData"]["gid"] != 0 && $_SESSION["_UserData"]["gid"] != 1);
		}
		
		/**
		 * reload page without parameters (do not send headers before)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function reload()
		{
			header("Location: ./");
			exit();
		}
		
		/**
		 * perform login with POST values $_POST["LoginUsername"] and $_POST["LoginPassword"]
		 *
		 * @return  boolean   success of login action
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function login()
		{
			if (isset($_POST["LoginUsername"]) && isset($_POST["LoginPassword"]))
				{
					$username = trim($_POST["LoginUsername"]);
					$password = md5(trim($_POST["LoginPassword"]));
					if (empty($username)) throw new Exception("access denied! no username given!",301);
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
					throw new Exception("access denied for '$username'!",303);
				}
				
			$_SESSION["_UserData"] = $found;
			
			$this->extend();
			
			return true;
		}
		
		/**
		 * extend current session, if valid anymore, or logout after timeout const SESSION_TIMEOUT seconds
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function extend()
		{
			if ($this->started())
				{
					if ($this->valid())
						{
							// extend session
							$_SESSION["_SessionTimer"] = time();
						}
					else
						{
							// kill session and logout
							$this->logout();
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
		 * @author  patrick.kracht
		 */
		public function valid()
		{
			$away = (time() - $_SESSION["_SessionTimer"]);
			return (($_SERVER["REMOTE_ADDR"] == $_SESSION["_UserData"]["ip"]) && ($away < $_SESSION["_TimeOut"]));
		}
		
		/**
		 * checks, if current session is started
		 *
		 * @return  boolean   returns true, if session is started
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function started()
		{
			return (isset($_SESSION["_SessionTimer"]) && isset($_SESSION["_UserData"]["ip"]));
		}
		
		/**
		 * redirect to any $url using header-location
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function return_to($url)
		{
			header("Location: ".$url);
			exit();
		}
		
		/**
		 * restarts the mt_srand with some better randomized init values
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function randomize()
		{
			list($usec, $sec) = explode(' ', microtime());
			mt_srand((float) $sec + ((float) $usec * 100000));
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
		 * @author  patrick.kracht
		 */
		public function valid_email($email, $check_db = false)
		{
			$error = false;
			
			// avoid queries to mail servers, use session buffer
			if (in_array($email, $_SESSION["_UserData"]["valid_mails"]))
				{
					return (! $check_db) ? true : $this->email_exists($email);
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
						
					if ($error) return false;
					
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
												return (! $check_db) ? true : $this->email_exists($email);
											}
									}
							}
					}
					// all mailservers checked... no success, FAILED
				}
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
		 * @author  patrick.kracht
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
		 * @author  patrick.kracht
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
