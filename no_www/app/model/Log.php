<?php if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit('Error: No direct access allowed');}

class Log
{
	/**
	 * LOG
	 * Parse and return lines from an IRC Logfile
	 * Enable sorting, searching and filtering of IRC Logfiles
	 * Return Data ready for json, plain text or html
	 * @param int item   N or empty for first
	 * @param int count   N or empty for all
	 * @param string type   text, plain, json or empty for json
	 * @param string option   all, talk, min and empty for part, joins and quits only
	 * @param string sort   desc or asc and empty for asc
	 * @param string callback   callbackFunction for type=json only
	 * @param int date   yyyymmdd or empty for current date
	 * @param string search   search Query or empty for no search
	 *
	 * @author Simon Gattner
	 */
	private $item = 0;
	private $itemCount = 12;
	private $itemSort = 'asc';
	private $type = 'json';
	private $option = 'min';
	private $callback = false;
	private $date; // see __construct
	private $search = false;

	private $error  = array();
	private $errorShow = false;
	private $items = array();
	private $lineCount = 0;
	private $anon = true; // turn anon replacements on of (true or false)
	private $anonPattern = array(); // see __construct
	private $anonReplace = array(); // see __construct
	private $logRegex = array(); // see __construct
	private $dateStart = 20161116; // 20161116-20161118 for #text_ log files
	private $dateEnd = 20161118; // false if current
	const REG_TIMESTAMP = '\[[0-9]{2}\:[0-9]{2}\:[0-9]{2}]\]?';
	const REG_STATUS = '\s\*\*\*\s';
	const REG_NICKNAME = '[A-Za-z0-9\-\_\/\[\]\{\}\^\`\´\|\\\\\~\:]{2,96}';

	public function __construct() {
		// LOG REGEX Join, Quits, Part for short output
		$this->logRegex['liveaction'] = '/('.self::REG_TIMESTAMP.')'.self::REG_STATUS.'(Joins|Quits|Parts)\:\s('.self::REG_NICKNAME.')/';
		// LOG REGEX Join, Quits, Part etc.
		$this->logRegex['action'] = '/('.self::REG_TIMESTAMP.')'.self::REG_STATUS.'([A-Za-z]+)\:\s('.self::REG_NICKNAME.').*/';
		// LOG REGEX rename action
		$this->logRegex['rename'] = '/('.self::REG_TIMESTAMP.')'.self::REG_STATUS.self::REG_NICKNAME.'\s(is\snow\sknown)\sas\s('.self::REG_NICKNAME.')/';
		// LOG REGEX set mode action
		$this->logRegex['setmode'] = '/('.self::REG_TIMESTAMP.')'.self::REG_STATUS.'()('.self::REG_NICKNAME.')\ssets\smode\:\s.+/';
		// LOG REGEX kicked
		$this->logRegex['waskicked'] = '/('.self::REG_TIMESTAMP.')'.self::REG_STATUS.self::REG_NICKNAME.'\s(was\skicked)\sby\s('.self::REG_NICKNAME.')?.*/';
		// LOG REGEX talk action
		$this->logRegex['talk'] = '/('.self::REG_TIMESTAMP.')(\s)\<('.self::REG_NICKNAME.')\>.*/';

		$this->anonPattern[0] = '/('.self::REG_NICKNAME.'|\*\!\*|\*)@([^\s\)]+)/';
		$this->anonReplace[0] = '$1@replaced/ip/or/hostname';
		//$this->anonPattern[1] = '/\(('.self::REG_NICKNAME.')\@([^\)]*)\)/';
		//$this->anonPattern[2] = '/((http|https):\/\/|www\.).*$/i';
		//$this->anonPattern[3] = '/(((http|https):\/\/|www\.).*some_unwanted_str.*)$/i';
		//$this->anonReplace[1] = '($1@replaced/ip/or/hostname)';
		//$this->anonReplace[2] = 'www.example.org/replaced/url';
		//$this->anonReplace[3] = 'www.example.org/replaced/url';
		if ($this->dateEnd === false) {
			$this->date = (int)date('Ymd');
			$this->dateEnd = (int)$this->date;
		}else{
			$this->date = (int)$this->dateEnd;
		}
	}

	public function setItem($int) {
		if (preg_match('/[0-9]/',$int)) {
			$this->item = (int)$int;
		}else{
			if (empty($this->item)) $this->item = 0;
			$this->setError("item: is not a number");
		}
	}

