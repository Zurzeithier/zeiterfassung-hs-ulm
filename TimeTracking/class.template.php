<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Template:: class implements the HTML-templates and output-engine.
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
class Template extends Controller
	{
		private $template     = array();
		private $assigned     = array();
		private $debug_off    = true;
		private $do_compress  = true;
		private $sql_table    = false;
		private $sql_type     = false;
		private $debugout     = "";
		private $menu         = "";
		private $tpl_folder   = "./templates/";
		
		/**
		 * constructor read config-file and initializes the vars
		 *
		 * @param   string  config-file-location
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($parameters = array())
		{
			$this->tpl_folder  = (isset($parameters[0])) ? $parameters[0] : "./templates/";
			$this->debug_off   = (isset($parameters[1])) ? $parameters[1] : true;
			$this->do_compress = (isset($parameters[2])) ? $parameters[2] : true;
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
		 * starts new clean output buffer
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function clean_ob()
		{
			if (!ob_get_length() || !ob_get_level()) ob_start();
			session_cache_limiter('must-revalidate');
		}
		
		public function preload($list = false)
		{
			//Templates aus der Liste vorladen
			if (is_array($list))
				{
					$list = implode("','", $list);
					$list = " WHERE file IN ( '$list' )";
				}
			else if (is_string($list))
				{
					$list = str_replace(",", "','", $list);
					$list = " WHERE file IN ( '$list' )";
				}
				
			$table = $_SESSION["_TplSqlTable"];
			$query  = "SELECT file, content FROM $table $list;";
			$result = $_SESSION[$_SESSION["_SqlType"]]->query($query);
			while ($row = $_SESSION[$_SESSION["_SqlType"]]->fetch_array())
				{
					$this->template[ $row['file'] ] = $row['content'];
					$this->assigned[ $row['file'] ] = array();
				}
		}
		
		public function import($list = false)
		{
			//Templates aus der Liste in die Datenbank importieren
			$values = "";
			$files  = array();
			
			if (! $list)
				{
					$dir = dir($this->tpl_folder);
					while ($file = $dir->read())
						{
							if (strlen($file) > 2 && ! is_dir($file) && $file {0} != ".")
								{
									$files[] = $file;
								}
						}
					$dir->close();
				}
			else if (is_string($list))
				{
					$files = explode(',', $list);
				}
			else if (is_array($list))
				{
					$files = $list;
				}
			else
				{
					return false;
				}
				
			foreach($files as $file)
			{
				$file     = trim($file);
				$filename = $this->tpl_folder.$file;
				$content  = file_get_contents($filename);
				$content  = addslashes($content);
				$values  .= (($values == "") ? "" : ", ") . "( '$file', '$content' )";
			}
			
			try
				{
					$table = $_SESSION["_TplSqlTable"];
					$_SESSION[$_SESSION["_SqlType"]]->query("TRUNCATE TABLE $table;");
					$_SESSION[$_SESSION["_SqlType"]]->query("INSERT INTO $table ( file , content ) VALUES $values;");
					return $_SESSION[$_SESSION["_SqlType"]]->affected_rows();
				}
			catch (Exception $e)
				{
					echo $e->getMessage();
				}
		}
		
		
		/**
		 * loads template with $name in internal buffer-array
		 *
		 * @param   string  template file name
		 * @param   boolean force overwriting buffer content
		 *  *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function load($name, $force_reload = false)
		{
			$this->assign($name, "{{SID}}",       session_id());
			$this->assign($name, "{{PAGE}}",      $_SESSION["_PageID.current"]);
			$this->assign($name, "{{ACTION}}",    $_SESSION["_Action"]);
			
			// if template loaded, don't waste time and return
			if (isset($this->template[$name]) && ! $force_reload) return true;
			
			// try loading from database, is options set
			if ($_SESSION["_TplSqlTable"] && $_SESSION["_SqlType"])
				{
					try
						{
							$table = $_SESSION["_TplSqlTable"];
							$query = "SELECT content FROM ${table} WHERE file = '${template}';";
							$array = $_SESSION[$_SESSION["_SqlType"]]->query_first($query);
							$this->template[$name] = $array["content"];
							return true;
						}
					catch (Exception $e)
						{
							echo $e->getMessage();
						}
				}
				
			// load template or die
			$filename = $this->tpl_folder.$name;
			if (! file_exists($filename) || is_dir($filename))
				{
					throw new Exception("could not load template '$name' from '$filename'!");
				}
				
			$this->template[$name] = file_get_contents($filename);
			
			// prepare array for assigned substitutions
			if (! array_key_exists($name, $this->assigned))
				{
					array_push($this->assigned, array($name => array()));
				}
		}
		
		/**
		 * assign a replacement in template $name
		 *
		 * @param   string  template file name
		 * @param   mixed   string (search for something) OR
		 * 					 array key = search, value = replace
		 * @param   string  replace with something (if $search is string)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function assign($name, $search, $replace = "EMPTY")
		{
			if (is_array($search))
				{
					foreach($search as $key => $value) $this->assigned[$name][$key] = $value;
				}
			else
				{
					$this->assigned[$name][$search] = $replace;
				}
		}
		
		/**
		 * returns get_parsed content of template in buffer
		 *
		 * @param   string  template file name
		 * @param   boolean force reloading buffer content
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get($name, $force_reload = false)
		{
			if (! isset($this->template[$name]) || $force_reload)
				{
					$this->load($name, $force_reload);
				}
			$buffer = $this->get_parsed($name);
			$this->special_chars($buffer);
			return $buffer;
		}
		
		/**
		 * compress template (remove not needed chars)
		 *
		 * @param   string  template file name
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function compress(&$buffer)
		{
			$length = strlen($buffer);
			$s = array("\t" => "", "\n" => " ", "\r" => " ", "   " => " ", "  " => " ", "> <" => "><");
			do
				{
					$len1 = strlen($buffer);
					$buffer = str_replace(array_keys($s), array_values($s), $buffer);
				}
			while ($len1 > strlen($buffer));
			$this->restore_newlines($buffer);
		}
		
		/**
		 * finally sends complete template $name, get_parsed
		 * and compressed (if setup in config-file)
		 *
		 * @param   string  template file name
		 * @param   string  type of output (html,js,css...)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function output($name, $type = "html")
		{
			if (! isset($this->template[$name]))
				{
					throw new Exception("Bitte geben Sie ein Template zur Ausgabe an!");
				}
				
			if (! isset($this->template[$name]))
				{
					$this->load($name);
				}
				
			$timers = "<br/>";
			
			if (isset($_SESSION["TIMER.MYSQL"])) $timers .= $_SESSION["TIMER.MYSQL"];
			if (isset($_SESSION["TIMER.MSSQL"])) $timers .= $_SESSION["TIMER.MSSQL"];
			if (isset($_SESSION["TIMER.PHP"]))   $timers .= $_SESSION["TIMER.PHP"];
			
			$this->assign($name, "<!--ERRORS-->", $_SESSION["_Errors"]);
			$this->assign($name, "<!--TIMERS-->", $timers);
			
			$buffer = $this->get_parsed($name);
			
			$this->special_chars($buffer);
			
			if ($this->do_compress)
				{
					$this->compress($buffer);
				}
				
			// save all previously sent output (with byte-order-mark-filter)
			$obget = trim(ob_get_clean());
			$obget = str_replace("\xef\xbb\xbf", "", $obget);   //BOM
			$obget = strip_tags(utf8_encode($obget));
			
			// if errormessages were printed, append hidden
			if (! empty($obget))
				{
					if ($this->debug_off)
						{
							$buffer .= "\n<!--\n".$obget."\n-->";
						}
					else
						{
							$buffer .= "<hr/>".$obget;
						}
				}
				
			// GZIP ON | OFF
			if ($this->accepts_gzip())
				{
					$buffer = gzencode($buffer, 9);
				}
				
			$this->headers($type);
			header("Content-Length: ".strlen($buffer));
			echo $buffer;
		}
		
		/**
		 * do replacements (if using 'assign') and
		 * returns array of parsed templates
		 *
		 * @param   string  template file name
		 *
		 * @return  array	templates
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get_parsed($name)
		{
			if (array_key_exists($name, $this->assigned))
				{
					return str_replace(array_keys($this->assigned[$name]), array_values($this->assigned[$name]), $this->template[$name]);
				}
			return $this->template[$name];
		}
		
		/**
		 * send headers to browser (no cookies after this point!)
		 *
		 * @param   string  type of output (html,css,js,...)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function headers($type = "html")
		{
			// send all headers to the browser
			header('Content-Language: de');
			header('Content-Type: text/' . $type . '; charset=UTF-8;');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Content-Transfer-Encoding: UTF-8');
			header('Date: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Accept-Ranges: bytes');
			header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: max-age=0');
			header('Pragma: no-cache');
			if ($this->accepts_gzip())
				{
					header('X-Compression: gzip');
					header('Content-Encoding: gzip');
					header('Vary: Accept-Encoding');
				}
			else
				{
					header('X-Compression: None');
				}
		}
		
		/**
		 * wrap long words from $text at $maxlen (=64 default)
		 *
		 * @param   string  text to wrap
		 * @param   string  maximum length to wrap at
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function wraplongwords(&$text, $maxlen = 64)
		{
			$mytext  = explode(" ", trim($text));
			$newtext = array();
			foreach($mytext as $k => $txt)
			{
				if (strlen($txt) > $maxlen)
					{
						$txt = wordwrap($txt, $maxlen, "- ", true);
					}
				$newtext[] = $txt;
			}
			$text = implode(" ", $newtext);
		}
		
		/**
		 * replace newlines with invisible character (to save newlines in
		 * textareas for example)
		 *
		 * @param   string  text to save newlines
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function save_newlines(&$text)
		{
			$text = str_replace("\n", chr(31), $text);
		}
		
		/**
		 * revert saved newlines to visible
		 *
		 * @param   string  text to restore newlines from
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function restore_newlines(&$text)
		{
			$text = str_replace(chr(31), "\n", $text);
		}
		
		/**
		 * check, if browser is accepting gzip or not an return boolean
		 *
		 * @return  boolean   returns TRUE or FALSE
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function accepts_gzip()
		{
			if (! isset($_SERVER['HTTP_ACCEPT_ENCODING'])) return false;
			$accept = str_replace(" ", "", strtolower($_SERVER['HTTP_ACCEPT_ENCODING']));
			$accept = explode(",", $accept);
			return (in_array("gzip", $accept) === true);
		}
		
		/**
		 * replace special chars to html-wellformed equivalents
		 *
		 * @param   string  text to transform
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function special_chars(&$content)
		{
			$trans = array(
			             chr(228) => "&auml;",
			             chr(246) => "&ouml;",
			             chr(252) => "&uuml;",
			             chr(196) => "&Auml;",
			             chr(214) => "&Ouml;",
			             chr(220) => "&Uuml;",
			             chr(171) => "&laquo;",
			             chr(187) => "&raquo;",
			             chr(223) => "&szlig;"
			         );
			$content = strtr($content, $trans);
		}
		
		/**
		 * return a generated select-html-tag with $selected
		 *
		 * @param   mixed   array or list of names separated with '|' to
		 * 					 create a select menu from
		 * @param   string  name of item selected (optional)
		 *
		 * @return  string  html select menu
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function create_select($array, $selected = "")
		{
			$ret = "";
			
			if (! is_array($array)) $array = explode("|", $array);
			
			if (is_array($array) && count($array) > 1)
				{
					foreach($array as $key => $value)
					{
						if ($selected == $value) $ret.="<option value=\"$value\" selected=\"selected\">$value</option>";
						else $ret.="<option value=\"$value\">$value</option>";
					}
				}
			return $ret;
		}
		
		/**
		 * the assign method for complete arrays
		 *
		 * @param   array   index <!--KEY--> is set to $value from
		 * @param   array   contains keys and values to set data
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function replace_data_from_array(&$data, &$array)
		{
			foreach($array as $key => $value)
			{
				$data["<!--".strtoupper($key)."-->"] = $value;
			}
		}
		
		/**
		 * return a right trimmed text with estimated $maxlen width
		 *
		 * @param   string  text to align right
		 * @param   int     width of line (=16 default)
		 *
		 * @return  string  aligned text (filled with spaces)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function align_right($text, $maxlen = 16)
		{
			$len = strlen($text);
			if ($len > $maxlen) return $text;
			return str_repeat(" ", $maxlen - $len).$text;
		}
		
		/**
		* return menu entry
		*
		* @param   string    name of the menu entry
		* @param   string    href of link
		* @param   char      access key
		* @param   boolean   changes class of entry, if true
		* @param   string    target of link (default:_self)
		*
		* @return  string    menu entry
		*
		* @access  public
		*
		* @author  patrick.kracht
		*/
		public function menu_get_entry($name, $href, $access_key = false, $selected = false, $target = "_self")
		{
			$return  = '<li><a '.(is_string($access_key) ? 'accesskey="'.$access_key.'" ' : '');
			$return .= ($selected ? 'class="selected" ' : '').'href="'.$href.'" target="'.$target.'">'.$name.'</a></li>';
			return $return;
		}
		
		
	}

?>
