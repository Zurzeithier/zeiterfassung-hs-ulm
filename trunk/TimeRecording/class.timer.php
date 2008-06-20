<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Timer:: class implements time measuring methods.
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
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
		
		/**
		 * constructor initializes the counter and starts it (if wanted)
		 *
		 * @param	array	parameters: 0=>(string)NAME,1=>(boolean)START
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __construct($parameters = array())
		{
			$this->namestr = (isset($parameters[0])) ? $parameters[0] : "Timer#" . self::$number;
			if (isset($parameters[1]))
				{
					// autostart timer
					$this->start();
				}
			self::$number++;
		}
		
		/**
		 * destructor kills the timer and decreases counter
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __destruct()
		{
			self::$number--;
		}
		
		/**
		 * start current timer
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function start()
		{
			$this->microtm = microtime(true);
		}
		
		/**
		 * stop timer and increase total runtime and ticks counter
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function stop()
		{
			$this->runtime += microtime(true) - $this->microtm;
			$this->microtm = 0;
			$this->counter++;
		}
		
		/**
		 * resets current counter to zero
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
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
		 * @return	double	amount of time since first start
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function get($prec = 3)
		{
			if ($this->microtm != 0)
				{
					// stop timer, if running
					$this->stop();
				}
			$double = (double)number_format($this->runtime * $this->unitfac, $prec);
			return ($double<=0)?0.001:$double;
		}
		
		/**
		 * get number of timeslices from timer
		 *
		 * @return 	int		number of timeslices
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function counter()
		{
			return $this->counter;
		}
		
		/**
		 * get number of counters registered
		 *
		 * @return	int		 number of counters
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function number()
		{
			return self::$number;
		}
		
		/**
		 * set units system
		 *
		 * @param	string		unit to use for output [us|ms|s]
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_units($name)
		{
			$name = strtolower($name);
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
		 * @return 	string		[COUNTER] trigger(s) from '[NAME]' lasted [DURATION] [UNIT];
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __toString()
		{
			if ($this->microtm != 0)
				{
					// stop timer, if running
					$this->stop();
				}
			$return  = $this->counter." Zugriff".(($this->counter!=1)?"e":"");
			$return .= " auf ".$this->namestr . " dauerte".(($this->counter!=1)?"n ":" ");
			$return .= $this->get().$this->unitstr . "<br/>";
			return $return;
		}
		
	}
?>