	public function setCount($int) {
		if (preg_match('/[0-9]/',$int)) {
			$this->itemCount = (int)$int;
		}else{
			if (empty($this->itemCount)) $this->itemCount = 10;
			$this->setError("count: is not a number");
		}
	}

	public function setType($string) {
		if (preg_match('/(plain|html|json)/',$string)) {
			$this->type = (string)$string;
		}else{
			if (empty($this->type)) $this->type = 'json';
			$this->setError("type: is not json, plain or html");
		}
	}

	public function setOption($string) {
		if(preg_match('/(all|min|talk)/',$string)) {
			$this->option = (string)$string;
		}else{
			if (empty($this->option)) $this->option = 'min';
			$this->setError("option: is not all, talk or min");
		}
	}

	public function setSort($string) {
		if(preg_match('/(asc|desc)/',$string)) {
			$this->itemSort = (string)$string;
		}else{
			if (empty($this->sort)) $this->itemSort = 'asc';
			$this->setError("sort: is not asc or desc");
		}
	}

	public function setCallback($string) {
		if(preg_match('/[A-Za-z\_\-0-9]/',$string)) {
			$this->callback = (string)$string;
		}else{
			$this->callback = (string)preg_replace('/[^a-zA-Z\_\-0-9]+/','',$string);
			$this->setError("callback: is not [A-Za-z_-]");
		}
	}

	public function setDate($int) {
		if (preg_match('/[0-9]{8}/',$int) && ($int >= $this->dateStart && $int <= $this->dateEnd)) {
			$this->date = (int)$int;
		}else{
			$this->date = (int)$this->dateEnd;
			$this->setError("date: is not between start and end date");
		}
	}

	public function setSearch($string) {
		if(!empty($string)) {
			$this->search = (string)$string;
		}
	}

	public function getItems() {
		$this->getData();
		return $this->items;
	}

	public function getLineCount() {
		return $this->lineCount;
	}

	public function getLastItemId() {
		$end = end($this->items);
		return $end[4];
	}

	public function getFirstItemId() {
		$start = reset($this->items);
		return $start[4];
	}

	public function getDateEnd() {
		return $this->dateEnd;
	}

	public function getDateStart() {
		return $this->dateStart;
	}

	public function getDate() {
		return $this->date;
	}

	public function getItemCount() {
		return count($this->items);
	}

	public function getType() {
		return $this->type;
	}

	public function getSearch() {
		return $this->search;
	}

	public function getCallback() {
		return $this->callback;
	}

	public function getOption() {
		return $this->option;
	}

	public function getSort() {
		return $this->itemSort;
	}

	public function getMoreItems() {
		if ($this->lineCount == $this->getItemCount()) {
			return false;
		}else{
			return true;
		}
	}

	public function getTimestampDate() {
		return preg_replace('/([0-9]{4})([0-9]{2})([0-9]{2})/i','$1-$2-$3',$this->date);
	}

	public function getError() {
		return $this->error;
	}

	public function setErrorShow($bool) {
		if (is_bool($bool)) {
			$this->errorShow = $bool;
		}else{
			return false;
		}
	}

	private function setError($string) {
		array_push($this->error,$string);
	}

	private function getLinesFromFile() {
		$filename = LOG_FILE_PREFIX.$this->date.LOG_FILE_SURFIX;
		$handle = @fopen(LOG_PATH."$filename", "r");
		$lines = array();
		$l = 0;
		if ($handle) {
			while (($subject = fgets($handle, 4096)) !== false) {
				$l++;
				//check if data is uft-8 printable
				$testSubject = htmlentities(trim($subject), ENT_QUOTES, 'UTF-8');
				if ($testSubject) {
					if (isset($this->option) && $this->option === "min") {
						if (preg_match($this->logRegex['liveaction'],$subject,$matches) && $l >= $this->item) {
							array_push($matches,$l);
							if ($this->search) {
								if (preg_match($this->getSearchRegex(),$matches[0])) $lines[$l] = $matches;
							}else{
								$lines[$l] = $matches;
							}
						}
					} else {
						if ($this->option == 'all') {
							if ((preg_match($this->logRegex['setmode'],$subject,$matches) || preg_match($this->logRegex['waskicked'],$subject,$matches) || preg_match($this->logRegex['rename'],$subject,$matches) || preg_match($this->logRegex['action'],$subject,$matches) || preg_match($this->logRegex['talk'],$subject,$matches)) && $l >= $this->item) {
								if ($this->anon === true) $matches = preg_replace($this->anonPattern,$this->anonReplace,$matches);
								array_push($matches,$l);
								if ($matches[2] == " ") $matches[2] = "Talks";
								if ($matches[2] == "") $matches[2] = "Modes";
								if ($this->search) {
									if (preg_match($this->getSearchRegex(),$matches[0])) $lines[$l] = $matches;
								}else{
									$lines[$l] = $matches;
								}
							}
						}elseif ($this->option == 'talk') {
							if (preg_match($this->logRegex['talk'],$subject,$matches) && $l >= $this->item) {
								if ($this->anon === true) $matches = preg_replace($this->anonPattern,$this->anonReplace,$matches);
								array_push($matches,$l);
								if ($matches[2] == " ") $matches[2] = "Talks";
								if ($this->search) {
									if (preg_match($this->getSearchRegex(),$matches[0])) $lines[$l] = $matches;
								}else{
									$lines[$l] = $matches;
								}
							}
						}else{
							echo "Error: unexpected option\n";
						}
					}
				}
			}
			if (!feof($handle)) {
				echo "Error: unexpected fgets() fail\n";
			}
			fclose($handle);
		}
		return $lines;
	}

