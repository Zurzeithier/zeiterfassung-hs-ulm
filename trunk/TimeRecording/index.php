<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * MAIN PROGRAM - TIMERECORDING
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
 */

// warn, if php version older than 5
if (0 > version_compare(PHP_VERSION, '5'))
	{
		die('Diese Programm verwendet mindestens PHP5!');
	}

// fetch global functions
require_once "./functions.php";


// create controller and check for actions to do
$Controller =& new Controller;
$Controller->switch_actions();
$Controller->prepare_templates();
$Controller->create_menu();
$Controller->create_page();


?>