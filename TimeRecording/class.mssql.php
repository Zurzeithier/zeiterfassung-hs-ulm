<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The MsSql:: class implements the MsSQL-Server-Connection.
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
class MsSql extends Controller implements iSql
	{
		private $link_id;
		private $query_id;
		private $query_key;
		private $mssql_error;
		private $select_db;
		private $hostname;
		private $hostport;
		private $database;
		private $password;
		private $username;
		private $querytimer;
		
		/**
		 * constructor initializes the vars
		 *
		 * @param   string  config-file-location
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($parameters = array())
		{
			//Initialisieren
			$this->link_id    = false;
			$this->query_id   = false;
			$this->hostname   = (isset($parameters[0])) ? $parameters[0] : "localhost";
			$this->hostport   = (isset($parameters[1])) ? $parameters[1] : 3306;
			$this->database   = (isset($parameters[2])) ? $parameters[2] : false;
			$this->username   = (isset($parameters[3])) ? $parameters[3] : false;
			$this->password   = (isset($parameters[4])) ? $parameters[4] : false;
			
			if (! $this->hostname || ! $this->hostport || ! $this->database || ! $this->username || ! $this->password)
				{
					throw new Exception("Es fehlt mindestens ein Parameter um die MsSQL-Verbindung herzustellen!",101);
				}
				
			parent::register("Timer",array("MsSQL",true),"TIMER.MSSQL");
			
			$this->init();
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
			if ($this->link_id) $this->close();
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
			if (! $this->connect())
				{
					throw new Exception("Konnte keine Verbindung zum MsSQL-Server '".$this->hostname."' herstellen! (".$this->mssql_error.")",102);
				}
			if (! $this->select_db())
				{
					throw new Exception("Konnte auf die MsSQL-Datenbank '".$this->database."' nicht zugreifen! (".$this->mssql_error.")",103);
				}
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
			$reconnects = 0;
			$_SESSION["TIMER.MSSQL"]->start();
			$this->link_id = @mssql_pconnect($this->hostname.":".$this->hostport, $this->username, $this->password);
			$_SESSION["TIMER.MSSQL"]->stop();
			if (! $this->link_id)
				{
					$this->mssql_error = mssql_get_last_message();
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
			$_SESSION["TIMER.MSSQL"]->start();
			$this->select_db = @mssql_select_db($this->database, $this->link_id);
			$_SESSION["TIMER.MSSQL"]->stop();
			if (! $this->select_db)
				{
					$this->mssql_error = mssql_get_last_message();
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
			if (! $this->link_id) $this->init();
			$_SESSION["TIMER.MSSQL"]->start();
			$this->query_id = mssql_query($qstring, $this->link_id);
			$_SESSION["TIMER.MSSQL"]->stop();
			return $this->query_id;
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
		public function query_all($qstring, $limit=0, $offset=0, $type=MSSQL_ASSOC)
		{
			$return = array();
			$this->query_id = $this->query($qstring, $limit, $offset);
			while ($return[] = $this->fetch_array($this->query_id, $type));
			$this->free_result($this->query_id);
			array_pop($return);
			return $return;
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
			if (!$this->link_id) $this->init();
			$_SESSION["TIMER.MSSQL"]->start();
			$this->query_id = mssql_query($qstring, $this->link_id);
			$_SESSION["TIMER.MSSQL"]->stop();
			return $this->query_id;
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
		public function fetch_array($query_id=-1, $type=MSSQL_ASSOC) # MSSQL_BOTH, MSSQL_NUM, MSSQL_ASSOC
		{
			if ($query_id != -1) $this->query_id = $query_id;
			$_SESSION["TIMER.MSSQL"]->start();
			$return = @mssql_fetch_array($this->query_id, $type);
			$_SESSION["TIMER.MSSQL"]->stop();
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
			if ($query_id != -1) $this->query_id = $query_id;
			$_SESSION["TIMER.MSSQL"]->start();
			$return = @mssql_fetch_row($this->query_id);
			$_SESSION["TIMER.MSSQL"]->stop();
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
		public function query_first($qstring, $limit=0, $offset=0, $type=MSSQL_ASSOC)
		{
			$this->query_id = $this->query($qstring, $limit, $offset);
			$return = $this->fetch_array($this->query_id, $type);
			$this->free_result($this->query_id);
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
		public function num_rows($query_id =- 1)
		{
			if ($query_id!=-1) $this->query_id = $query_id;
			$_SESSION["TIMER.MSSQL"]->start();
			$return = @mssql_num_rows($this->query_id);
			$_SESSION["TIMER.MSSQL"]->stop();
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
		public function affected_rows($link_id =- 1)
		{
			if ($link_id!=-1) $this->link_id = $link_id;
			$_SESSION["TIMER.MSSQL"]->start();
			$return = @mssql_rows_affected($this->link_id);
			$_SESSION["TIMER.MSSQL"]->stop();
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
		public function insert_id($link_id =- 1)
		{
			if ($link_id!=-1) $this->link_id = $link_id;
			return $this->query_first("SELECT @@IDENTITY");
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
			@mssql_free_result($result);
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
		public function close($link_id =- 1)
		{
			if ($link_id!=-1) $this->link_id = $link_id;
			@mssql_close($this->link_id);
		}
		
		/**
		 * returns array of all tables in database
		 *
		 * @return	array	array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_tables()
		{
			$return = array();
			$result = $this->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES;");
			while ($row = $this->fetch_row($result))
				{
					$return[] = $row[0];
				}
			return $return;
		}
		
		/**
		 * generate html string sql table
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function html_table($tablename,$where="")
		{
			$dump   = "";
			$first  = true;
			$query  = "SELECT * FROM $tablename $where";
			$result = $this->query($query);
			
			// append title and start table
			$dump  .= "<table border=\"1\">";
			while ($row = $this->fetch_array($result))
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
