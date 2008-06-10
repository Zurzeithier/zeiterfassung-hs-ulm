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
			echo "Speicher-Differenz: " .(memory_get_usage(false) - $this->umemory) . " Bytes\n";
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
			$this->umemory = memory_get_usage(false);
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
			return __from_bytes( memory_get_usage(false) ) . " Speicher sind derzeit belegt\n";
		}
		
	}
?>