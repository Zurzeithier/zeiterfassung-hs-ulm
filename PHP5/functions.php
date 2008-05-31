<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The functions collection providing useful functions
 *
 * Copyright 2008   Patrick Kracht <patrick.kracht@googlemail.com>
 *                  Thorsten Moll  <thorsten.moll@googlemail.com>
 *
 * @author          Patrick Kracht <patrick.kracht@googlemail.com>
 *                  Thorsten Moll  <thorsten.moll@googlemail.com>
 */

 // autoload function for including class-files
 function __autoload( $class_name )
 {
  $class_name = strtolower( $class_name );
  if ( file_exists( "./classes/$class_name.php" ) )
  {
   require_once "./classes/$class_name.php";
  }
  else
  {
   trigger_error( 'Klasse '.$class_name.' wurde nicht gefunden!' );
  }
 }
 
 // init-function for main program
 function __init_main()
 {
  // turn on all errormessages
  error_reporting( E_ALL );
  
  // define some values
  setlocale ( LC_ALL, 'de_DE' );
  define( "PHP_SCRIPT_START", microtime() );
  define( "SESSION_TIMEOUT", 900 );
  
  // create output buffer (for suppressing error messages) 
  ob_start();
  ob_clean();
 }
 
 
 
/**
 * strip slashes from string, for array_walk_recursive and
 * array_walk usage
 *
 * @param   string  text to strip slashes
 * 
 * @return  string  text without doubleslashes
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _strip( &$item )
 {
  if ( ! is_string( $item ) ) return false;
  $item = stripslashes( $item );
 }
 
/**
 * add slashes to string, for array_walk_recursive and
 * array_walk usage
 *
 * @param   string  text to add slashes
 * 
 * @return  string  text with doubleslashes
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _addsl( &$item )
 {
  if ( ! is_string( $item ) ) return false;
  $item = addslashes( $item );
 }
 
/**
 * trims string, for array_walk_recursive and
 * array_walk usage
 *
 * @param   string  text to trim
 * 
 * @return  string  trimmed text
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _ptrim( &$item )
 {
  if ( ! is_string( $item ) ) return false;
  $item = trim( $item );
 }
 
/**
 * clean special chars from string, for array_walk_recursive and
 * array_walk usage
 *
 * @param   string  text to clean
 * 
 * @return  string  cleaned text
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _clean( &$item )
 {
  if ( ! is_string( $item ) ) return false;
  $item = stripslashes( $item );
  $item = htmlspecialchars( $item, ENT_QUOTES, "UTF-8" );
  $item = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $item );
  $item = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $item );
  $trans_tbl = get_html_translation_table( HTML_ENTITIES );
  $trans_tbl = array_flip( $trans_tbl );
  $item = strtr( $item, $trans_tbl );
  $item = trim( $item );
 }
 
/**
 *convert string to HTML-source, for array_walk_recursive and
 * array_walk usage
 *
 * @param   string  text to convert
 * 
 * @return  string  converted text
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _xhtml( &$item )
 {
  $trans = get_html_translation_table( HTML_ENTITIES );
  $item  = strtr( $item, $trans);
 }
 
/**
 * decode an utf-8 string, for array_walk_recursive and
 * array_walk usage
 *
 * @param   string  utf-8 text
 * 
 * @return  string  iso text
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _utf8_decode( &$item )
 {
  $item = utf8_decode( $item ); 
 }
 
/**
 * calculate time difference between microtime start and 
 * stop (or now) with precision in milliseconds
 *
 * @param   string  microtime php was started
 * @param   string  microtime php was stopped
 * @param   int     precision of output
 * 
 * @return  string  formated time elapsed in milliseconds
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _msec( $start = PHP_SCRIPT_START, $stop = -1, $prec = 2 )
 {
  if ( $stop < 0 ) $stop = microtime();
  $seconds = array_sum( explode(" ", $stop) ) - array_sum( explode(" ", $start) );
  if( $prec < 0 ) return ( 1000.0 * $seconds );
  else return number_format( ( 1000.0 * $seconds ), intval( $prec ), ",", "." );
 }
 
/**
 * calculate time difference between microtime start and 
 * stop (or now) with precision in seconds
 *
 * @param   string  microtime php was started
 * @param   string  microtime php was stopped
 * @param   int     precision of output
 * 
 * @return  string  formated time elapsed in seconds
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _sec( $start = PHP_SCRIPT_START, $stop = -1, $prec = 2 )
 {
  if ( $stop < 0 ) $stop = microtime();
  $seconds = array_sum( explode(" ", $stop) ) - array_sum( explode(" ", $start) );
  if( $prec < 0 ) return $seconds;
  else return number_format( $seconds, intval( $prec ), ",", "." );
 }
 
/**
 * convert php.ini values from GB, MB... to bytes
 *
 * @param   string  string formated bytes
 * 
 * @return  int     number of bytes
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _to_bytes( $val )
 {
  $val  = trim($val);
  $last = strtolower($val{strlen($val)-1});
  $val  = intval($val);
  switch($last){
   case 'g': $val *= 1024;
   case 'm': $val *= 1024;
   case 'k': $val *= 1024;
  }
  return $val;
 }

/**
 * convert string to blob
 *
 * @param   string  normal text or something
 * 
 * @return  string  blob (hex) formatted string
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _string2blob( $string )
 {
  return "0x".bin2hex( $string."\n" );
 }

/**
 * convert php.ini values bytes to KB, MB, GB... 
 *
 * @param   int     value in bytes
 * @param   int     precision
 * @param   int     shifting amount
 * 
 * @return  string  formatted bytes
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _from_bytes( $val, $prec = 2, $cnt = 0 )
 {
  $ext = array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
  $val = trim($val);
  if(!is_numeric($val)) return $val;
  else while($val>=1024){
   $val/=1024;
   $cnt++;
  }
  return number_format( $val, $prec, ",", "." )." ".$ext[$cnt];
 }
 
/**
 * convert php.ini values bytes to KB, MB, GB... 
 *
 * @param   string  normal text, no html
 * @param   int     maximum length for space left
 * 
 * @return  string  right aligned text using spaces
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _align_right( $text, $maxlen = 16 )
 {
  $len = strlen( $text );
  if ( $len > $maxlen ) return $text;
  return str_repeat( " ", $maxlen - $len ).$text;
 }

/**
 * start new mt_srand randomizer
 *
 * @access  public
 *
 * @author  patrick.kracht
 */
 function _randomize()
 {
  list( $usec, $sec ) = explode( ' ', microtime() );
  mt_srand( (float) $sec + ((float) $usec * 100000) );
 }
 
?>
