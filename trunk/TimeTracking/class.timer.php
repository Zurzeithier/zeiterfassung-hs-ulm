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
		private static $number = 0;		// current counter number (static)
		private $counter = 0;			// how many time slices
		private $runtime = 0;			// total runtime in seconds
		private $microtm = 0;			// unix timestamp with microseconds
		private $unitfac = 1.0;			// unit multiplication factor
		private $unitstr = "s";			// unti string name (default seconds)
		private $namestr;				// name of the current counter
		private $umemory;				// usage of memory
		private $started;				// timestamp when timer was created
		
		/**
		 * constructor
		 *
		 * @param	array	parameters: 0=>(string)NAME,1=>(boolean)START
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($parameters = array())
		{
			$this->started = time();
			$this->namestr = (isset($parameters[0])) ? $parameters[0] : "Timer#" . self::$number;
			if (isset($parameters[1]))
				{
					self::start();
				}
			self::$number++;
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
			$this->microtm = microtime(true);
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
			$this->runtime += microtime(true) - $this->microtm;
			$this->microtm = 0;
			$this->counter++;
		}
		
		/**
		 * resets counter to zero
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function reset()
		{
			$this->microtm = 0;
			$this->counter = 0;
			$this->unitstr = "s";
			$this->unitfac = 1.0;
			$this->runtime = 0;
		}
		
		/**
		 * get
		 *
		 * @param	int		(optional) precision [default:3]
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function get($prec = 3)
		{
			if ($this->microtm != 0) self::stop();
			return (double)number_format($this->runtime * $this->unitfac, $prec);
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
			return "[created@".$this->started."]".$this->counter." trigger(s) from '".$this->namestr . "' lasted " . self::get() . $this->unitstr . ";<br/>\n";
		}
		
	}
?>