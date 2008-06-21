<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The iSql:: interface declares the SQL-Server-Connection.
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
 */
interface iSql
	{
		/**
		 * constructor read config-file and initializes the vars
		 *
		 * @param   string  config-file-location
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __construct($config_file = "");
		/**
		 * destructor is closing connection, if present
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __destruct();
		/**
		 * init is connecting to the mysql-server and selecting the database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function init();
		/**
		 * connect creates the MySQL connection and returns status
		 *
		 * @return  boolean  status
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function connect();
		/**
		 * selects the MySQL database
		 *
		 * @return  boolean  status
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function select_db();
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function query($qstring);
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function query_all($qstring);
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function unbuffered_query($qstring);
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function fetch_array($query_id=-1);
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function fetch_row($query_id = -1);
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
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function query_first($qstring);
		/**
		 * gets the number of rows in result
		 *
		 * @param   mixed   query resource
		 *
		 * @return  int     number of rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function num_rows($query_id = -1);
		/**
		 * gets the number of rows in result
		 *
		 * @param   mixed   link resource
		 *
		 * @return  int     number of rows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function affected_rows($link_id = -1);
		/**
		 * gets id of last insert query
		 *
		 * @param   mixed   link resource
		 *
		 * @return  int     id of last insert
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function insert_id($link_id = -1);
		/**
		 * frees result of previous query
		 *
		 * @param   mixed   MYSQL result resource to free
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function free_result(&$result);
		/**
		 * closes mysql-connection
		 *
		 * @param   mixed   link resource
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function close($link_id = -1);
		/**
		 * returns array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function show_tables();
		/**
		 * generate html string sql table
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function html_table($tablename);
	}

?>