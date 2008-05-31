<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Session:: class manages login functions and security
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
 class Session
 {
  protected static $mdate;
  protected static $current_page  = 0;
  protected static $previous_page = 0;
  
/**
 * constructor initializes session, sets ini-values
 *
 * @param   string  template name for html-email
 * @param   string  (optional) subject of mail
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function __construct()
  {
   self::$mdate = intval( date("Ymd") );
   
   ini_set( "session.name", "S" );
   ini_set( "url_rewriter.tags", "" );
   ini_set( "session.cookie_path", "/" );
   ini_set( "session.cookie_domain", "" );
   
   // initialize session
   session_start();
   
   // save post or get value from "page"
   if ( isset( $_POST["page"] ) )
   {
    $_SESSION["PageID_OLD"] = isset( $_SESSION["PageID_NOW"] ) ? $_SESSION["PageID_NOW"] : 0;
    $_SESSION["PageID_NOW"] = intval( $_POST["page"] );
   }
   else if ( isset( $_GET["page"] ) )
   {
   	$_SESSION["PageID_OLD"] = isset( $_SESSION["PageID_NOW"] ) ? $_SESSION["PageID_NOW"] : 0;
   	$_SESSION["PageID_NOW"] = intval( $_GET["page"] );
   }
   else
   {
   	$_SESSION["PageID_NOW"] = 0;
   	$_SESSION["PageID_OLD"] = 0;
   }
   
   // save post or get value from "action"
   if ( isset( $_POST["page"] ) )
   {
    $_SESSION["Action"] = intval( $_POST["page"] );
   }
   else if ( isset( $_GET["page"] ) )
   {
    $_SESSION["Action"] = intval( $_GET["page"] );
   }
   else
   {
    $_SESSION["Action"] = 0;
   }
   
   if ( ! isset( $_SESSION["ValidMailsArray"] ) ) $_SESSION["ValidMailsArray"] = array();
   if ( ! isset( $_SESSION["GroupID"] ) )         $_SESSION["GroupID"]         = 1;
   if ( ! isset( $_SESSION["GroupNAME"] ) )       $_SESSION["GroupNAME"]       = "Gast";
   
   // extend session timer 
   self::extend();
  }
  
  public function __destruct()
  {
  }
  
  public function logout()
  {
   // destroy session and start new one
   session_destroy();
   session_start();
   session_regenerate_id();
   
   // reset default values
   $_SESSION["GroupID"]   = 1;
   $_SESSION["GroupNAME"] = "Gast";
   
   // Seite neuladen
   self::reload();
  }
  
  public function reset_password( $user )
  {
   // TODO !!!!
  }
  
  public function generate_password( $length = 8 )
  {
   self::randomize();
   $password = "";
   $possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXY"; 
   $i = 0; 
   while ( $i < $length )
   {
    $char = substr( $possible, mt_rand( 0, strlen( $possible ) - 1 ), 1 );
    if ( ! strstr( $password, $char ) )
    {
     $password .= $char;
     $i++;
    }
   }
   return $password;
  }
  
  public function is_admin()
  {
   if ( ! isset( $_SESSION["GroupID"] ) ) return false;
   return ( $_SESSION["GroupID"] == 2 );
  }
  
  public function is_user()
  {
   if ( ! isset( $_SESSION["GroupID"] ) ) return false;
   return ( $_SESSION["GroupID"] != 1 && $_SESSION["GroupID"] != 2 );
  }
  
  public function reload()
  {
   header( "Location: ./?page=" . intval( $_POST["page"] ) );
   exit();
  }
  
  public function login()
  {
   // TODO !!!!
  }
  
  public function extend()
  {
   if ( self::started() )
   {
   	if ( self::valid() )
   	{
     // extend session
   	 $_SESSION["SessionTimer"] = time();
   	}
   	else
   	{
   	 // kill session and logout
   	 self::logout();
   	}
   }
   else
   {
   	$_SESSION["ClientIP"] = $_SERVER["REMOTE_ADDR"];
   }
  }
  
  public function valid()
  {
   if ( ! isset ( $_SESSION["ClientIP"] ) ) return false;
   if ( ! isset ( $_SESSION["SessionTimer"] ) ) return false;
   return ( $_SERVER["REMOTE_ADDR"] == $_SESSION["ClientIP"] && time() - $_SESSION["SessionTimer"] < 900 );
  }
  
  public function started()
  {
   return ( isset( $_SESSION["SessionTimer"] ) && isset( $_SESSION["ClientIP"] ) );
  }
  
  public function return2( $url )
  {
   header( "Location: ".$url );
   exit();    
  }
  
  public function randomize()
  {
   list( $usec, $sec ) = explode( ' ', microtime() );
   mt_srand( (float) $sec + ((float) $usec * 100000) );
  }
  
  public function anti_spam( &$html, &$md5 )
  {
   $value = 0;
   $html  = intval( $html );
   $md5   = trim( $md5 );
   if ( ! empty( $html ) && ! empty( $md5 ) && $html > 0 && strlen( $md5 ) == 32 )
   {
   	if ( md5( $html * self::$mdate ) == $md5 )
   	{
   	 $html = "";
   	 $md5  = "";
   	 return true;
   	}
   	$html = "";
   	$md5  = "";
   	return false;
   }
   self::randomize();
   $oper1 = ( mt_rand( 0, 1 ) == 0 ) ? "+" : "-";
   $multi = mt_rand( 3, 7 );
   $addi3 = mt_rand( 2, 9 );
   $addi2 = mt_rand( 2, 4 );
   if ( $oper1 == "-" ) $addi1 = $addi2 + mt_rand( 2, 5 );
   else $addi1 = mt_rand( 2, 5 );
   
   $html  = "&#40;&nbsp;$addi1&nbsp;$oper1&nbsp;$addi2&nbsp;&#41;&nbsp;&middot;&nbsp;$multi&nbsp;&#61;&nbsp;";
   eval( "\$value = ( $addi1 $oper1 $addi2 ) * $multi;" );
   $md5 = md5( self::$mdate * $value );
   return 2;
  }
  
  public function valid_email( $email, $check_db = false )
  {
   $error = false;
   
   // avoid queries to mail servers, use session buffer
   if ( in_array( $email, $_SESSION["ValidMailsArray"] ) )
   {
   	return ( ! $check_db ) ? true : self::email_exists( $email );
   }
   
   $email = strtolower( $email );
   
   // regex for checking syntactically correct email addresses
   if ( ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $email ) )
   {
    list( $username, $domain ) = split( '@', $email );
    $smtp = array( "mail.$domain", "smtp.$domain" );
    
    if ( ! function_exists( "checkdnsrr" ) ) 
    {
     $error = true;
     echo( "function checkdnsrr() not found!\n" );
    }
    if ( ! function_exists( "getmxrr" ) )
    {
     $error = true;
     echo( "function getmxrr() not found!\n" );
    }
    if ( ! function_exists( "fsockopen" ) )
    {
     $error = true;
     echo( "function fsockopen() not found!\n" );
    }
    
    if ( $error ) return false;
    
    // query MX-records
    if ( @checkdnsrr ( $domain, "MX" ) )
    {
     $mx = array();
     @getmxrr( $domain, $mx );
     foreach ( $mx as $mxs ) array_push( $smtp, $mxs );
    }
    else 
    {
     // unknown error, no MX-records?
     return false;
    }
    
    // contact each server
    foreach ( $smtp as $server )
    {
     if ( $sock = @fsockopen( $server, 25, $errno, $errstr, 3 ) )
     {
      if ( ereg("^220", $output = fgets( $sock, 1024 ) ) )
      {
       fputs ( $sock, "HELO ".$_SERVER["HTTP_HOST"]."\r\n" );
       $out = fgets ( $sock, 1024 );
       fputs ( $sock, "MAIL FROM: <{$email}>\r\n" );
       $gfr = fgets ( $sock, 1024 );
       fputs ( $sock, "RCPT TO: <{$email}>\r\n" );
       $gto = fgets ( $sock, 1024 );
       fputs ( $sock, "QUIT\r\n");
       fclose ( $sock );
       
       // if sender and recipient valid, TRUE
       if ( ( ereg("^250", $gfr ) && ereg( "^250", $gto ) ) || ( ! empty( $gfr ) && ! ereg( "unknown", $gfr ) ) )
       {
        array_push( $_SESSION["ValidMailsArray"], $email );
        return ( ! $check_db ) ? true : self::email_exists( $email );
       }
      }
     }
    }
    // all mailservers checked... no success, FAILED
   }
   return false;
  }
  
  public function valid_user( $username )
  {
   // no empty username
   if ( empty( $username ) ) return false;
   
   // TODO !!!!
   return true;
  }
  
  protected function get_mx_records( $host, $type = "MX" )
  {
   if( ! empty( $host ) )
   {
   	$result = array();
    exec( "nslookup -type=$type $host", $result );
    foreach ( $result as $line )
    {
     if( eregi( "^$host", $line ) )
     {
      return $result;
     }
    }
    return false;
   }
   return false;
  }
  
  public function is_secure_password( $password, $min_len = 8 )
  {
   return ( strlen( $password ) >= $min_len
         && preg_match("@[A-Z]@", $password )
         && preg_match("@[a-z]@", $password )
         && preg_match("@[0-9]@", $password ) );
  }
  
 }
 
?>
