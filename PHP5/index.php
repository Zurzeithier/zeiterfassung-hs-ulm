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
 $template_name = "page_auth.html"; // default subpage template
 
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
 }
 
 // load template index.html
 $TPL->load( "index.html" );
 
 // if logged in, show specific pages
 if ( $SES->started() )
 {
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
   default:
   	// login page (default)
    $template_name = "page_home.html";
    
    // TODO
    
   break;
  }
 }
 else
 {
  
 }
 
 // load subpage content and insert in index.html
 $page_output = $TPL->get( $template_name );
 
 $page_output .= nl2br( print_r( $_SESSION, true ) );
 $page_output .= "<br/>".session_id();
 
 $TPL->assign( "index.html", "{{PAGE_OUTPUT}}", $page_output );
 
/**
 * MAIN PROGRAM ENDS HERE
 * 
 * SEND PAGE TO BROWSER
 */
 
 // send page to browser using output method
 $TPL->output( "index.html" );
   
?>