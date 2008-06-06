<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Timer:: class
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 */
class Timer
	{
		private static $number = 0;
		private $counter = 0;
		private $runtime = 0;
		private $microtm = 0;
		private $unitfac = 1;
		private $unitstr = "s";
		private $namestr;
		/**
		 * constructor
		 *
		 * @param	string	$namestr	name of new timer
		 * @param 	boolean	$autostart	autostart timer on create
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($namestr = false, $autostart = false)
		{
			$this->namestr = (is_string($namestr)) ? $namestr : "Timer#" . self::$number;
			if ($autostart) self::start();
			self::$number++;
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
			self::$number--;
		}
		
		/**
		 * start
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function start()
		{
			$this->microtm = microtime();
		}
		
		/**
		 * stop
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function stop()
		{
			$this->runtime += array_sum(explode(" ", microtime())) - array_sum(explode(" ", $this->microtm));
			$this->microtm = 0;
			$this->counter++;
		}
		
		/**
		 * get
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get()
		{
			if ($this->microtm != 0) self::stop();
			return number_format($this->runtime * $this->unitfac, 3);
		}
		
		/**
		 * counter
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function counter()
		{
			return $this->counter;
		}
		
		/**
		 * number
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function number()
		{
			return self::$number;
		}
		
		/**
		 * set units system
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function set_units($name)
		{
			switch ($name)
				{
				case "us":
					$this->unitstr = $name;
					$this->unitfac = 1000000.0;
					break;
				case "ms":
					$this->unitstr = $name;
					$this->unitfac = 1000.0;
					break;
				case "s":
				default:
					$this->unitstr = $name;
					$this->unitfac = 1.0;
					break;
				}
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
			if ($this->microtm != 0) self::stop();
			return $this->counter." in '".$this->namestr . "' " . self::get() . $this->unitstr . "; ";
		}
		
	}
?>