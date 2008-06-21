<?
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The Email:: class gives easy methods to send html mails
 *
 * @copyright 	Patrick Kracht <patrick.kracht@googlemail.com>
 * @copyright	Thorsten Moll <thorsten.moll@googlemail.com>
 *
 * @author		Patrick Kracht <patrick.kracht@googlemail.com>
 * @author		Thorsten Moll <thorsten.moll@googlemail.com>
 */

class Email extends Template
	{
		private $eol; 		//End-Of-Line
		private $sol; 		//Separator-Of-Line
		private $mailto;
		private $domain;
		private $borders    = array();
		private $headers    = array();
		private $attach     = array();
		private $recipients = "";
		private $subject    = "";
		private $useinlay   = false;
		private $template;
		
		/**
		 * constructor creates default mail from template
		 *
		 * @param   string  template name for html-email
		 * @param   string  (optional) subject of mail
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function __construct($parameters = array())
		{
			$this->set_newlines();
			$this->set_domain($_SESSION["_Domain"]);
			$this->template = isset($parameters[0]) ? $parameters[0] : false;
			$this->subject  = isset($parameters[1]) ? utf8_decode($parameters[1]) : "";
			$this->headers  = array(
			                      "Return-Path"  => $_SESSION["_Webmaster"],
			                      "Message-ID"   => time().rand(1,1000)."@".$_SERVER["SERVER_NAME"],
			                      "Date"         => date("D, j M Y G:i:s O"),
			                      "From"         => $_SERVER["SERVER_NAME"]." <".$_SESSION["_Webmaster"].">",
			                      "User-Agent"   => "PHP MAILER",
			                      "MIME-Version" => "1.0",
			                      "Sender-IP"    => $_SERVER["REMOTE_ADDR"],
			                      "Reply-To"     => $_SERVER["SERVER_NAME"]." <".$_SESSION["_Webmaster"].">"
			                  );
			                  
		}
		
		/**
		 * returns a 32bit token
		 *
		 * @return  string  new unique 32bit token
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function get_token()
		{
			return strtoupper(md5(uniqid(microtime() * mt_rand(1000,100000))));
		}
		
		/**
		 * set usage of inlay images or use links
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_inlay($bool = false)
		{
			$this->useinlay = $bool;
		}
		
		/**
		 * add a recipient to the mail
		 *
		 * @param   string  prefix: TO, CC, BCC
		 * @param   string  email address
		 * @param   string  (optional) name
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function add_recipient($prefix, $email, $name = "")
		{
			if ($name != "") $this->recipients .= "$prefix: ".utf8_decode($name)." <$email>".$this->eol;
			else $this->recipients .= "$prefix: $email".$this->eol;
		}
		
		/**
		 * set sender details (FROM) and return path
		 *
		 * @param   string  email address
		 * @param   string  (optional) name
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_sender($email, $name = "")
		{
			if ($name != "") $this->headers["From"] = utf8_decode($name)." <$email>";
			else $this->headers["From"] = $email;
			$this->headers["Return-Path"] = $email;
		}
		
		/**
		 * set sender reply-to
		 *
		 * @param   string  email address
		 * @param   string  (optional) name
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_reply($email, $name = "")
		{
			if ($name != "") $this->headers["Reply-To"] = utf8_decode($name)." <$email>";
			else $this->headers["Reply-To"] = $email;
		}
		
		/**
		 * set recipient (primary)
		 *
		 * @param   string  email address
		 * @param   string  (optional) name
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_to($email, $name = "")
		{
			if ($name != "") $this->mailto = utf8_decode($name)." <$email>";
			else $this->mailto = $email;
		}
		
		/**
		 * set subject of mail
		 *
		 * @param   string  subject of the email
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_subject($subject)
		{
			$this->subject = utf8_decode($subject);
		}
		
		/**
		 * set domain
		 *
		 * @param   string  domain name
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function set_domain($domain)
		{
			$this->domain = $domain;
		}
		
		/**
		 * set special newlines for linux and windows
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function set_newlines()
		{
			if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN'))
				{
					$this->eol="\r\n";
					$this->sol="\n";
				}
			elseif(strtoupper(substr(PHP_OS, 0, 3) == 'MAC'))
			{
				$this->eol="\r";
			}
			else
				{
					$this->eol="\n";
				}
			if (! isset($this->sol))
				{
					$this->sol = $this->eol;
				}
		}
		
		/**
		 * returns a boundary for embedded objects
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function get_boundary()
		{
			return "_-".$this->get_token();
		}
		
		/**
		 * creates multipart header with starting boundary
		 *
		 * @param   string  boundary of the header
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function create_head($boundary)
		{
			$head  = "";
			foreach($this->headers as $name => $value) $head .= "$name: $value".$this->eol;
			$head .= $this->recipients;
			$head .= "Content-Transfer-Encoding: binary".$this->eol;
			$head .= "Content-Type: multipart/alternative; ".$this->eol;
			$head .= " boundary=\"$boundary\"".$this->eol;
			return $head;
		}
		
		/**
		 * create parsed text from $template and return html source
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function create_html()
		{
			return parent::get($this->template, true);
		}
		
		/**
		 * reproduce plain text from html source, strip tags etc.
		 *
		 * @param   string  html source input
		 *
		 * @return  string  plain-text string
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function create_text($html)
		{
			$search = array(
			              '@<script[^>]*?>.*?</script>@si',
			              '@<style[^>]*?>.*?</style>@siU',
			              '@<[\/\!]*?[^<>]*?>@si',
			              '@<![\s\S]*?--[ \t\n\r]*>@'
			          );
			          
			$text      = preg_replace($search, "", $html);
			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip($trans_tbl);
			$text      = strtr($text, $trans_tbl);
			$text      = str_replace($this->eol.$this->eol, $this->eol, $text);
			
			return trim($text);
		}
		
		/**
		 * creates an inline object in html-mail with $id and $boundary
		 *
		 * @param   string  filename of the included object
		 * @param   string  new id for this object
		 * @param   string  boundary for separation
		 *
		 * @return  string  complete mail-block including file
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		private function create_inline($filename, $id, $boundary)
		{
			preg_match('@.*\/([a-z_A-Z0-9]*\.png)@i', $filename, $found);
			$name = $found[1];
			
			$ret  = $this->eol;
			$ret .= "Content-Type: image/png; ".$this->eol;
			$ret .= " name=\"$name\"".$this->eol;
			$ret .= "Content-ID: <$id>".$this->eol;
			$ret .= "Content-Disposition: inline; ".$this->eol;
			$ret .= " filename=\"$name\"".$this->eol;
			$ret .= "Content-Transfer-Encoding: base64".$this->eol;
			$ret .= $this->eol;
			$base = base64_encode(file_get_contents($filename)).$this->eol;
			$base = wordwrap($base, 72, "\n", true);
			$ret .= $base;
			$ret .= "--".$boundary;
			return $ret;
		}
		
		/**
		 * create html and plain-text message, bundle with objects
		 *
		 * @return  boolean status of mail function
		 *
		 * @access  public
		 *
		 * @author  patrick.kracht, thorsten.moll
		 */
		public function send()
		{
			// create headers
			$boundmix = $this->get_boundary();
			$boundalt = $this->get_boundary();
			$_headers = $this->create_head($boundalt);
			
			// generate HTML and TEXT body
			$html     = $this->create_html();
			$text     = $this->create_text($html);
			$inline   = "";
			
			$_headers  .= $this->eol;
			$_headers  .= "Diese Nachricht sollte als HTML dargestellt werden!".$this->eol;
			$_headers  .= "--".$boundalt.$this->eol;
			$_headers  .= "Content-Type: text/plain; charset=UTF-8; format=flowed".$this->eol;
			$_headers  .= "Content-Transfer-Encoding: 7bit".$this->eol;
			
			$output     = $text;
			$output    .= $this->eol;
			$output    .= $this->eol;
			$output    .= "--".$boundalt.$this->eol;
			$output    .= "Content-Type: multipart/related; ".$this->eol;
			$output    .= " boundary=\"$boundmix\"".$this->eol;
			$output    .= $this->eol;
			$output    .= $this->eol;
			$output    .= "--".$boundmix.$this->eol;
			$output    .= "Content-Type: text/html; charset=UTF-8".$this->eol;
			$output    .= "Content-Transfer-Encoding: 7bit".$this->eol;
			$output    .= $this->eol;
			
			// replace all images with tokens and inline-attachments (png-only)
			if (preg_match_all("@(\.\/.*\.png)@i", $html, $found))
				{
					// attach the images to the mail
					if ($this->useinlay)
						{
							foreach($found[0] as $index => $value)
							{
								$cid = md5($value);
								$this->attach["cid:".$cid] = $value;
								$inline .= $this->create_inline($value, $cid, $boundmix);
							}
							$html = str_replace(array_values($this->attach), array_keys($this->attach), $html);
						}
					// turn all image-links into direct online links
					else
						{
							$url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/";
							foreach($found[0] as $index => $value)
							{
								$link = $url.str_replace("./","",$value);
								$this->attach[$link] = $value;
							}
							$html = str_replace(array_values($this->attach), array_keys($this->attach), $html);
						}
				}
				
			$output  .= $html;
			$output  .= $this->eol;
			$output  .= $this->eol;
			$output  .= "--".$boundmix;
			$output  .= $inline."--".$this->eol;
			$output  .= $this->eol;
			$output  .= "--".$boundalt."--".$this->eol;
			$output  .= $this->eol;
			
			$status = mail($this->mailto, $this->subject, $output, $_headers);
			
			return $status;
		}
		
	}

?>