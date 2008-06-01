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
 * @author          Thorsten Moll  <thorsten.moll@googlemail.com>
 * 
 * @see             http://www.omega2k.de/~omega2k/WEBE/
 * 
 * @version         0.5_beta         
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
  case "terminal_functions": $SES->terminal_functions( $SQL ); break;
  case "create":
   //$SQL->query( "INSERT INTO Mitarbeiter (Namen,Vornamen,LoginNamen,LoginPasswort) VALUES ('Nachname','Vorname','PHP5','".md5("PHP5")."');" );
  break;
 }
 
 // if logged in, show specific pages
 if ( $SES->started() )
 {
  // load template index.html
  $TPL->load( "index.html" );
  
  // create menu and highlight active page
  $menu  = '<ul id="main_menu">';
  $menu .= $TPL->menu_get_entry( "Abmelden", "./?action=logout", "Q" );
  $menu .= $TPL->menu_get_entry( "&Uuml;bersicht", "./?page=home", "H", ($_SESSION["PageID_NOW"]=="home") );
  $menu .= $TPL->menu_get_entry( "Statistiken", "./?page=stats", "S", ($_SESSION["PageID_NOW"]=="stats") );
  $menu .= $TPL->menu_get_entry( "Datenbank", "./?page=dump", "D", ($_SESSION["PageID_NOW"]=="dump") );
  $menu .= '</ul>';
  $TPL->assign( "index.html", "<!--MENU-->", $menu );
  unset( $menu );
  
  // choose template from session "PageID_NOW"
  switch( $_SESSION["PageID_NOW"] )
  {
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
     // first fetch result from query
     $first  = true;
     $query  = "SELECT * FROM $table_name";
     $result = $SQL->query( $query );
     
     // append title and start table
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
      // append data rows
      $dump .= "<tr><td>";
      $dump .= implode( "</td><td>", $row );
      $dump .= "</td></tr>";     
     }
     // close table
     $dump  .= "</table>";
    }
    
    // include the generated html source in template
    $TPL->assign( $template_name, "<!--DUMP-->", $dump );
   break;
   case "home":
   default:
   	// standard index page (home)
    $template_name = "page_home.html";
     
    $array  = $SQL->query_first( "SELECT TOP 1 TypId FROM ZeitBuchung WHERE MId = '".$_SESSION ['UserID']."' ORDER BY Bid DESC;" );
    $status = ( array_key_exists('TypID', $array ) AND $array['TypId'] == 1 ) ? 'anwesend' : 'abwesend';
    $wt     = array("So","Mo","Di","Mi","Do","Fr","Sa");
    $tag    = date("w");
    
    $content = '';
    $result = $SQL->query( "SELECT TOP 15 Bezeichnung, Datum FROM ZeitBuchung b JOIN ZBTyp z ON (b.TypID = z.TypID) WHERE MId = '".$_SESSION ['UserID']."' ORDER BY Bid DESC;" );
    while( $row = $SQL->fetch_array( $result ) )
    {
     $content .= "<tr><td>".$row['Datum']."</td><td>".$row['Bezeichnung']."</td></tr>";
    }
    
    $TPL->assign( "page_home.html", "<!--CURRENTDATE-->", $wt[$tag].date(" d.m.Y H:i") );
    $TPL->assign( "page_home.html", "<!--USERNAME-->", $_SESSION['UserNAME'] );
    $TPL->assign( "page_home.html", "<!--USERSTATUS-->", $status );
    $TPL->assign( "page_home.html", "<!--TABLE_LASTBOOKINGS-->", $content );
    // end of case 'home' and default      
   break;
  }
  
  // load subpage content and insert in index.html
  $page_output = $TPL->get( $template_name );
  
  $page_output .= "<hr/><tt id=\"debug\"><u>DEBUG OUTPUT VALUES:</u><br/>".nl2br( print_r( $_SESSION, true ) );
  $page_output .= "<br/>".session_id()."</tt>";
  
  $TPL->assign( "index.html", "<!--PAGE_OUTPUT-->", $page_output );
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