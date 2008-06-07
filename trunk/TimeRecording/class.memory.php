<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Memory:: class
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 */
class Memory
	{
		private $umemory;				// usage of memory
		
		/**
		 * constructor
		 *
		 * @param	array	parameters: 0=>(string)NAME,1=>(boolean)START
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct()
		{
			$this->start();
		}
		
		/**
		 * destructor kills the timer and decreases counter
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __destruct()
		{
			echo memory_get_usage(true) . " bytes in use;\n";
		}
		
		/**
		 * print delta
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function delta()
		{
			echo "delta: " .(memory_get_usage(true) - $this->umemory) . ";\n";
		}
		
		/**
		 * start measuring
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function start()
		{
			$this->umemory = memory_get_usage(true);
		}
		
		/**
		 * to string
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __toString()
		{
			return memory_get_usage(true) . " bytes in use;\n";
		}
		
	}
?>