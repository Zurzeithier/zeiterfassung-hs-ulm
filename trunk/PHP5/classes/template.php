<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Template:: class implements the HTML-templates and output-engine.
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
 class Template
 {
  protected static $template     = array();
  protected static $assigned     = array();
  protected static $debugoff     = true;
  protected static $performance  = true;
  protected static $debugout     = "";
  protected static $php_perform  = "";
  protected static $sql_perform  = "";
  protected static $tpl_folder   = "./templates/";
  
/**
 * constructor read config-file and initializes the vars
 *
 * @param   string  config-file-location
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function __construct( $config_file = "./configs/template.conf.php" )
  {
   if ( file_exists( $config_file ) )
   {
    // import settings for connection
    include( $config_file );
    self::$tpl_folder = $tpl_folder;
   }
   self::$debugoff     = ( isset( $tpl_compress ) ? $tpl_compress : true );
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
   if(!ob_get_length() || !ob_get_level()) ob_start();
   session_cache_limiter( 'must-revalidate' );
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
  public function load( $name, $force_reload = false )
  {
   // default replacements PAGE and ACTION
   self::assign( $name, "{{PAGE}}", $_SESSION["PageID_NOW"] );
   self::assign( $name, "{{ACTION}}", $_SESSION["Action"] );
   
   // if template loaded, don't waste time and return
   if ( isset( self::$template[$name] ) && ! $force_reload ) return;
   
   // load template or die
   $filename = self::$tpl_folder . $name;
   if ( ! file_exists( $filename ) || is_dir( $filename ) )
   {
    echo ob_get_clean();
	return;
   }
   self::$template[$name] = file_get_contents( $filename );
   
   // prepare array for assigned substitutions
   if ( ! array_key_exists( $name, self::$assigned ) )
   {
   	array_push( self::$assigned, array( $name => array() ) );
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
  public function assign( $name, $search, $replace = "EMPTY" )
  {
   if ( is_array( $search ) )
   {
    foreach( $search as $key => $value ) self::$assigned[$name][$key] = $value;
   }
   else
   { 
    self::$assigned[$name][$search] = $replace;
   }
  }
  
/**
 * returns parsed content of template in buffer
 *
 * @param   string  template file name
 * @param   boolean force reloading buffer content
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function get( $name, $force_reload = false )
  {
   if ( ! isset( self::$template[$name] ) || $force_reload ) self::load( $name, $force_reload );
   self::parsed( $name );
   self::special_chars( self::$template[$name] );
   return self::$template[$name];
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
  public function compress( $name )
  {
   $length = strlen( self::$template[$name] );
   $s = array( "\t" => "", "\n" => " ", "\r" => " ", "   " => " ", "  " => " ", "> <" => "><" );
   do
   {
    $len1 = strlen( self::$template[$name] );
    self::$template[$name] = str_replace( array_keys( $s ), array_values( $s ), self::$template[$name] );
   } while ( $len1 > strlen( self::$template[$name] ) );
   self::restore_newlines( self::$template[$name] );
  }
  
/**
 * finally sends complete template $name, parsed 
 * and compressed (if setup in config-file)
 *
 * @param   string  template file name
 * @param   string  type of output (html,js,css...)
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function output( $name, $type = "html" )
  {
   if ( ! isset( $name ) ) 
   {
    return trigger_error( "Bitte geben Sie ein Template zur Ausgabe an!" );
   }
   if ( ! isset( self::$template[$name] ) ) self::load( $name );
   
   self::set_sql_performance();
   self::set_php_performance( $name );
   self::parsed( $name );
   self::special_chars( self::$template[$name] );
   if( self::$debugoff ) self::compress( $name );
   
   // save all previously sent output (with byte-order-mark-filter)
   $obget = trim( ob_get_clean() );
   $obget = str_replace( "\xef\xbb\xbf", "", $obget ); //BOM
   $obget = strip_tags( utf8_encode( $obget ) );
   
   // if errormessages were printed, append hidden 
   if ( ! empty( $obget ) ) self::$debugout = $obget;
   
   // DEBUGGING (only HTML pages!)
   if ( $type == "html" && ( ! empty( self::$debugout ) || self::$performance ) )
   {
    self::$template[$name] .= "\n\n<!--\n";
    if ( ! empty( self::$debugout ) )
    {
     self::$template[$name] .= "\nDEBUG MESSAGES\n\n".self::$debugout."\n\n";
    }
    self::$template[$name] .= self::$php_perform;
    self::$template[$name] .= self::$sql_perform;
    self::$template[$name] .= "\n-->\n";
   }
   
   // GZIP ON | OFF
   if ( self::accepts_gzip() )
   {
   	self::$template[$name] = gzencode( self::$template[$name], 9 );
   }
   
   self::headers( $type );
   header( "Content-Length: ".strlen( self::$template[$name] ) );
   echo self::$template[$name];
  }
  
/**
 * replace array of assigned replacements (if using 'assign')
 *
 * @param   string  template file name
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function parsed( $name )
  {
   if ( array_key_exists( $name, self::$assigned ) )
   {
   	self::$template[$name] = str_replace( array_keys( self::$assigned[$name] ), array_values( self::$assigned[$name] ), self::$template[$name] );
   }
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
  public function headers( $type = "html" )
  {
   // send all headers to the browser
   header( 'Content-Language: de' );
   header( 'Content-Type: text/' . $type . '; charset=UTF-8;' ); 
   header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
   header( 'Content-Transfer-Encoding: UTF-8' );
   header( 'Date: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
   header( 'Accept-Ranges: bytes' );
   header( 'Expires: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
   header( 'Cache-Control: max-age=0' );
   header( 'Pragma: no-cache' );
   if ( self::accepts_gzip() ) 
   {
   	header( 'X-Compression: gzip' );
   	header( 'Content-Encoding: gzip' );
   	header( 'Vary: Accept-Encoding' );
   }
   else
   {
    header( 'X-Compression: None' );
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
  public function wraplongwords( &$text, $maxlen = 64 )
  {
   $mytext  = explode( " ", trim( $text ) );
   $newtext = array();
   foreach( $mytext as $k => $txt )
   {
    if ( strlen($txt) > $maxlen )
    {
     $txt = wordwrap( $txt, $maxlen, "- ", true );
    }
    $newtext[] = $txt;
   }
   $text = implode( " ", $newtext );
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
  public function save_newlines( &$text )
  {
   $text = str_replace( "\n", chr(31), $text );
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
  public function restore_newlines( &$text )
  {
   $text = str_replace( chr(31), "\n", $text );
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
   if ( ! isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) return false;
   $accept = str_replace( " ", "", strtolower( $_SERVER['HTTP_ACCEPT_ENCODING'] ) );
   $accept = explode( ",", $accept );
   return ( in_array( "gzip", $accept ) === true );
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
  public function special_chars( &$content )
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
   $content = strtr( $content, $trans );
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
  public function create_select( $array, $selected = "" )
  {
   $ret = "";
   
   if ( ! is_array( $array ) ) $array = explode( "|", $array ); 
   
   if ( is_array( $array ) && count( $array ) > 1 )
   {
    foreach( $array as $key => $value )
    {
     if ( $selected == $value ) $ret.="<option value=\"$value\" selected=\"selected\">$value</option>";
     else $ret.="<option value=\"$value\">$value</option>";
    }
   }
   return $ret;
  }
  
/**
 * the assign method for complete arrays
 *
 * @param   array   index {{KEY}} is set to $value from 
 * @param   array   contains keys and values to set data
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function replace_data_from_array( &$data, &$array )
  {
   foreach( $array as $key => $value )
   {
   	$data["{{".strtoupper( $key )."}}"] = $value;
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
  public function align_right( $text, $maxlen = 16 )
  {
   $len = strlen( $text );
   if ( $len > $maxlen ) return $text;
   return str_repeat( " ", $maxlen - $len ).$text;
  }
  
/**
 * set php performance measuring (to output hidden in output)
 *
 * @param   string  template name  
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_php_performance( $name )
  {
   $size = _from_bytes( strlen( self::$template[$name] ) );
   $usag = _from_bytes( memory_get_usage( true ) );
   $peak = _from_bytes( memory_get_peak_usage( true ) );
   $time = _msec( PHP_SCRIPT_START, microtime() )." MS";
   
   $ret  = "\nPERFORMANCE ( SCRIPT )\n";
   $ret .= " | generated_html_size  ".self::align_right($size)."\n";
   $ret .= " | memory_get_usage     ".self::align_right($usag)."\n";
   $ret .= " | memory_get_peak_usage".self::align_right($peak)."\n";
   $ret .= " | php5_script_runtime  ".self::align_right($time)."\n";
   self::$php_perform .= $ret;
  }
  
/**
 * set sql performance measuring (to output hidden in output)
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_sql_performance()
  {
   if ( MsSql::is_active() || MySql::is_active() )
   {
   	// append MsSql-performance-output 
    if ( MsSql::is_active() )
    {
     $ret   = "\nPERFORMANCE ( MSSQL )\n";
     $tick  = MsSql::get_query_count()." QS";
     $time  = MsSql::get_query_timer()." MS";
     $ret  .= " | mssql_query_count    ".self::align_right( $tick )."\n";
     $ret  .= " | mssql_query_time     ".self::align_right( $time )."\n";
     self::$sql_perform .= $ret;
    }
    // append MySql-performance-output 
    if ( MySql::is_active() )
    {
     $ret   = "\nPERFORMANCE ( MYSQL )\n";
     $tick  = MySql::get_query_count()." QS";
     $time  = MySql::get_query_timer()." MS";
     $ret  .= " | mysql_query_count    ".self::align_right( $tick )."\n";
     $ret  .= " | mysql_query_time     ".self::align_right( $time )."\n";
     self::$sql_perform .= $ret;
    }
   }
   else
   {
    return trigger_error( 'Bitte laden Sie eine Datenbank-Klasse vor der Ausgabe durch die Template-Klasse!' );
   }
  }
  
 }

?>
