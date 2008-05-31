<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Email:: class gives easy methods to send html mails
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 */
 class Email extends Template
 {
  protected static $eol; //End-Of-Line
  protected static $sol; //Separator-Of-Line
  protected static $mailto     = "webmaster@localhost";
  protected static $domain     = "localhost";
  protected static $borders    = array();
  protected static $headers    = array();
  protected static $attach     = array();
  protected static $recipients = "";
  protected static $subject    = "";
  protected static $template;
  
/**
 * constructor creates default mail from template
 *
 * @param   string  template name for html-email
 * @param   string  (optional) subject of mail
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function __construct( $template, $subject = "" )
  {
   randomize();
   self::set_newlines();
   
   self::$template = $template;
   self::$subject  = utf8_decode( $subject );
   self::$headers  = array(
    "Return-Path"  => "webmaster@localhost",
    "Message-ID"   => time().rand(1,1000)."@".$_SERVER["SERVER_NAME"],
    "Date"         => date( "D, j M Y G:i:s O" ),
    "From"         => "webmaster <webmaster@localhost>",
    "User-Agent"   => "PHP MAILER",
    "MIME-Version" => "1.0",
    "Sender-IP"    => $_SERVER["REMOTE_ADDR"],
    "Reply-To"     => "webmaster <service@localhost>"
   );
   
  }
  
/**
 * returns a 32bit token
 *
 * @return  string  new unique 32bit token
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function get_token()
  {
   return strtoupper( md5( uniqid( microtime() * mt_rand( 1000,100000 ) ) ) );
  }
  
/**
 * add a recipient to the mail
 *
 * @param   string  prefix: TO, CC, BCC
 * @param   string  email address
 * @param   string  (optional) name
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function add_recipient( $prefix, $email, $name = "" )
  {
   if ( $name != "" ) self::$recipients .= "$prefix: ".utf8_decode( $name )." <$email>".self::$eol;
   else self::$recipients .= "$prefix: $email".self::$eol;
  }
  
/**
 * set sender details (FROM) and return path
 *
 * @param   string  email address
 * @param   string  (optional) name
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_sender( $email, $name = "" )
  {
   if ( $name != "" ) self::$headers["From"] = utf8_decode( $name )." <$email>";
   else self::$headers["From"] = $email;
   self::$headers["Return-Path"] = $email;
  }
  
/**
 * set sender reply-to
 *
 * @param   string  email address
 * @param   string  (optional) name
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_reply( $email, $name = "" )
  {
   if ( $name != "" ) self::$headers["Reply-To"] = utf8_decode( $name )." <$email>";
   else self::$headers["Reply-To"] = $email;
  }
  
/**
 * set recipient (primary)
 *
 * @param   string  email address
 * @param   string  (optional) name
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_to( $email, $name = "" )
  {
   if ( $name != "" ) self::$mailto = utf8_decode( $name )." <$email>";
   else self::$mailto = $email;
  }
  
/**
 * set subject of mail
 *
 * @param   string  subject of the email
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_subject( $subject )
  {
   self::$subject = utf8_decode( $subject );
  }
  
/**
 * set domain
 *
 * @param   string  domain name
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function set_domain( $domain )
  {
   self::$domain = $domain;
  }
  
/**
 * set special newlines for linux and windows
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  protected function set_newlines()
  {
   if ( strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' ) )
   {
    self::$eol="\r\n";
    self::$sol="\n";
   }
   elseif ( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' ) )
   {
    self::$eol="\r";
   }
   else
   {
    self::$eol="\n";
   }
   if ( ! isset( self::$sol ) )
   {
    self::$sol = self::$eol;
   }
  }
  
/**
 * returns a boundary for embedded objects
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  protected function get_boundary()
  {
   return "_-".self::get_token();
  }
  
/**
 * creates multipart header with starting boundary
 *
 * @param   string  boundary of the header
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  protected function create_head( $boundary )
  {
   $head  = "";
   foreach( self::$headers as $name => $value ) $head .= "$name: $value".self::$eol;
   $head .= self::$recipients;
   $head .= "Content-Transfer-Encoding: binary".self::$eol;
   $head .= "Content-Type: multipart/alternative; ".self::$eol;
   $head .= " boundary=\"$boundary\"".self::$eol;
   return $head;
  }
  
/**
 * create parsed text from $template and return html source
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  protected function create_html()
  {
   return parent::get( self::$template, true );
  }
  
/**
 * reproduce plain text from html source, strip tags etc.
 *
 * @param   string  html source input
 * 
 * @return  string  plain-text string  
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  protected function create_text( $html )
  {
   $search = array(
    '@<script[^>]*?>.*?</script>@si',
    '@<style[^>]*?>.*?</style>@siU',
    '@<[\/\!]*?[^<>]*?>@si',
    '@<![\s\S]*?--[ \t\n\r]*>@'
   );
   
   $text      = preg_replace( $search, "", $html );
   $trans_tbl = get_html_translation_table( HTML_ENTITIES );
   $trans_tbl = array_flip( $trans_tbl );
   $text      = strtr( $text, $trans_tbl );
   $text      = str_replace( self::$eol.self::$eol, self::$eol, $text );
   
   return trim( $text );
  }
  
/**
 * creates an inline object in html-mail with $id and $boundary
 *
 * @param   string  filename of the included object
 * @param   string  new id for this object
 * @param   string  boundary for separation
 * 
 * @return  string  complete mail-block including file 
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  protected function create_inline( $filename, $id, $boundary )
  {
   preg_match('@.*\/([a-z_A-Z0-9]*\.png)@i', $filename, $found );
   $name = $found[1];
   
   $ret  = self::$eol;
   $ret .= "Content-Type: image/png; ".self::$eol;
   $ret .= " name=\"$name\"".self::$eol;
   $ret .= "Content-ID: <$id>".self::$eol;
   $ret .= "Content-Disposition: inline; ".self::$eol;
   $ret .= " filename=\"$name\"".self::$eol;
   $ret .= "Content-Transfer-Encoding: base64".self::$eol;
   $ret .= self::$eol;
   $base = base64_encode( file_get_contents( $filename ) ).self::$eol;
   $base = wordwrap( $base, 72, "\n", true );
   $ret .= $base;
   $ret .= "--".$boundary;
   return $ret;
  }
  
/**
 * create html and plain-text message, bundle with objects 
 * 
 * @return  boolean status of mail function 
 * 
 * @access  public
 *
 * @author  patrick.kracht
 */
  public function send()
  {
   // create headers
   $boundmix = self::get_boundary();
   $boundalt = self::get_boundary();
   $_headers = self::create_head( $boundalt );
   
   // generate HTML and TEXT body
   $html     = self::create_html();
   $text     = self::create_text( $html );
   $inline   = "";
   
   $_headers  .= self::$eol;
   $_headers  .= "Diese Nachricht sollte als HTML dargestellt werden!".self::$eol;
   $_headers  .= "--".$boundalt.self::$eol;
   $_headers  .= "Content-Type: text/plain; charset=UTF-8; format=flowed".self::$eol;
   $_headers  .= "Content-Transfer-Encoding: 7bit".self::$eol;
   
   $output     = $text;
   $output    .= self::$eol;
   $output    .= self::$eol;
   $output    .= "--".$boundalt.self::$eol;
   $output    .= "Content-Type: multipart/related; ".self::$eol;
   $output    .= " boundary=\"$boundmix\"".self::$eol;
   $output    .= self::$eol;
   $output    .= self::$eol;
   $output    .= "--".$boundmix.self::$eol;
   $output    .= "Content-Type: text/html; charset=UTF-8".self::$eol;
   $output    .= "Content-Transfer-Encoding: 7bit".self::$eol;
   $output    .= self::$eol;
   
   // replace all images with tokens and inline-attachments (png-only)
   if ( preg_match_all( "@=\"(\.\/.*\.png)\"\s@", $html, $found ) )
   {
    foreach( $found[1] as $index => $value )
    {
     $cid = md5( $value );
     self::$attach["cid:".$cid] = $value;
     $inline .= self::create_inline( $value, $cid, $boundmix );
    }
    $html = str_replace( array_values( self::$attach ), array_keys(  self::$attach ), $html );
   }
   
   $output  .= $html;
   $output  .= self::$eol;
   $output  .= self::$eol;
   $output  .= "--".$boundmix;
   $output  .= $inline."--".self::$eol;
   $output  .= self::$eol;
   $output  .= "--".$boundalt."--".self::$eol;
   $output  .= self::$eol;
   
   $status = mail( self::$mailto, self::$subject, $output, $_headers );
   
   return $status;
  }
  
  
 }
 
?>
