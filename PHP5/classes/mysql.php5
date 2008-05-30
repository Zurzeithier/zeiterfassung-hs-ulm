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
  private static $link_id;
  private static $query_id;
  private static $query_key;
  private static $query_list;
  private static $mysql_error;
  private static $select_db;
  private static $hostname;
  private static $database;
  private static $password;
  private static $username;
  private static $logging;
  private static $query_time;
  
  public function __construct( $config_file = "./configs/mysql.conf.php5" )
  {
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
  }
  
  public function __destruct()
  {
   if( self::$link_id ) self::close();
  }
  
  public function init()
  {
   if ( ! self::connect() ) trigger_error( 'Konnte keine Verbindung zum Datenbank-Server (' . self::$hostname . ') hergestellt! ' . self::$mysql_error );
   if ( ! self::select_db() ) trigger_error( 'Konnte keinen Zugriff auf die Datenbank (' . self::$database . ') herstellen! ' . self::$mysql_error );
  }
  
  public function connect()
  {
   self::start( "CONNECT" );
   self::$link_id = @mysql_pconnect( self::$hostname, self::$username, self::$password, MYSQL_CLIENT_COMPRESS );
   self::stop();
   if( ! self::$link_id )
   {
    self::$mysql_error = mysql_error();
    return false;
   }
   return true;
  }
  
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
  
  public function query( $qstring, $limit=0, $offset=0 )
  {
   if( $limit!=0 ) $qstring.=" LIMIT ".$offset.",".$limit;
   if( ! self::$link_id ) self::init();
   self::start( "QUERY : " . $qstring );
   self::$query_id = mysql_query( $qstring, self::$link_id );
   self::stop();
   return self::$query_id;
  }
  
  public function query_all( $qstring, $limit=0, $offset=0, $type=MYSQL_ASSOC )
  {
   $array = array();
   self::$query_id = self::query( $qstring, $limit, $offset );
   while( $array[] = self::fetch_array( self::$query_id, $type ) );
   self::free_result( self::$query_id );
   array_pop( $array );
   return $array;
  }
  
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
  
  public function fetch_array( $query_id=-1, $type=MYSQL_ASSOC )
  {
   if( $query_id != -1 ) self::$query_id = $query_id;
   return @mysql_fetch_array( self::$query_id, $type );
  }
  
  public function fetch_row( $query_id=-1 ){
   if( $query_id != -1 ) self::$query_id = $query_id;
   return @mysql_fetch_row( self::$query_id );
  }
  
  public function query_first( $qstring, $limit=0, $offset=0, $type=MYSQL_ASSOC )
  {
   self::$query_id = self::query( $qstring, $limit, $offset );
   $array = self::fetch_array( self::$query_id, $type );
   self::free_result( self::$query_id );
   return $array;
  }
  
  public function num_rows( $query_id=-1 )
  {
   if( $query_id!=-1 ) self::$query_id = $query_id;
   return @mysql_num_rows( self::$query_id );
  }
  
  public function affected_rows( $link_id=-1 )
  {
   if( $link_id!=-1 ) self::$link_id = $link_id;
   return @mysql_affected_rows( self::$link_id );
  }
  
  public function insert_id( $link_id=-1 )
  {
   if( $link_id!=-1 ) self::$link_id = $link_id;
   return @mysql_insert_id( self::$link_id );
  }
  
  public function free_result( &$result )
  {
   @mysql_free_result( $result );
   unset( $result );
  }
  
  public function close( $link_id=-1 )
  {
   if( $link_id!=-1 ) self::$link_id = $link_id;
   @mysql_close( self::$link_id );
  }
  
  public function start( $info )
  {
   if ( strlen( $info ) > 64 ) $info = substr( $info, 0, 61 ) . "...";
   self::$query_list[ self::$query_key ] = array( "INFO" => $info, "START" => microtime() );
  }
  
  public function stop()
  {
   $start = self::$query_list[ self::$query_key ]["START"];
   $stop  = microtime();
   $time  = array_sum( explode( " ", $stop ) ) - array_sum( explode( " ", $start ) );
   self::$query_time += $time;
   self::$query_list[ self::$query_key ]["TIME"]   = $time;
   self::$query_list[ self::$query_key++ ]["STOP"] = $stop;
  }
  
  public function get_query_list()
  {
   return self::$query_list;
  }
  
  public function get_query_count()
  {
   return count( self::$query_list );
  }
  
  public function get_query_timer( $prec = 2 )
  {
   return number_format( 1000 * self::$query_time, $prec, ",", "."  );
  }
      
 }

?>
