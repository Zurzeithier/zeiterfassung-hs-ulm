<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The MySql:: class implements the MySQL-Server-Connection.
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
class MySql extends Core implements iSql
	{
		protected static $link_id;
		protected static $query_id;
		protected static $query_key;
		protected static $mysql_error;
		protected static $select_db;
		protected static $hostname;
		protected static $database;
		protected static $password;
		protected static $username;
		protected static $querytimer;
		
		/**
		 * constructor read config-file and initializes the vars
		 *
		 * @param   string  config-file-location
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($config_file = "./config.mysql.php")
		{
			if (file_exists($config_file))
				include($config_file);
			else
				parent::set_error("config file ".$config_file." not found!");
				
			//Initialisieren
			self::$link_id    = false;
			self::$query_id   = false;
			self::$database   = $database;
			self::$password   = $password;
			self::$hostname   = (isset($hostname) ? $hostname : "localhost");
			self::$username   = (isset($username) ? $username : self::$database);
			self::$querytimer = parent::get_Timer("mysql query time",true);
			
			self::init();
		}
		
		/**
		 * destructor is closing connection, if present
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __destruct()
		{
			if (self::$link_id) self::close();
		}
		
		/**
		 * init is connecting to the mysql-server and selecting the database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function init()
		{
			if (! self::connect()) parent::set_error("unable to connect to ".self::$hostname."! ".self::$mysql_error);
			if (! self::select_db()) parent::set_error("unable to access database ".self::$database."! ".self::$mysql_error);
		}
		
		/**
		 * connect creates the MySQL connection and returns status
		 *
		 * @return  boolean  status
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function connect()
		{
			self::$querytimer->start();
			self::$link_id = @mysql_pconnect(self::$hostname, self::$username, self::$password);
			self::$querytimer->stop();
			if (! self::$link_id)
				{
					self::$mysql_error = mysql_error();
					return false;
				}
			return true;
		}
		
		/**
		 * selects the MySQL database
		 *
		 * @return  boolean  status
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function select_db()
		{
			self::$querytimer->start();
			self::$select_db = @mysql_select_db(self::$database, self::$link_id);
			self::$querytimer->stop();
			if (! self::$select_db)
				{
					self::$mysql_error = mysql_error();
					return false;
				}
			return true;
		}
		
		/**
		 * query sends querystring to server and returns queryid
		 *
		 * @param   string  querystring
		 * @param   int     start retrieving records from record
		 * @param   int     limits number of record returned
		 *
		 * @return  mixed   returns MYSQL result resource on success,
		 *                  TRUE if no rows were returned, or FALSE on error
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function query($qstring, $limit=0, $offset=0)
		{
			if ($limit!=0) $qstring.=" LIMIT ".$offset.",".$limit;
			if (! self::$link_id) self::init();
			self::$querytimer->start();
			self::$query_id = mysql_query($qstring, self::$link_id);
			self::$querytimer->stop();
			return self::$query_id;
		}
		
		/**
		 * queryall sends querystring to server and returns whole result as array
		 *
		 * @param   string  querystring
		 * @param   int     start retrieving records from record
		 * @param   int     limits number of record returned
		 * @param   const   the type of array that is to be fetched
		 *                  (default) MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
		 *
		 * @return  array   returns whole array containing all records
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function query_all($qstring, $limit=0, $offset=0, $type=MYSQL_ASSOC)
		{
			$array = array();
			self::$query_id = self::query($qstring, $limit, $offset);
			while ($array[] = self::fetch_array(self::$query_id, $type));
			self::free_result(self::$query_id);
			array_pop($array);
			return $array;
		}
		
		/**
		 * unbuffered_query (like query, but unbuffered)
		 *
		 * @param   string  querystring
		 * @param   int     start retrieving records from record
		 * @param   int     set low-priority commands (1) or not (0)
		 * @param   int     limits number of record returned
		 *
		 * @return  mixed   returns MYSQL result resource on success,
		 *                  TRUE if no rows were returned, or FALSE on error
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function unbuffered_query($qstring, $LOW=0, $limit=0, $offset=0)
		{
			if ($LOW==1)
				{
					$qstring = preg_replace(
					               "/^(INSERT|UPDATE|DELETE|REPLACE)(.*)/si",
					               "\\1 LOW_PRIORITY\\2",
					               $qstring
					           );
				}
			if ($limit!=0)$qstring .= " LIMIT $offset,$limit";
			if (!self::$link_id) self::init();
			self::$querytimer->start();
			self::$query_id = mysql_unbuffered_query($qstring, self::$link_id);
			self::$querytimer->stop();
			return self::$query_id;
		}
		
		/**
		 * fetch a result row as an associative array, a numeric array, or both
		 *
		 * @param   mixed   query resource
		 * @param   const   the type of array that is to be fetched
		 *                  (default) MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
		 *
		 * @return  mixed   returns an array that corresponds to the fetched row,
		 *                  or FALSE if there are no more rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function fetch_array($query_id=-1, $type=MYSQL_ASSOC)
		{
			if ($query_id != -1) self::$query_id = $query_id;
			self::$querytimer->start();
			$return = @mysql_fetch_array(self::$query_id, $type);
			self::$querytimer->stop();
			return $return;
		}
		
		/**
		 * get row as enumerated array
		 *
		 * @param   mixed   query resource
		 *
		 * @return  mixed   returns an array that corresponds to the fetched row,
		 *                  or FALSE if there are no more rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function fetch_row($query_id=-1)
		{
			if ($query_id != -1) self::$query_id = $query_id;
			self::$querytimer->start();
			$return = @mysql_fetch_row(self::$query_id);
			self::$querytimer->stop();
			return $return;
		}
		
		/**
		 * query_first sends querystring to server and returns array
		 *
		 * @param   string  querystring
		 * @param   int     start retrieving records from record
		 * @param   int     limits number of record returned
		 * @param   const   the type of array that is to be fetched
		 *                  (default) MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
		 *
		 * @return  array   returns array containing first records
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function query_first($qstring, $limit=0, $offset=0, $type=MYSQL_ASSOC)
		{
			self::$query_id = self::query($qstring, $limit, $offset);
			$return = self::fetch_array(self::$query_id, $type);
			self::free_result(self::$query_id);
			return $return;
		}
		
		/**
		 * gets the number of rows in result
		 *
		 * @param   mixed   query resource
		 *
		 * @return  int     number of rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function num_rows($query_id=-1)
		{
			if ($query_id!=-1) self::$query_id = $query_id;
			self::$querytimer->start();
			$return = @mysql_num_rows(self::$query_id);
			self::$querytimer->stop();
			return $return;
		}
		
		/**
		 * gets the number of rows in result
		 *
		 * @param   mixed   link resource
		 *
		 * @return  int     number of rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function affected_rows($link_id=-1)
		{
			if ($link_id!=-1) self::$link_id = $link_id;
			self::$querytimer->start();
			$return = @mysql_affected_rows(self::$link_id);
			self::$querytimer->stop();
			return $return;
		}
		
		/**
		 * gets id of last insert query
		 *
		 * @param   mixed   link resource
		 *
		 * @return  int     id of last insert
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function insert_id($link_id=-1)
		{
			if ($link_id!=-1) self::$link_id = $link_id;
			self::$querytimer->start();
			$return = @mysql_insert_id(self::$link_id);
			self::$querytimer->stop();
			return $return;
		}
		
		/**
		 * frees result of previous query
		 *
		 * @param   mixed   MYSQL result resource to free
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function free_result(&$result)
		{
			@mysql_free_result($result);
			unset($result);
		}
		
		/**
		 * closes mysql-connection
		 *
		 * @param   mixed   link resource
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function close($link_id=-1)
		{
			if ($link_id!=-1) self::$link_id = $link_id;
			@mysql_close(self::$link_id);
		}
		
		/**
		 * returns array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_tables()
		{
			$return = array();
			$result = self::query("SHOW TABLES;");
			while ($row = self::fetch_row($result))
				$return[] = $row[0];
			return $return;
		}
		
		/**
		 * generate html string sql table
		 *
		 * @return	array	array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function html_table($tablename)
		{
			$dump   = "";
			$first  = true;
			$query  = "SELECT * FROM $tablename";
			$result = self::query($query);
			
			// append title and start table
			$dump  .= "<table border=\"1\">";
			while ($row = self::fetch_array($result))
				{
					// append only keys on first line
					if ($first)
						{
							$rows  = count($row);
							$dump .= "<tr><td colspan=$rows><b>$query</b></td></tr>";
							$dump .= "<tr><td>";
							$dump .= implode("</td><td>", array_keys($row));
							$dump .= "</td></tr>";
							$first = false;
						}
					// append data rows
					$dump .= "<tr><td>";
					$dump .= implode("</td><td>", $row);
					$dump .= "</td></tr>";
				}
			if ($first) $dump .= "<tr><td><b>$query</b> (empty)</td></tr>";
			// close table
			$dump  .= "</table>";
			return $dump;
		}
		
	}

?>
