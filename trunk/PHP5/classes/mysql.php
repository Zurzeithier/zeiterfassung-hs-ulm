<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The MySql:: class implements the MySQL-Server-Connection.
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
 class MySql implements iSql
 {
  protected static $link_id;
  protected static $query_id;
  protected static $query_key;
  protected static $query_list;
  protected static $mysql_error;
  protected static $select_db;
  protected static $hostname;
  protected static $database;
  protected static $password;
  protected static $username;
  protected static $logging;
  protected static $query_time;
  protected static $is_active = false;
  
/**
 * constructor read config-file and initializes the vars
 *
 * @param   string  config-file-location
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function __construct( $config_file = "./configs/mysql.conf.php" )
  {
   self::$is_active = false;
   if ( file_exists( $config_file ) )
   {
    // import settings for connection
    include( $config_file );
   }
   else
   {
   	trigger_error( 'Konfigurationsdatei ('.$config_file.') konnte nicht gefunden werden!' );
   }
   
   //Initialisieren
   self::$query_key  = 0;
   self::$link_id    = false;
   self::$query_id   = false;
   self::$query_list = array();
   self::$database   = $database;
   self::$password   = $password;
   self::$hostname   = ( isset( $hostname ) ? $hostname : 'localhost' );
   self::$logging    = ( isset( $logging )  ? $logging  : false );
   self::$username   = ( isset( $username ) ? $username : self::$database );
   
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
   if( self::$link_id ) self::close();
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
   if ( ! self::connect() ) trigger_error( 'Konnte keine Verbindung zum Datenbank-Server (' . self::$hostname . ') hergestellt! ' . self::$mysql_error );
   if ( ! self::select_db() ) trigger_error( 'Konnte keinen Zugriff auf die Datenbank (' . self::$database . ') herstellen! ' . self::$mysql_error );
  }
  
/**
 * returns boolean active flag
 * 
 * @return  boolean  flag of activity
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function is_active()
  {
   return self::$is_active;
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
   self::start( "CONNECT" );

   do
   {
    self::$link_id = @mysql_pconnect( self::$hostname, self::$username, self::$password );
   }while ( ! self::$link_id && $reconnects++ < 3 );
   
   self::stop();
   if( ! self::$link_id )
   {
    self::$mysql_error = mysql_error();
    return false;
   }
   self::$is_active = true;
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
   self::$select_db = @mysql_select_db( self::$database, self::$link_id );
   if( ! self::$select_db )
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
  public function query( $qstring, $limit=0, $offset=0 )
  {
   if( $limit!=0 ) $qstring.=" LIMIT ".$offset.",".$limit;
   if( ! self::$link_id ) self::init();
   self::start( "QUERY : " . $qstring );
   self::$query_id = mysql_query( $qstring, self::$link_id );
   self::stop();
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
  public function query_all( $qstring, $limit=0, $offset=0, $type=MYSQL_ASSOC )
  {
   $array = array();
   self::$query_id = self::query( $qstring, $limit, $offset );
   while( $array[] = self::fetch_array( self::$query_id, $type ) );
   self::free_result( self::$query_id );
   array_pop( $array );
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
  public function unbuffered_query( $qstring, $LOW=0, $limit=0, $offset=0 )
  {
   if( $LOW==1 )
   {
    $qstring = preg_replace(
     "/^(INSERT|UPDATE|DELETE|REPLACE)(.*)/si",
     "\\1 LOW_PRIORITY\\2",
     $qstring
    );
   }
   if( $limit!=0 )$qstring .= " LIMIT $offset,$limit";
   if( !self::$link_id ) self::init();
   self::start( "UNBUFFERED_QUERY : " . $qstring );
   self::$query_id = mysql_unbuffered_query( $qstring, self::$link_id );
   self::stop();
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
  public function fetch_array( $query_id=-1, $type=MYSQL_ASSOC )
  {
   if( $query_id != -1 ) self::$query_id = $query_id;
   return @mysql_fetch_array( self::$query_id, $type );
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
  public function fetch_row( $query_id=-1 ){
   if( $query_id != -1 ) self::$query_id = $query_id;
   return @mysql_fetch_row( self::$query_id );
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
  public function query_first( $qstring, $limit=0, $offset=0, $type=MYSQL_ASSOC )
  {
   self::$query_id = self::query( $qstring, $limit, $offset );
   $array = self::fetch_array( self::$query_id, $type );
   self::free_result( self::$query_id );
   return $array;
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
  public function num_rows( $query_id=-1 )
  {
   if( $query_id!=-1 ) self::$query_id = $query_id;
   return @mysql_num_rows( self::$query_id );
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
  public function affected_rows( $link_id=-1 )
  {
   if( $link_id!=-1 ) self::$link_id = $link_id;
   return @mysql_affected_rows( self::$link_id );
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
  public function insert_id( $link_id=-1 )
  {
   if( $link_id!=-1 ) self::$link_id = $link_id;
   return @mysql_insert_id( self::$link_id );
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
  public function free_result( &$result )
  {
   @mysql_free_result( $result );
   unset( $result );
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
  public function close( $link_id=-1 )
  {
   if( $link_id!=-1 ) self::$link_id = $link_id;
   @mysql_close( self::$link_id );
   self::$is_active = false;
  }
  
/**
 * starts measuring execution-performance using info-tag
 *
 * @param   string  info for started measuring startpoint
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function start( $info )
  {
   if ( strlen( $info ) > 64 ) $info = substr( $info, 0, 61 ) . "...";
   self::$query_list[ self::$query_key ] = array( "INFO" => $info, "START" => microtime() );
  }
  
/**
 * stopps measuring execution-performance
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function stop()
  {
   $start = self::$query_list[ self::$query_key ]["START"];
   $stop  = microtime();
   $time  = array_sum( explode( " ", $stop ) ) - array_sum( explode( " ", $start ) );
   self::$query_time += $time;
   self::$query_list[ self::$query_key ]["TIME"]   = $time;
   self::$query_list[ self::$query_key++ ]["STOP"] = $stop;
  }
  
/**
 * returns complete list of all queries done
 *
 * @return  string   querylist
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function get_query_list()
  {
   return self::$query_list;
  }
  
/**
 * returns number of queries done
 *
 * @return  int     querycount
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function get_query_count()
  {
   return count( self::$query_list );
  }
  
/**
 * returns total time of all queries 
 *
 * @param   int      precision
 *
 * @return  string   sql-query-time
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function get_query_timer( $prec = 2 )
  {
   return number_format( 1000 * self::$query_time, $prec, ",", "."  );
  }
      
 }

?>
