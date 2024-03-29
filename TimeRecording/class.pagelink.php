<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The PageLink:: class implements the page-link functions
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
 */
class PageLink
	{
		private $url   = "./";
		private $pp    = 10;
		private $ml    = 7;
		private $_data = array();
		private $cnt;
		private $html;
		private $name;
		private $pageid;
		private $total_pages;
		private $query_limit;
		private $query_first;
		private $query_last;
		
		/**
		 * @param 	string	id of the pagelink index
		 * @param 	int		count of all entries (from sql)
		 * @param 	int		number of entries per page wanted
		 * @param 	int		maximum number of links displayed
		 * @access  public
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __construct($namespace, $entrycount, $perpage = 10, $maxlinks = 7)
		{
			// init values
			$this->html = "";
			$this->pp   = $perpage;
			$this->ml   = $maxlinks;
			$this->cnt  = $entrycount;
			$this->name = $namespace;
			
			// prepare generation of html
			$this->prepare();
		}
		
		/**
		 * prepare some values for later usage
		 * @access  private
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function prepare()
		{
			if (! isset($_SESSION["_pageid"]) || ! is_array($_SESSION["_pageid"]))
				{
					$_SESSION["_pageid"] = array();
				}
				
			if (isset($_GET["pageid"]))
				{
					$this->pageid = intval($_GET["pageid"]);
				}
			else if (isset($_SESSION["_pageid"][$this->name]))
				{
					$this->pageid = $_SESSION["_pageid"][$this->name];
				}
			else
				{
					$this->pageid = 0;
				}
				
			$this->total_pages = ceil($this->cnt / $this->pp);
			$this->query_limit = "LIMIT ".($this->pp * $this->pageid).",".$this->pp;
			$this->query_first = ($this->pp * $this->pageid + 1);
			$this->query_last  = ($this->pp * ($this->pageid + 1));
			
			// force valid values
			if ($this->pageid + 1 > $this->total_pages)
				{
					$this->pageid = $this->total_pages - 1;
				}
			else if ($this->pageid < 0)
				{
					$this->pageid = 0;
				}
				
			// save other query values in url
			if (isset($_SERVER["QUERY_STRING"]) && ! empty($_SERVER["QUERY_STRING"]))
				{
					$this->url = "./?".preg_replace("@pageid=(\d+)|&pageid=(\d+)@i", "", $_SERVER["QUERY_STRING"]);
				}
			else
				{
					$this->url = "./?page=home";
				}
				
			$_SESSION["_pageid"][$this->name] = $this->pageid;
		}
		
		/**
		 * generates whole link bar in html
		 * @access  private
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function generate()
		{
			$this->html = "Seite";
			// less than 10 links -> one row, no splitting
			if ($this->total_pages <= $this->ml)
				{
					$this->get_links_from(1, $this->total_pages);
				}
			elseif($this->pageid > 3 && $this->pageid <= ($this->total_pages - 5))
			{
				$this->get_links_from(1, 2);
				$this->html .= "&nbsp;...";
				$this->get_links_from($this->pageid, $this->pageid + 2);
				$this->html .= "&nbsp;...";
				$this->get_links_from($this->total_pages - 1, $this->total_pages);
			}
			elseif($this->pageid <= 3)
			{
				$last = $this->pageid + 2;
				if ($last < 5)
					{
						$last = 5;
					}
				$this->get_links_from(1, $last);
				$this->html .= "&nbsp;...";
				$this->get_links_from($this->total_pages - 1, $this->total_pages);
			}
			elseif($this->pageid >= ($this->total_pages - 4))
			{
				$this->get_links_from(1, 2);
				$this->html .= "&nbsp;...";
				$this->get_links_from($this->total_pages - 4, $this->total_pages);
			}
		}
		
		/**
		 * appends pagelink or blank to html
		 * @param 	int		first page to display
		 * @param 	int		last page to display
		 * @access  private
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function get_links_from($start, $end)
		{
			for ($i = $start; $i <= $end; $i++)
				{
					if (($this->pageid + 1) == $i)
						{
							$this->html .= "&nbsp;(&nbsp;$i&nbsp;)";
						}
					else
						{
							$this->html .= "&nbsp;".$this->get_page_link($i);
						}
				}
		}
		
		/**
		 * returns the LIMIT x,y string for mysql queries
		 * @access  public
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function get_query_limit()
		{
			return $this->query_limit;
		}
		
		/**
		 * returns html of one link to page number $page
		 * @param 	int		page number
		 * @access  public
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function get_page_link($page)
		{
			return "<a title=\"Seite $page\" href=\"$this->url&amp;pageid=".($page-1)."\">$page</a>";
		}
		
		/**
		 * returns complete link bar
		 * @access  public
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __toString()
		{
			$this->generate();
			return $this->html;
		}
		
	}

?>