<?php

$_SETTINGS = array(

                 /* MYQL SERVER CONFIGURATION (HOST,PORT,DATABASE,USERNAME,PASSWORD) */
                 "Main" => array(
                             "MYSQL", 						//(0) type of database to use default
                             300							//(1) session timeout
                         )
                         ,
                 /* MYQL SERVER CONFIGURATION (HOST,PORT,DATABASE,USERNAME,PASSWORD) */
                 "MySql" => array(
                              "localhost", 					//(0) remote or local hostname
                              3306, 						//(1) port number of the service
                              "TimeRecording", 				//(2) database name to connect to
                              "TimeRecording", 				//(3) username for database
                              "TimeRecording" 				//(4) password for database
                          )
                          ,
                 /* MSQL SERVER CONFIGURATION (HOST,PORT,DATABASE,USERNAME,PASSWORD) */
                 "MsSql" => array(
                              "idefix.illertech.com", 		//(0) remote or local hostname
                              1433, 						//(1) port number of the service
                              "Zeiterfassung", 				//(2) database name to connect to
                              "sa", 						//(3) username for database
                              "odysee2001" 					//(4) password for database
                          )
                          ,
                 /* TEMPLATE CONFIGURATION (HOST,PORT,DATABASE,USERNAME,PASSWORD) */
                 "Template" => array(
                                 "./templates/", 			//(0) folder containing templates
                                 true, 						//(1) should debugging turned of?
                                 false, 					//(2) should html output be compressed?
                                 "tr_templates"	 			//(3) database table for templates, if used
                             )

             );

?>