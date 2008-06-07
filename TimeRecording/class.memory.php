<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Memory:: class implements memory measuring methods.
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
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct()
		{
			$this->start();
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
		 * @return  string	memory in use (in bytes)
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