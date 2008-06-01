<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The mainprogram
 *
 * @copyright 2008  Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright 2008  Thorsten Moll  <thorsten.moll@googlemail.com>
 * 
 * @filesource      http://code.google.com/p/zeiterfassung-hs-ulm/source/browse/trunk/PHP5/
 *
 * @author          Patrick Kracht <patrick.kracht@googlemail.com>
 *                  Thorsten Moll  <thorsten.moll@googlemail.com>
 * 
 * @see             http://www.omega2k.de/~omega2k/WEBE/
 * 
 * @version         0.3_alpha         
 */
 
 
/**
 * INIT STARTS HERE
 */
 
 require_once "./functions.php" ;
 __init_main();
 
 
 $output = ""; // initialize output var
 
/**
 * MAIN PROGRAM STARTS HERE
 */
 
 // create needed objects
 $SES = new Session;
 $SQL = new MsSql;
 $TPL = new Template;
 
 switch( $_SESSION["Action"] )
 {
  case "login":  $SES->login( $SQL ); break;
  case "logout": $SES->logout(); break;
  case "create":
   //$SQL->query( "INSERT INTO Mitarbeiter (Namen,Vornamen,LoginNamen,LoginPasswort) VALUES ('Nachname','Vorname','PHP5','".md5("PHP5")."');" );
  break;
 }
 
 // if logged in, show specific pages
 if ( $SES->started() )
 {
  // load template index.html
  $TPL->load( "index.html" );
  
  // choose template from session "PageID_NOW"
  switch( $_SESSION["PageID_NOW"] )
  {
   case "home":
    $template_name = "page_home.html";
    
    // TODO
    
   break;
   case "stats":
    // statistics page
    $template_name = "page_stat.html";
    
    // TODO
    
   break;
   case "dump":
    // dump of complete db
    $template_name = "page_dump.html";
    $dump = "";
    
    // define multiple selects
    $tables = explode( ",", "Mitarbeiter,ZeitBuchung,ZeitKonto,ZBTyp" );
    
    foreach( $tables as $table_name )
    {
     $query  = "SELECT * FROM $table_name";
     $first  = true;
     $result = $SQL->query( $query );
     $dump  .= "<br/><b>$query</b><table border=\"1\">";
     while( $row = $SQL->fetch_array( $result ) )
     {
      // append only keys on first line
      if ( $first )
      {
       $dump .= "<tr><td>";
       $dump .= implode( "</td><td>", array_keys( $row ) );
       $dump .= "</td></tr>";
       $first = false;
      }
      $dump .= "<tr><td>";
      $dump .= implode( "</td><td>", $row );
      $dump .= "</td></tr>";     
     }
     $dump  .= "</table>";
    }
    
    $TPL->assign( $template_name, "{{DUMP}}", $dump );
   break;
   default:
   	// login page (default)
    $template_name = "page_home.html";
    
    // TODO
    
   break;
  }
  
  // load subpage content and insert in index.html
  $page_output = $TPL->get( $template_name );
  
  $page_output .= "<hr/>".nl2br( print_r( $_SESSION, true ) );
  $page_output .= "<br/>".session_id();
  
  $TPL->assign( "index.html", "{{PAGE_OUTPUT}}", $page_output );
  $TPL->output( "index.html" );
 }
 else
 {
  // load template login.html
  $TPL->load( "login.html" );
  $TPL->output( "login.html" );
 }
 
/**
 * MAIN PROGRAM ENDS HERE
 */
?>