	private function getData() {
		$this->items = $this->getLinesFromFile();
		$this->lineCount = count($this->items);
		if ($this->itemCount > 0) {
			($this->item > 0 || $this->lineCount < $this->itemCount) ? $count = 0 : $count = $this->lineCount-$this->itemCount;
			$this->items = array_slice($this->items, $count, $this->itemCount);
		}
		if ($this->itemSort == 'desc') rsort($this->items,SORT_NUMERIC);
	}

	private function getSearchRegex() {
		if ($this->search) {
			$search = preg_replace('/[^A-Za-z0-9\s\_\-]/',' ',trim($this->search));
			if (substr($search,0,1) == '"' && substr($search,-1) == '"') {
				$search = substr($search,1,-1);
			} elseif (strstr($search," ")) {
				$search = '('.strtr($search,' ','|').')';
			}
			return '/'.$search.'/i';
		}else{
			return false;
		}
	}

	/**
	 * jsonpp - Pretty print JSON data
	 *
	 * In versions of PHP < 5.4.x, the json_encode() function does not yet provide a
	 * pretty-print option. In lieu of forgoing the feature, an additional call can
	 * be made to this function, passing in JSON text, and (optionally) a string to
	 * be used for indentation.
	 * source: http://ryanuber.com/07-10-2012/json-pretty-print-pre-5.4.html
	 *
	 * @param string $json  The JSON data, pre-encoded
	 * @param string $istr  The indentation string
	 *
	 * @return string
	 */
	static function jsonpp($json, $istr='  ') {
	if (strlen($json) > 100000) {
		return $json;
	}else{
	    $result = '';
	    for($p=$q=$i=0; isset($json[$p]); $p++)
	    {
	        $json[$p] == '"' && ($p>0?$json[$p-1]:'') != '\\' && $q=!$q;
	        if(strchr('}]', $json[$p]) && !$q && $i--)
	        {
	            strchr('{[', $json[$p-1]) || $result .= "\n".str_repeat($istr, $i);
	        }
	        $result .= $json[$p];
	        if(strchr(',{[', $json[$p]) && !$q)
	        {
	            $i += strchr('{[', $json[$p])===FALSE?0:1;
	            strchr('}]', $json[$p+1]) || $result .= "\n".str_repeat($istr, $i);
	        }
	    }
	    return $result;
		}
	}

	static function debug($api) {

		$reflection = new \ReflectionClass($api);
		$objStr = (object) array();

		$properties = $reflection ->getProperties();

		foreach ($properties as $property)
		{
			if ($property->isPublic()) $propType = 'public';
			elseif ($property->isPrivate()) $propType = 'private';
			elseif ($property->isProtected()) $propType = 'protected';
			else $propType = 'static';

			$property->setAccessible(true);

			$name = $property->getName();
			$val = $property->getValue($api);
			$text = "is_empty";
			$i = 0;
			if (is_array($val)) {
				foreach($val as $k => $v) {
					$n = $name.'['.$i.']';
					$objStr->$n = array( "type" => $propType, "array" => $name, "key" => $k, "value" => $v);
					$i = $i + 1;
				}
			}else{;
				$objStr->$name = array( "type" => $propType, "value" => $val);
			}
		}
		return (object) $objStr;
	}

	static function isValidJson($string) {
		json_decode($string);
		return json_last_error() == JSON_ERROR_NONE;
	}
}
?>
