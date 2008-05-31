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
 $template_name = "page_home.html"; // default subpage template
 
/**
 * MAIN PROGRAM STARTS HERE
 */
 
 // create needed objects
 $SID = new Session;
 $SQL = new MsSql;
 $TPL = new Template;
 
 // load template index.html
 $TPL->load( "index.html" );
 
 // include page specific program parts (subprograms)
 switch( $_SESSION["PageID_NOW"] )
 {
  // PAGE 1 : AUTHENTIFICATION
  case 1:
   $template_name = "page_auth.html";
   
   // TODO
   
   // authentification page (login, logout)
   $page_output = $TPL->get( $template_name );
  break;
  // PAGE 2 : STATISTICS
  case 2:
   // statistics page
   $template_name = "page_stat.html";
   
   // TODO
   
   // read all rows from table 'Mitarbeiter'
   $buffer = "";
   $result = $SQL->query( "SELECT * FROM Mitarbeiter" );
   while( $row = $SQL->fetch_array( $result ) )
   {
    $buffer .= nl2br( print_r( $row, true ) );
   }
   $SQL->free_result( $result );
   
   // set replacement for RESULT in page_stat.html
   $TPL->assign( $template_name, "{{RESULT}}", $buffer );
  break;
  // PAGE 0 : DEFAULT
  default:
   
   // TODO
   
  break;
 }
 
 // load subpage content and insert in index.html
 $page_output = $TPL->get( $template_name );
 $TPL->assign( "index.html", "{{PAGE_OUTPUT}}", $page_output );
 
 // DEBUG
 print_r( $_SESSION );
 echo "SessionID = ".session_id();
 
/**
 * MAIN PROGRAM ENDS HERE
 * 
 * SEND PAGE TO BROWSER
 */
 
 // send page to browser using output method
 $TPL->output( "index.html" );
   
?>