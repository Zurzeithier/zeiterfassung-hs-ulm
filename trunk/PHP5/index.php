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
 
 $output = "";
 
/**
 * MAIN PROGRAM STARTS HERE
 */
 
 // create needed objects
 $SID = new Session;
 $SQL = new MsSql;
 $TPL = new Template;
 
 // load template index.html
 $TPL->load( "index.html" );
 
 $SQL->connect();
 
 // include page specific program parts (subprograms)
 switch( $_SESSION["PageID_NOW"] )
 {
  case 1:  include( "./pages/auth.php" ); break;
  case 2:  include( "./pages/stat.php" ); break;
  default: include( "./pages/home.php" ); break;
 }
 // assing content to specific replacement vars
 $TPL->assign( "index.html", "{{PAGE_OUTPUT}}", $page_output );
 
 // DEBUG
 print_r( $_SESSION ); 
 
/**
 * MAIN PROGRAM ENDS HERE
 * 
 * SEND PAGE TO BROWSER
 */
 
 // send page to browser using output method
 $TPL->output( "index.html" );
   
?>