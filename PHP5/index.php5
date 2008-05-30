<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The mainprogram
 *
 * Copyright 2008   Patrick Kracht <patrick.kracht@googlemail.com>
 *                  Thorsten Moll  <thorsten.moll@googlemail.com>
 *
 * @author          Patrick Kracht <patrick.kracht@googlemail.com>
 *                  Thorsten Moll  <thorsten.moll@googlemail.com>
 * 
 * @version         0.3_alpha         
 */
 
 
/**
 * INIT STARTS HERE
 */
 
 include( "./functions.php5" );
 __init_main();
 
 $output = "";
 
/**
 * MAIN PROGRAM STARTS HERE
 */
 
 // create needed objects
 $MYS = new MySql; //TEST ONLY
 $SQL = new MsSql;
 $TPL = new Template;
 
 // load template index.html
 $TPL->load( "index.html" );
 
 // do some sql-queries...
 $result = $MYS->query( "SELECT * FROM Mitarbeiter;" ); //TEST ONLY
 $result = $SQL->query( "SELECT * FROM Mitarbeiter;" );
 while( $row = $SQL->fetch_array( $result ) )
 {
  $output .= "MSSQL : " . nl2br( print_r( $row, true ) ); 
 }
 
 // assing content to specific replacement vars
 $TPL->assign( "index.html", "{{OUTPUT}}", $output );
 
/**
 * MAIN PROGRAM ENDS HERE
 * 
 * SEND PAGE TO BROWSER
 */
 
 // send page to browser using output method
 $TPL->output( "index.html" );
   
?>