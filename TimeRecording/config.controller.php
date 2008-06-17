<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * CONFIG FILE - TIMERECORDING
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
 */

$_SETTINGS = array(

                 /* MYQL SERVER CONFIGURATION (DATABASE_TYPE,SESSION_TIMEOUT) */
                 "Main"     => array(
                                 "MYSQL", 						//(0) type of database to use default
                                 600,							//(1) session timeout in seconds
                                 false,							//(2) force usage of cookies?
                                 "omega2k@omega2k.de",			//(3) webmaster email
                                 "omega2k.de",					//(4) mailserver domain
                                 9								//(5) max working time in hours
                             )
                             ,
                 /* MYQL SERVER CONFIGURATION (HOST,PORT,DATABASE,USERNAME,PASSWORD) */
                 "MySql"    => array(
                                 "localhost", 					//(0) remote or local hostname
                                 3306, 							//(1) port number of the service
                                 "TimeRecording", 				//(2) database name to connect to
                                 "TimeRecording", 				//(3) username for database
                                 "TimeRecording" 				//(4) password for database
                             )
                             ,
                 /* MSQL SERVER CONFIGURATION (HOST,PORT,DATABASE,USERNAME,PASSWORD) */
                 "MsSql"    => array(
                                 "idefix.illertech.com", 		//(0) remote or local hostname
                                 1433, 							//(1) port number of the service
                                 "Zeiterfassung", 				//(2) database name to connect to
                                 "sa", 							//(3) username for database
                                 "odysee2001" 					//(4) password for database
                             )
                             ,
                 /* TEMPLATE CONFIGURATION (TPL_FOLDER,DEBUG_OFF,COMPRESS,TPL_DB_TABLE) */
                 "Template" => array(
                                 "./templates/", 				//(0) folder containing templates
                                 true, 							//(1) should debugging turned off?
                                 true, 							//(2) should html output be compressed?
                                 "tr_templates"	 				//(3) database table for templates, if used
                             )

             );

?>