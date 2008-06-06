<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Error:: class manages occurred errors and bugs for logging and output
 *
 * Copyright 2008 Patrick Kracht <patrick.kracht@googlemail.com>
 *
 * @author  Patrick Kracht <patrick.kracht@googlemail.com>
 *
 *
 */
class Error
	{
		private $message = "";
		
		/**
		 * constructor
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __construct($errormsg="unknown error!")
		{
			$this->message = $errormsg;
		}
		
		/**
		 * destructor (nop)
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht
		 */
		public function __destruct()
		{
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
			return $this->message;
		}
	}
?>
