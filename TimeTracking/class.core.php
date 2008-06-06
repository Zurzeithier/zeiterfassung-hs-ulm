<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Core:: class
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 */
class Core
	{
		protected static $tSql = "MySql";
		protected static $_Sql = array();
		protected static $_Tmr = array();
		protected static $_Err = array();
		protected static $_Tpl;
		protected static $_Acc;
		
		/**
		 * constructor
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($ObjType="MySql")
		{
			self::$tSql = $ObjType;
			self::get_Timer("total system runtime", true);
			self::$_Sql[self::$tSql] =& new $ObjType();
			self::$_Tpl =& new Template();
			self::$_Acc =& new Session();
		}
		
		/**
		 * destructor
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __destruct()
		{
		}
		
		/**
		 * get a new timer
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get_Timer($namestr, $autostart = true)
		{
			if (! array_key_exists($namestr, self::$_Tmr))
				{
					self::$_Tmr[$namestr] =& new Timer($namestr, $autostart);
				}
			return self::$_Tmr[$namestr];
		}
		
		/**
		 * get a new mysql object, if not created
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get_MySql()
		{
			if (! array_key_exists("MySql", self::$_Sql))
				{
					self::$_Sql["MySql"] =& new MySql();
				}
			return self::$_Sql["MySql"];
		}
		
		/**
		 * get a new mssql object, if not created
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get_MsSql()
		{
			if (! array_key_exists("MsSql", self::$_Sql))
				{
					self::$_Sql["MsSql"] =& new MsSql();
				}
			return self::$_Sql["MsSql"];
		}
		
		/**
		 * get session pointer
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function ObjACC()
		{
			return self::$_Acc;
		}
		
		/**
		 * get default [my/ms]sql pointer
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function ObjSQL()
		{
			return self::$_Sql[self::$tSql];
		}
		
		/**
		 * get template pointer
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function ObjTPL()
		{
			return self::$_Tpl;
		}
		
		/**
		 * set a new error message
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function set_error($errormsg="unknown error")
		{
			self::$_Err[] =& new Error($errormsg);
		}
		
		/**
		 * dump a sql table to sting (html)
		 *
		 * @param 	string	name of the table to print out
		 * @param 	string	(optional) type of sql to query from [MySql|MsSql]
		 *
		 * @return 	string	table in html source
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function html_table($tablename,$typeOfSql=false)
		{
			if ($typeOfSql) return self::$_Sql[$typeOfSql]->html_table($tablename);
			return self::$_Sql[self::$tSql]->html_table($tablename);
		}
		
		/**
		 * returns array of all tables in database
		 *
		 * @param 	string	(optional) type of sql to query from [MySql|MsSql]
		 *
		 * @return	array	array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_tables($typeOfSql=false)
		{
			if ($typeOfSql) return self::$_Sql[$typeOfSql]->html_table($tablename);
			return self::$_Sql[self::$tSql]->show_tables();
		}
		
		/**
		 * returns array of all tables in database
		 *
		 * @param 	string	(optional) type of sql to query from [MySql|MsSql]
		 *
		 * @return	array	array of all tables in database
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function show_last_bookings($limit=15)
		{
			$return = "";
			$query  = "SELECT TOP $limit t.Bezeichnung, t.Symbol, b.TypId, CONVERT(varchar, Datum , 104)+' '+CONVERT(varchar, Datum , 8) AS Datum FROM ZeitBuchung b ";
			$query .= "LEFT JOIN ZBTyp t ON ( t.TypId = b.TypId ) WHERE b.MId = '".$_SESSION["UserID"]."' ";
			$query .= "ORDER BY b.Datum DESC";
			$result = self::$_Sql[self::$tSql]->query($query);
			while ($row = self::$_Sql[self::$tSql]->fetch_array($result))
				{
					$return .= '<div class="win_attr">'.$row["Bezeichnung"].'</div><div class="win_value">'.$row["Datum"].'</div><br clear="all"/>';
				}
			return $return;
		}
	}
?>