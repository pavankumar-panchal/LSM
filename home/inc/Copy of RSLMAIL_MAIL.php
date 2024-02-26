<?php

function rslmail($fromname, $fromemail, $toarray, $subject, $text, $html, $ccarray = null,  $bccarray = null, $filearray = null)
{
	$smtp = 'mail.relyon.co.in';
	$username = 'lms@relyon.co.in';
	$password = 'itzzzlms';
	$smtp_port = 25;
	$usessl = false;
	$timeout = 30;
	
	$m = new RSLMAIL_MAIL;
	$m->Relay($smtp, $username, $password, $smtp_port, $usessl, $timeout) or die('Can\'t connect to Relay SMTP Mail Server !'); // or print_r($m->result);
	$m->Delivery('relay');
	$m->From($fromemail, $fromname);

	while ($item = current($toarray)) 
	{
		$m->AddTo($item, key($toarray));
		next($toarray);
	}

	if(empty($ccarray) == false)
	while ($item = current($ccarray)) 
	{
		$m->AddCc($item, key($ccarray));
		next($ccarray);
	}

	if(empty($bccarray) == false)
	while ($item = current($bccarray)) 
	{
		$m->AddBcc($item, key($bccarray));
		next($bccarray);
	}
	$m->Text($text);
	$m->Html($html);

	if(empty($filearray) == false)
	while ($filesubarray = current($filearray))
	{
		if(empty($filesubarray) == false)
		{
			$file = $filesubarray[0]; //physical location of the file
			$position = $filesubarray[1]; //inline or attachment.
			$id = $filesubarray[2]; //unique id for that email considering all the attachments.
			$m->AttachFile($file, null, null, null, 'base64', $position, $id); 
		}
		next($filearray);
	}
	$m->Send($subject); 
	$m->Quit(); 
	return true;
}

class RSLMAIL_Exception extends Exception {
   public $message = '';
   public $code = 0;
   public function __construct() {
       parent::__construct($this->message, $this->code);
   }
}

class RSLMAIL_FUNC {

	static public $_extensions = array(
		'z'    => 'application/x-compress', 
		'xls'  => 'application/x-excel', 
		'gtar' => 'application/x-gtar', 
		'gz'   => 'application/x-gzip', 
		'cgi'  => 'application/x-httpd-cgi', 
		'php'  => 'application/x-httpd-php', 
		'js'   => 'application/x-javascript', 
		'swf'  => 'application/x-shockwave-flash', 
		'tar'  => 'application/x-tar', 
		'tgz'  => 'application/x-tar', 
		'tcl'  => 'application/x-tcl', 
		'src'  => 'application/x-wais-source', 
		'zip'  => 'application/zip', 
		'kar'  => 'audio/midi', 
		'mid'  => 'audio/midi', 
		'midi' => 'audio/midi', 
		'mp2'  => 'audio/mpeg', 
		'mp3'  => 'audio/mpeg', 
		'mpga' => 'audio/mpeg', 
		'ram'  => 'audio/x-pn-realaudio', 
		'rm'   => 'audio/x-pn-realaudio', 
		'rpm'  => 'audio/x-pn-realaudio-plugin', 
		'wav'  => 'audio/x-wav', 
		'bmp'  => 'image/bmp', 
		'fif'  => 'image/fif', 
		'gif'  => 'image/gif', 
		'ief'  => 'image/ief', 
		'jpe'  => 'image/jpeg', 
		'jpeg' => 'image/jpeg', 
		'jpg'  => 'image/jpeg', 
		'png'  => 'image/png', 
		'tif'  => 'image/tiff', 
		'tiff' => 'image/tiff', 
		'css'  => 'text/css', 
		'htm'  => 'text/html', 
		'html' => 'text/html', 
		'txt'  => 'text/plain', 
		'rtx'  => 'text/richtext', 
		'vcf'  => 'text/x-vcard', 
		'xml'  => 'text/xml', 
		'xsl'  => 'text/xsl', 
		'mpe'  => 'video/mpeg', 
		'mpeg' => 'video/mpeg', 
		'mpg'  => 'video/mpeg', 
		'mov'  => 'video/quicktime', 
		'qt'   => 'video/quicktime', 
		'asf'  => 'video/x-ms-asf', 
		'asx'  => 'video/x-ms-asf', 
		'avi'  => 'video/x-msvideo', 
		'vrml' => 'x-world/x-vrml', 
		'wrl'  => 'x-world/x-vrml');

	static public function exception_handler($exception, $ret = null) {
		$arrs = $exception->getTrace();
		$code = $exception->getCode();
		if ($code == 0) $mess = 'Error';
		else if ($code == 1) $mess = 'Warning';
		else $mess = 'Notice';
		$emsg = '<b>'.$mess.'</b>: '.$exception->getMessage().
			' on '.$arrs[0]['class'].$arrs[0]['type'].$arrs[0]['function'].
			' in <b>'.$arrs[0]['file'].'</b> on line <b>'.$arrs[0]['line'].'</b><br />'."\n";
		if ($code == 0) die($emsg);
		else echo $emsg;
		return $ret;
	}

	static public function exception_rewrite($exception, $message, $code) {
		if ($exception == null) $exception = new Exception($message, $code);
		else {
			$exception->message = $message;
			$exception->code = $code;
		}
		return $exception;
	}

	static public function result($conn, &$resp, $code1, $code2 = null, $code3 = null) {
		$resp = array();
		$ret = true;
		if ($conn && is_resource($conn)) {
			do {
				if ($result = fgets($conn, 1024)) {
					$resp[] = $result;
					$rescode = substr($result, 0, 3);
					if (!($rescode == $code1 || $rescode == $code2 || $rescode == $code3)) {
						$ret = false;
						break;
					}
				} else {
					$resp[] = 'can not read';
					$ret = false;
					break;
				}
			} while ($result[3] == '-');
		} else {
			$resp[] = 'invalid resource connection';
			$ret = false;
		}
		return $ret;
	}

	static public function is_win() {
		return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
	}

	static public function close($conn) {
		return ($conn && is_resource($conn)) ? fclose($conn) : false;
	}

	static public function str_clear($str, $addrep = array()) {
		try {
			$errors = array();
			$rep = array("\r", "\n", "\t");
			if (is_array($addrep)) {
				if (count($addrep) > 0) {
					foreach ($addrep as $strrep) {
						if (is_string($strrep) && $strrep != '') $rep[] = $strrep;
						else {
							$errors[] = 'invalid array value';
							break;
						}
					}
				}
			} else $errors[] = 'invalid array type';
			if (!is_string($str)) $errors[] = 'invalid argument type';
			if (count($errors) == 0) return ($str == '') ? '' : str_replace($rep, '', $str);
			else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function is_alpha($strval, $numeric = true, $addstr = '') {
		try {
			$errors = array();
			if (!is_string($strval)) $errors[] = 'invalid value type';
			if (!is_bool($numeric)) $errors[] = 'invalid numeric type';
			if (!is_string($addstr)) $errors[] = 'invalid additional type';
			if (count($errors) == 0) {
				if ($strval != '') {
					$lists = 'abcdefghijklmnoqprstuvwxyzABCDEFGHIJKLMNOQPRSTUVWXYZ'.$addstr;
					if ($numeric) $lists .= '1234567890';
					$len1 = strlen($strval);
					$len2 = strlen($lists);
					$match = true;
					for ($i = 0; $i < $len1; $i++) {
						$found = false;
						for ($j = 0; $j < $len2; $j++) {
							if ($lists{$j} == $strval{$i}) {
								$found = true;
								break;
							}
						}
						if (!$found) {
							$match = false;
							break;
						}
					}
					return $match;
				} else return false;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function is_hostname($str, $addr = false) {
		try {
			$errors = array();
			if (!is_string($str)) $errors[] = 'invalid value type';
			if (!is_bool($addr)) $errors[] = 'invalid address type';
			if (count($errors) == 0) {
				$ret = false;
				if (trim($str) != '' && self::is_alpha($str, true, '-.')) {
					if (count($exphost1 = explode('.', $str)) > 1 && !(strstr($str, '.-') || strstr($str, '-.'))) {
						$set1 = $set2 = true;
						foreach ($exphost1 as $expstr1) {
							if ($expstr1 == '') {
								$set1 = false;
								break;
							}
						}
						foreach (($exphost2 = explode('-', $str)) as $expstr2) {
							if ($expstr2 == '') {
								$set2 = false;
								break;
							}
						}
						$ext = $exphost1[count($exphost1)-1];
						$len = strlen($ext);
						if ($set1 && $set2 && $len > 1 && $len < 7 && self::is_alpha($ext, false)) $ret = true;
					}
				}
				return ($ret && $addr && gethostbyname($str) == $str) ? false : $ret;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function is_ipv4($str) {
		try {
			if (is_string($str)) return (trim($str) != '' && ip2long($str) && count(explode('.', $str)) === 4);
			else throw new Exception('invalid argument value', 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function getmxrr_win($hostname, &$mxhosts) {
		$mxhosts = array();
		try {
			if (is_string($hostname)) {
				if (self::is_hostname($hostname)) {
					$hostname = strtolower($hostname);
					$retstr = exec('nslookup -type=mx '.$hostname, $retarr);
					if ($retstr && count($retarr) > 0) {
						foreach ($retarr as $line) {
							if (preg_match('/.*mail exchanger = (.*)/', $line, $matches)) $mxhosts[] = $matches[1];
						}
					}
				}
				return (count($mxhosts) > 0);
			} else throw new Exception('invalid argument type', 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function is_mail($addr, $vermx = false) {
		try {
			$errors = array();
			if (!is_string($addr)) $errors[] = 'invalid address type';
			if (!is_bool($vermx)) $errors[] = 'invalid MX type';
			if (count($errors) == 0) {
				$ret = (count($exp = explode('@', $addr)) === 2 && $exp[0] != '' && $exp[1] != '' && self::is_alpha($exp[0], true, '_-.') && (self::is_hostname($exp[1]) || self::is_ipv4($exp[1])));
				if ($ret && $vermx) {
					if (self::is_ipv4($exp[1])) $ret = false;
					else $ret = self::is_win() ? self::getmxrr_win($exp[1], $mxh) : getmxrr($exp[1], $mxh);
				}
				return $ret;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function mimetype($filename) {
		try {
			$ret = 'application/octet-stream';
			if (is_string($filename)) {
				$filename = self::str_clear($filename);
				$filename = trim($filename);
				if ($filename != '') {
					if (count($exp = explode('.', $filename)) >= 2) {
						$ext = strtolower($exp[count($exp)-1]);
						if (isset(self::$_extensions[$ext])) $ret = self::$_extensions[$ext];
					}
					return $ret;
				} else throw new Exception('invalid argument value', 0);
			} else throw new Exception('invalid argument type', 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

}

class RSLMAIL_MIME extends RSLMAIL_FUNC {

	const CRLF = "\r\n"; // PHP_EOL
	const HLEN = 52;
	const MLEN = 74;

	const HCHARSET = 'utf-8';
	const MCHARSET = 'us-ascii';

	const HENCODING = 'quoted-printable';
	const MENCODING = 'quoted-printable';

	static public $_hencoding = array('quoted-printable' => '', 'base64' => '');
	static public $_mencoding = array('7bit' => '', '8bit' => '', 'quoted-printable' => '', 'base64' => '');

	static public $_qpkeys = array(
			"\x00","\x01","\x02","\x03","\x04","\x05","\x06","\x07",
			"\x08","\x09","\x0A","\x0B","\x0C","\x0D","\x0E","\x0F",
			"\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17",
			"\x18","\x19","\x1A","\x1B","\x1C","\x1D","\x1E","\x1F",
			"\x7F","\x80","\x81","\x82","\x83","\x84","\x85","\x86",
			"\x87","\x88","\x89","\x8A","\x8B","\x8C","\x8D","\x8E",
			"\x8F","\x90","\x91","\x92","\x93","\x94","\x95","\x96",
			"\x97","\x98","\x99","\x9A","\x9B","\x9C","\x9D","\x9E",
			"\x9F","\xA0","\xA1","\xA2","\xA3","\xA4","\xA5","\xA6",
			"\xA7","\xA8","\xA9","\xAA","\xAB","\xAC","\xAD","\xAE",
			"\xAF","\xB0","\xB1","\xB2","\xB3","\xB4","\xB5","\xB6",
			"\xB7","\xB8","\xB9","\xBA","\xBB","\xBC","\xBD","\xBE",
			"\xBF","\xC0","\xC1","\xC2","\xC3","\xC4","\xC5","\xC6",
			"\xC7","\xC8","\xC9","\xCA","\xCB","\xCC","\xCD","\xCE",
			"\xCF","\xD0","\xD1","\xD2","\xD3","\xD4","\xD5","\xD6",
			"\xD7","\xD8","\xD9","\xDA","\xDB","\xDC","\xDD","\xDE",
			"\xDF","\xE0","\xE1","\xE2","\xE3","\xE4","\xE5","\xE6",
			"\xE7","\xE8","\xE9","\xEA","\xEB","\xEC","\xED","\xEE",
			"\xEF","\xF0","\xF1","\xF2","\xF3","\xF4","\xF5","\xF6",
			"\xF7","\xF8","\xF9","\xFA","\xFB","\xFC","\xFD","\xFE",
			"\xFF");

	static public $_qpvrep = array(
			"=00","=01","=02","=03","=04","=05","=06","=07",
			"=08","=09","=0A","=0B","=0C","=0D","=0E","=0F",
			"=10","=11","=12","=13","=14","=15","=16","=17",
			"=18","=19","=1A","=1B","=1C","=1D","=1E","=1F",
			"=7F","=80","=81","=82","=83","=84","=85","=86",
			"=87","=88","=89","=8A","=8B","=8C","=8D","=8E",
			"=8F","=90","=91","=92","=93","=94","=95","=96",
			"=97","=98","=99","=9A","=9B","=9C","=9D","=9E",
			"=9F","=A0","=A1","=A2","=A3","=A4","=A5","=A6",
			"=A7","=A8","=A9","=AA","=AB","=AC","=AD","=AE",
			"=AF","=B0","=B1","=B2","=B3","=B4","=B5","=B6",
			"=B7","=B8","=B9","=BA","=BB","=BC","=BD","=BE",
			"=BF","=C0","=C1","=C2","=C3","=C4","=C5","=C6",
			"=C7","=C8","=C9","=CA","=CB","=CC","=CD","=CE",
			"=CF","=D0","=D1","=D2","=D3","=D4","=D5","=D6",
			"=D7","=D8","=D9","=DA","=DB","=DC","=DD","=DE",
			"=DF","=E0","=E1","=E2","=E3","=E4","=E5","=E6",
			"=E7","=E8","=E9","=EA","=EB","=EC","=ED","=EE",
			"=EF","=F0","=F1","=F2","=F3","=F4","=F5","=F6",
			"=F7","=F8","=F9","=FA","=FB","=FC","=FD","=FE",
			"=FF");

	static public function unique($add = null) {
		return md5(microtime(1).$add);
	}

	static public function is_printable($str = null) {
		try {
			if (is_string($str) && $str != '') {
				$contain = implode('', self::$_qpkeys);
				return (strcspn($str, $contain) == strlen($str));
			} else throw new Exception('invalid argument type', 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function qp_encode($str = null, $len = self::MLEN, $end = self::CRLF) {
		try {
			$errors = array();
			if (!(is_string($str) && $str != '')) $errors[] = 'invalid argument type';
			if (!(is_int($len) && $len > 1)) $errors[] = 'invalid line length value';
			if (!is_string($end)) $errors[] = 'invalid line end value';
			if (count($errors) == 0) {
				$out = '';
				foreach (explode($end, $str) as $line) {
					$line = str_replace('=', '=3D', $line);
					$line = str_replace(self::$_qpkeys, self::$_qpvrep, $line);
					preg_match_all('/.{1,'.$len.'}([^=]{0,2})?/', $line, $match);
					$mcnt = count($match[0]);
					for ($i = 0; $i < $mcnt; $i++) {
						$line = (substr($match[0][$i], -1) == ' ') ? substr($match[0][$i], 0, -1).'=20' : $match[0][$i];
						if (($i+1) < $mcnt) $line .= '=';
						$out .= $line.$end;
					}
				}
				return ($out == '') ? '' : substr($out, 0, -strlen($end));
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
    }

	static public function encode_header($str = null, $charset = self::HCHARSET, $encoding = self::HENCODING) {
		try {
			$errors = array();
			if (!(is_string($str) && $str != '')) $errors[] = 'invalid argument type';
			if (!is_string($charset)) $errors[] = 'invalid charset type';
			else {
				$charset = self::str_clear($charset, array(' '));
				$charlen = strlen($charset);
				if ($charlen < 4 || $charlen > 22) $errors[] = 'invalid charset value';
			}
			if (!(is_string($encoding) && isset(self::$_hencoding[$encoding]))) $errors[] = 'invalid encoding value';
			if (count($errors) == 0) {
				$enc = false;
				if ($encoding == 'quoted-printable') {
					if (!self::is_printable($str)) {
						$enc = self::qp_encode($str, self::HLEN);
						$enc = str_replace('?', '=3F', $enc);
					}
				} else if ($encoding == 'base64') $enc = rtrim(chunk_split(base64_encode($str), self::HLEN, self::CRLF));
				if ($enc) {
					$res = array();
					$chr = ($encoding == 'base64') ? 'B' : 'Q';
					foreach (explode(self::CRLF, $enc) as $val) if ($val != '') $res[] = '=?'.$charset.'?'.$chr.'?'.$val.'?=';
					return implode(self::CRLF."\t", $res);
				} else return $str;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function decode_header($str = null) {
		try {
			if (is_string($str)) {
				$out = '';
				$set = 'ISO-8859-1';
				$enc = '7bit';
				$new = self::is_win() ? "\r\n" : "\n";
				foreach (explode($new."\t", $str) as $line) {
					$dec = false;
					if (strlen($line) > 10 && substr($line, 0, 2) == '=?' && substr($line, -2) == '?=') {
						if ($code = stristr($line, '?Q?')) {
							$exp = explode('?Q?', $line);
							$chr = substr($exp[0], 2, strlen($exp[0]));
							if (strlen($chr) > 3) {
								$set = $chr;
								$enc = 'quoted-printable';
								$sub = substr($code, 3, -2);
								$dec = self::is_printable($sub) ? quoted_printable_decode($sub) : $sub;
							}
						} else if ($code = stristr($line, '?B?')) {
							$exp = explode('?B?', $line);
							$chr = substr($exp[0], 2, strlen($exp[0]));
							if (strlen($chr) > 3) {
								$set = $chr;
								$enc = 'base64';
								$dec = base64_decode(substr($code, 3, -2));
							}
						}
					}
					$out .= $dec ? $dec : $line;
				}
				return array('charset' => $set, 'encoding' => $enc, 'value' => $out);
			} else throw new Exception('invalid argument type', 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function decode_content($str = null, $decoding = '7bit') {
		try {
			$errors = array();
			if (!is_string($str)) $errors[] = 'invalid argument type';
			if (!is_string($decoding)) $errors[] = 'invalid decoding type';
			else {
				$decoding = self::str_clear($decoding, array(' '));
				$decoding = strtolower($decoding);
				if (!isset(self::$_mencoding[$decoding])) $errors[] = 'invalid decoding value';
			}
			if (count($errors) == 0) {
				if ($decoding == 'base64') {
					$str = self::str_clear($str);
					return trim(base64_decode($str));
				} else if ($decoding == 'quoted-printable') {
					return quoted_printable_decode($str);
				} else return $str;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function message($content = null, $type = null, $name = null, $charset = null, $encoding = null, $disposition = null, $id = null, $exception = null) {
		try {
			$errors = array();
			if (!(is_string($content) && $content != '')) $errors[] = 'invalid content type';
			if ($type == null) $type = 'application/octet-stream';
			else if (is_string($type)) {
				$type = self::str_clear($type);
				$type = trim($type);
				$typelen = strlen($type);
				if ($typelen < 4 || $typelen > 64) $errors[] = 'invalid type value';
			} else $errors[] = 'invalid type';
			if ($name == null) $name = '';
			else if (is_string($name)) {
				$name = self::str_clear($name);
				$name = trim($name);
				if ($name != '') {
					$namelen = strlen($name);
					if ($namelen < 2 || $namelen > 64) $errors[] = 'invalid name value';
				}
			} else $errors[] = 'invalid name type';
			if ($charset == null) $charset = '';
			else if (is_string($charset)) {
				$charset = self::str_clear($charset, array(' '));
				if ($charset != '') {
					$charlen = strlen($charset);
					if ($charlen < 4 || $charlen > 64) $errors[] = 'invalid charset value';
				}
			} else $errors[] = 'invalid charset type';
			if ($encoding == null) $encoding = self::MENCODING;
			else if (is_string($encoding)) {
				$encoding = self::str_clear($encoding, array(' '));
				$encoding = strtolower($encoding);
				if (!isset(self::$_mencoding[$encoding])) $errors[] = 'invalid encoding value';
			} else $errors[] = 'invalid encoding type';
			if ($disposition == null) $disposition = 'inline';
			else if (is_string($disposition)) {
				$disposition = self::str_clear($disposition, array(' '));
				$disposition = strtolower($disposition);
				if (!($disposition == 'inline' || $disposition == 'attachment')) $errors[] = 'invalid disposition value';
			} else $errors[] = 'invalid disposition type';
			if ($id == null) $id = '';
			else if (is_string($id)) {
				$id = self::str_clear($id, array(' '));
				if ($id != '') {
					$idlen = strlen($id);
					if ($idlen < 2 || $idlen > 64) $errors[] = 'invalid id value';
				}
			} else $errors[] = 'invalid id type';
			if (count($errors) == 0) {
				$header = 'Content-Type: '.$type.
					(($charset != '') ? ';'.self::CRLF."\t".'charset="'.$charset.'"' : '').self::CRLF.
					'Content-Transfer-Encoding: '.$encoding.self::CRLF.
					'Content-Disposition: '.$disposition.
					(($name != '') ? ';'.self::CRLF."\t".'filename="'.$name.'"' : '').
					(($id != '') ? self::CRLF.'Content-ID: <'.$id.'>' : '');
				if ($encoding == '7bit' || $encoding == '8bit') $content = wordwrap($content, self::MLEN, self::CRLF, true);
				else if ($encoding == 'base64') $content = chunk_split(base64_encode($content), self::MLEN, self::CRLF);
				else if ($encoding == 'quoted-printable') $content = self::qp_encode($content);
				return array('name' => $name, 'disposition' => $disposition, 'header' => $header, 'content' => $content);
			} else throw self::exception_rewrite($exception, implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e, false); }
	}

	static public function compose($text = null, $html = null, $attach = null, $uniq = null) {
		try {
			$errors = array();
			if ($text == null && $html == null) $errors[] = 'message is not set';
			else {
				if ($text != null) {
					if (!(is_array($text) && isset($text['header'], $text['content']) && is_string($text['header']) && is_string($text['content']))) $errors[] = 'invalid text message format';
				}
				if ($html != null) {
					if (!(is_array($html) && isset($html['header'], $html['content']) && is_string($html['header']) && is_string($html['content']))) $errors[] = 'invalid html message format';
				}
				if ($attach != null) {
					if (is_array($attach) && count($attach) > 0) {
						foreach ($attach as $arr) {
							if (!(is_array($arr) && isset($arr['disposition'], $arr['header'], $arr['content']) && is_string($arr['header']) && is_string($arr['content']))) {
								$errors[] = 'invalid attachment format';
								break;
							}
						}
					} else $errors[] = 'invalid attachment format';
				}
			}
			if (count($errors) == 0) {
				$multipart = false;
				if ($text && $html) $multipart = true;
				if ($attach) $multipart = true;
				$addheader = array();
				$body = '';
				if ($multipart) {
					$uniq = ($uniq == null) ? 0 : intval($uniq);
					$boundary1 = '=_'.self::unique($uniq++);
					$boundary2 = '=_'.self::unique($uniq++);
					$boundary3 = '=_'.self::unique($uniq++);
					$disp['inline'] = $disp['attachment'] = false;
					if ($attach) {
						foreach ($attach as $desc) {
							if ($desc['disposition'] == 'inline') $disp['inline'] = true;
							else $disp['attachment'] = true;
						}
					}
					$addheader[] = 'MIME-Version: 1.0';
					$body = 'This is a message in MIME Format. If you see this, your mail reader does not support this format.'.self::CRLF.self::CRLF;
					if ($text && $html) {
						if ($disp['inline'] && $disp['attachment']) {
							$addheader[] = 'Content-Type: multipart/mixed;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.'Content-Type: multipart/related;'.self::CRLF."\t".'boundary="'.$boundary2.'"'.self::CRLF.self::CRLF.
								'--'.$boundary2.self::CRLF.'Content-Type: multipart/alternative;'.self::CRLF."\t".'boundary="'.$boundary3.'"'.self::CRLF.self::CRLF.
								'--'.$boundary3.self::CRLF.$text['header'].self::CRLF.self::CRLF.$text['content'].self::CRLF.
								'--'.$boundary3.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF.
								'--'.$boundary3.'--'.self::CRLF;
							foreach ($attach as $desc) if ($desc['disposition'] == 'inline') $body .= '--'.$boundary2.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary2.'--'.self::CRLF;
							foreach ($attach as $desc) if ($desc['disposition'] == 'attachment') $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary1.'--';
						} else if ($disp['inline']) {
							$addheader[] = 'Content-Type: multipart/related;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.'Content-Type: multipart/alternative;'.self::CRLF."\t".'boundary="'.$boundary2.'"'.self::CRLF.self::CRLF.
								'--'.$boundary2.self::CRLF.$text['header'].self::CRLF.self::CRLF.$text['content'].self::CRLF.
								'--'.$boundary2.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF.
								'--'.$boundary2.'--'.self::CRLF;
							foreach ($attach as $desc) $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary1.'--';
						} else if ($disp['attachment']) {
							$addheader[] = 'Content-Type: multipart/mixed;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.'Content-Type: multipart/alternative;'.self::CRLF."\t".'boundary="'.$boundary2.'"'.self::CRLF.self::CRLF.
								'--'.$boundary2.self::CRLF.$text['header'].self::CRLF.self::CRLF.$text['content'].self::CRLF.
								'--'.$boundary2.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF.
								'--'.$boundary2.'--'.self::CRLF;
							foreach ($attach as $desc) $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary1.'--';
						} else {
							$addheader[] = 'Content-Type: multipart/alternative;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.$text['header'].self::CRLF.self::CRLF.$text['content'].self::CRLF.
								'--'.$boundary1.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF.
								'--'.$boundary1.'--';
						}
					} else if ($text) {
						$addheader[] = 'Content-Type: multipart/mixed;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
						$body .= '--'.$boundary1.self::CRLF.$text['header'].self::CRLF.self::CRLF.$text['content'].self::CRLF;
						foreach ($attach as $desc) $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
						$body .= '--'.$boundary1.'--';
					} else if ($html) {
						if ($disp['inline'] && $disp['attachment']) {
							$addheader[] = 'Content-Type: multipart/mixed;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.'Content-Type: multipart/related;'.self::CRLF."\t".'boundary="'.$boundary2.'"'.self::CRLF.self::CRLF.
								'--'.$boundary2.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF;
							foreach ($attach as $desc) if ($desc['disposition'] == 'inline') $body .= '--'.$boundary2.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary2.'--'.self::CRLF;
							foreach ($attach as $desc) if ($desc['disposition'] == 'attachment') $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary1.'--';
						} else if ($disp['inline']) {
							$addheader[] = 'Content-Type: multipart/related;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF;
							foreach ($attach as $desc) $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary1.'--';
						} else if ($disp['attachment']) {
							$addheader[] = 'Content-Type: multipart/mixed;'.self::CRLF."\t".'boundary="'.$boundary1.'"';
							$body .= '--'.$boundary1.self::CRLF.$html['header'].self::CRLF.self::CRLF.$html['content'].self::CRLF;
							foreach ($attach as $desc) $body .= '--'.$boundary1.self::CRLF.$desc['header'].self::CRLF.self::CRLF.$desc['content'].self::CRLF;
							$body .= '--'.$boundary1.'--';
						}
					}
				} else {
					if ($text) {
						$addheader[] = $text['header'];
						$body = $text['content'];
					} else if ($html) {
						$addheader[] = $html['header'];
						$body = $html['content'];
					}
				}
				return array('addheader' => implode(self::CRLF, $addheader), 'body' => $body);
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function split_message($body = null, $multipart = null, $boundary = null) {
		try {
			$errors = array();
			if (!is_string($body)) $errors[] = 'invalid body type';
			if (!is_string($multipart)) $errors[] = 'invalid multipart type';
			if (!is_string($boundary)) $errors[] = 'invalid boundary type';
			if (count($errors) == 0) {
				$ret = array();
				if (strstr($body, '--'.$boundary.'--')) {
					$exp1 = explode('--'.$boundary.'--', $body);
					$new = self::is_win() ? "\r\n" : "\n";
					if (strstr($exp1[0], '--'.$boundary.$new)) {
						foreach (explode('--'.$boundary.$new, $exp1[0]) as $part) {
							if ($data1 = stristr($part, 'content-type: ')) {
								if ($data2 = stristr($part, 'boundary=')) {
									$exp2 = explode('multipart/', $part);
									$exp3 = explode(';', $exp2[1]);
									$multipart2 = trim(strtolower($exp3[0]));
									if ($multipart2 == 'mixed' || $multipart2 == 'related' || $multipart2 == 'alternative') {
										$data2 = substr($data2, strlen('boundary='));
										$exp4 = explode("\n", $data2);
										$exp5 = explode("\r", $exp4[0]);
										$boundary2 = trim($exp5[0], '"');
										if ($boundary2 != '') $ret = self::split_message($part, $multipart.', '.$multipart2, $boundary2);
									}
								} else {
									if ($res = self::split_compose($part)) {
										$one = array();
										foreach ($res['header'] as $harr) {
											foreach ($harr as $hnum => $hval) if (stristr($hnum, 'content-')) $one[$hnum] = $hval;
										}
										$one['multipart'] = $multipart;
										$one['data'] = $res['body'];
										$ret[] = $one;
									}
								}
							}
						}
					}
				}
				return (count($ret) > 0) ? $ret : false;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function split_compose($str = null) {
		try {
			if (!is_string($str)) throw new Exception('invalid argument type', 0);
			$new = self::is_win() ? "\r\n" : "\n";
			$sep = $new.$new;
			$arr['header'] = $arr['body'] = array();
			if (!(count($exp1 = explode($sep, $str)) > 1)) {
				$new = ($new == "\n") ? "\r\n" : "\n";
				$sep = $new.$new;
				if (!(count($exp1 = explode($sep, $str)) > 1)) throw new Exception('invalid 1 argument value', 1);
			}
			$multipart = false;
			$header = str_replace(';'.$new."\t", '; ', $exp1[0]);
			$header = str_replace($new."\t", '', $header);
			if (!(count($exp2 = explode($new, $header)) > 1)) throw new Exception('invalid 2 argument value', 1);
			foreach ($exp2 as $hval) {
				$exp3 = explode(': ', $hval, 2);
				$name = trim($exp3[0]);
				if (count($exp3) == 2 && $name != '' && !strstr($name, ' ')) {
					$sval = trim(self::str_clear($exp3[1]));
					$arr['header'][] = array($name => $sval);
					if (strtolower($name) == 'content-type') {
						if (($data1 = stristr($sval, 'multipart/')) && ($data2 = stristr($sval, 'boundary='))) {
							$data3 = trim(substr($data2, strlen('boundary=')));
							$bexpl = explode(';', $data3);
							$boundary = trim($bexpl[0], '"');
							if ($boundary != '') {
								$data4 = substr($data1, strlen('multipart/'));
								$mexpl = explode(';', $data4);
								$mtype = trim(strtolower($mexpl[0]));
								if ($mtype == 'mixed' || $mtype == 'related' || $mtype == 'alternative') $multipart = $mtype;
							}
						}
					}
				}
			}
			if (count($arr['header']) > 0) {
				if ($multipart) {
					$arr['multipart'] = $multipart;
					$arr['boundary']  = $boundary;
				}
				$body = strstr($str, $sep);
				$body = substr($body, strlen($sep));
				$arr['body'] = $body;
				return $arr;
			} else throw new Exception('invalid 3 argument value', 1);
		} catch (Exception $e) { return self::exception_handler($e, false); }
	}

	static public function split_content($str = null) {
		try {
			if (!is_string($str)) throw new Exception('invalid argument type', 0);
			if (!$res = self::split_compose($str)) throw new Exception('invalid 1 argument value', 1);
			$arr = array();
			if (isset($res['multipart'], $res['boundary'])) {
				$arr['header'] = $res['header'];
				$arr['multipart'] = 'yes';
				if (!$arr['body'] = self::split_message($res['body'], $res['multipart'], $res['boundary'])) throw new Exception('invalid 2 argument value', 1);
			} else {
				foreach ($res['header'] as $harr) {
					foreach ($harr as $hnum => $hval) if (stristr($hnum, 'content-')) $content[$hnum] = $hval;
				}
				$content['data'] = $res['body'];
				$arr['header'] = $res['header'];
				$arr['multipart'] = 'no';
				$arr['body'][] = $content;
			}
			return $arr;
		} catch (Exception $e) { return self::exception_handler($e, false); }
	}

}

class RSLMAIL_SMTP extends RSLMAIL_MIME {

	const PORT = 25;
	const TIMEOUT = 30;
	const COM_TIMEOUT = 5;

	static protected $_cssl = array('tls' => '', 'ssl' => '', 'sslv2' => '', 'sslv3' => '');

	static public function quit($fp) {
		$ret = $res = false;
		if ($fp && is_resource($fp)) {
			if (fwrite($fp, 'QUIT'.self::CRLF)) {
				if ($vget = @fgets($fp, 1024)) $res['success'] = $vget;
				$ret = true;
			} else $res[27] = 'can not write';
			@fclose($fp);
		} else $res[26] = 'invalid resource connection';
		return array($ret, $res);
	}

	static public function connect($host = null, $user = null, $pass = null, $port = null, $ssl = null, $timeout = null, $name = null, $exception = null) {
		try {
			$errors = array();
			if ($host == null) $host = '127.0.0.1';
			else if (!is_string($host)) $errors[] = 'invalid hostname type';
			if ($user == null) $user = '';
			else if (!is_string($user)) $errors[] = 'invalid username type';
			if ($pass == null) $pass = '';
			else if (!is_string($pass)) $errors[] = 'invalid password type';
			if ($port == null) $port = self::PORT;
			else if (!(is_int($port) && $port > 0)) $errors[] = 'invalid port type/value';
			if ($ssl == null) $ssl = false;
			else if (is_string($ssl) && isset(self::$_cssl[strtolower($ssl)])) $ssl = strtolower($ssl);
			else if (!is_bool($ssl)) $errors[] = 'invalid ssl value';
			if ($timeout == null) $timeout = self::TIMEOUT;
			else if (!(is_int($timeout) && $timeout > 0)) $errors[] = 'invalid timeout type/value';
			if ($name == null) $name = '';
			else if (!is_string($name)) $errors[] = 'invalid name type';
			if (count($errors) == 0) {
				$host = self::str_clear($host, array(' '));
				$host = strtolower($host);
				if ($host == '') $errors[] = 'invalid hostname value';
				else if (self::is_ipv4($host)) $iphost = $host;
				else {
					$iphost = gethostbyname($host);
					if ($iphost == $host) $errors[] = 'invalid hostname address';
				}
				$user = self::str_clear($user);
				$pass = self::str_clear($pass);
				if (($user != '' && $pass == '') || ($user == '' && $pass != '')) $errors[] = 'invalid username and password value combination';
				if (count($errors) == 0) {
					$arr['result'] = $response = array();
					$arr['connection'] = false;
					if (is_bool($ssl)) $ver = $ssl ? 'ssl' : 'tcp';
					else $ver = $ssl;
					$name = self::str_clear($name, array(' '));
					if ($name == '') $name = '127.0.0.1';
					if (!$fp = stream_socket_client($ver.'://'.$iphost.':'.$port, $errno, $errstr, $timeout)) $arr['result'][0] = $errstr;
					else if (!stream_set_timeout($fp, self::COM_TIMEOUT)) $arr['result'][1] = 'could not set stream timeout';
					else if (!self::result($fp, $response, 220)) $arr['result'][2] = $response;
					else if (!fwrite($fp, 'EHLO '.$name.self::CRLF)) $arr['result'][3] = 'can not write';
					else {
						$continue = false;
						if (!self::result($fp, $response, 250)) {
							if (!fwrite($fp, 'HELO '.$name.self::CRLF)) $arr['result'][4] = 'can not write';
							else if (!self::result($fp, $response, 250)) $arr['result'][5] = $response;
							else $continue = true;
						} else $continue = true;
						if ($continue) {
							if ($user != '') {
								if (!fwrite($fp, 'AUTH LOGIN'.self::CRLF)) $arr['result'][6] = 'can not write';
								else if (!self::result($fp, $response, 334)) {
									if (!fwrite($fp, 'AUTH PLAIN '.base64_encode($user.chr(0).$user.chr(0).$pass).self::CRLF)) $arr['result'][7] = 'can not write';
									else if (!self::result($fp, $response, 235)) $arr['result'][8] = $response;
									else $arr['connection'] = $fp;
								} else if (!fwrite($fp, base64_encode($user).self::CRLF)) $arr['result'][9] = 'can not write';
								else if (!self::result($fp, $response, 334)) $arr['result'][10] = $response;
								else if (!fwrite($fp, base64_encode($pass).self::CRLF)) $arr['result'][11] = 'can not write';
								else if (!self::result($fp, $response, 235)) $arr['result'][12] = $response;
								else $arr['connection'] = $fp;
							} else $arr['connection'] = $fp;
						}
					}
					if (!$arr['connection']) self::close($fp);
					else $arr['result']['success'] = $response;
					return $arr;
				} else throw self::exception_rewrite($exception, implode(', ', $errors), 0);
			} else throw self::exception_rewrite($exception, implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

	static public function sendtohost($host, $addrs, $from, $message, $name = '', $path = '', $port = self::PORT, $timeout = self::TIMEOUT, $exception = null) {
		try {
			$errors = array();
			if (!(is_string($host) || is_resource($host))) $errors[] = 'invalid ip/connection type';
			if (!is_array($addrs)) $errors[] = 'invalid mail address destination type';
			else {
				if (count($addrs) > 0) {
					foreach ($addrs as $dest) {
						if (!(is_string($dest) && self::is_mail($dest))) {
							$errors[] = 'invalid mail address destination type/value';
							break;
						}
					}
				} else $errors[] = 'invalid mail address destination type';
			}
			if (!is_string($from)) $errors[] = 'invalid mail from address type';
			if (!is_string($path)) $errors[] = 'invalid path type';
			if (!is_string($message)) $errors[] = 'invalid message type';
			if (!is_string($name)) $errors[] = 'invalid name type';
			if (!(is_int($port) && $port > 0)) $errors[] = 'invalid port type';
			if (!(is_int($timeout) && $timeout > 0)) $errors[] = 'invalid timeout type';
			if (count($errors) == 0) {
				$res = array();
				$ret = false;
				$fp = false;
				$quit = true;
				if (!is_resource($host)) {
					$host = self::str_clear($host, array(' '));
					$host = strtolower($host);
					$iparr = array();
					if (self::is_ipv4($host)) $iparr[$host] = $host;
					else {
						$havemx = self::is_win() ? self::getmxrr_win($host, $mx) : getmxrr($host, $mx);
						if ($havemx) {
							foreach ($mx as $mxhost) {
								$iphost = gethostbyname($mxhost);
								if ($iphost != $mxhost && !isset($iparr[$iphost]) && self::is_ipv4($iphost)) $iparr[$iphost] = $iphost;
							}
						} else {
							$iphost = gethostbyname($host);
							if ($iphost != $host && self::is_ipv4($iphost)) $iparr[$iphost] = $iphost;
						}
					}
					if (count($iparr) > 0) {
						foreach ($iparr as $ipaddr) {
							$conn = self::connect($ipaddr, '', '', $port, false, $timeout, $name, $exception);
							if ($fp = $conn['connection']) break;
							else $res = $conn['result'];
						}
					} else $res['error'] = 'can not find any valid ip address';
				} else {
					$quit = false;
					$fp = $host;
				}
				if ($fp) {
					if (!fwrite($fp, 'MAIL FROM:<'.(($path == '') ? $from : $path).'>'.self::CRLF)) $res[13] = 'can not write';
					else if (!self::result($fp, $response, 250)) $res[14] = $response;
					else {
						$continue = true;
						foreach ($addrs as $dest) {
							if (!fwrite($fp, 'RCPT TO:<'.$dest.'>'.self::CRLF)) {
								$res[15] = 'can not write';
								$continue = false;
								break;
							} else if (!self::result($fp, $response, 250, 251)) {
								$res[16] = $response;
								$continue = false;
								break;
							}
						}
						if ($continue) {
							if (!fwrite($fp, 'DATA'.self::CRLF)) $res[17] = 'can not write';
							else if (!self::result($fp, $response, 354)) $res[18] = $response;
							else {
								foreach (explode(self::CRLF, $message) as $line) {
									if ($line == '.') $line = '..';
									if (!fwrite($fp, $line.self::CRLF)) {
										$res[19] = 'can not write';
										$continue = false;
										break;
									}
								}
								if ($continue) {
									if (!fwrite($fp, '.'.self::CRLF)) $res[20] = 'can not write';
									else if (!self::result($fp, $response, 250)) $res[21] = $response;
									else {
										if (!fwrite($fp, 'RSET'.self::CRLF)) $res[22] = 'can not write';
										else if (!self::result($fp, $response, 250)) $res[23] = $response;
										else if ($quit) {
											if (!fwrite($fp, 'QUIT'.self::CRLF)) $res[24] = 'can not write';
											else if (!$vget = @fgets($fp, 1024)) $res[25] = $response;
											else $res['success'] = $vget;
										} else $res['success'] = $response;
										$ret = true;
									}
								}
							}
						}
					}
				}
				return array($ret, $res);
			} else throw self::exception_rewrite($exception, implode(', ', $errors), 0);
		} catch (Exception $e) { return self::exception_handler($e); }
	}

}

class RSLMAIL_MAIL {

	protected $_conn = false;
	protected $_from = false;
	protected $_host = false;
	protected $_path = false;
	protected $_text = false;
	protected $_html = false;
	protected $_priority = false;

	protected $_port;
	protected $_timeout;

	protected $_to = array();
	protected $_cc = array();
	protected $_bcc = array();
	protected $_header = array();
	protected $_attach = array();

	protected $_vord = array('local');
	protected $_delv = array('local' => '', 'client' => '', 'relay' => '');
	protected $_unique = 0;

	public $result = array();

	public function __construct() {
		$this->_port = RSLMAIL_SMTP::PORT;
		$this->_timeout = RSLMAIL_SMTP::TIMEOUT;
	}

	public function delivery($str = null) {
		try {
			if (is_string($str)) {
				$str = strtolower($str);
				$arr = array();
				$set = true;
				foreach (explode('-', $str) as $val) {
					if (isset($this->_delv[$val])) $arr[] = $val;
					else {
						$set = false;
						break;
					}
				}
				if ($set) {
					$this->_vord = $arr;
					return true;
				} else throw new Exception('invalid argument value', 0);
			} else throw new Exception('invalid argument type', 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e); }
	}

	private function _addaddr($addr, $name, $charset, $encoding, &$rep, $exception) {
		$ret = false;
		try {
			$errors = array();
			if (!is_string($addr)) $errors[] = 'invalid address type';
			if ($name == null) $name = '';
			else if (!is_string($name)) $errors[] = 'invalid name type';
			if ($charset == null) $charset = RSLMAIL_MIME::HCHARSET;
			else if (!is_string($charset)) $errors[] = 'invalid charset type';
			if ($encoding == null) $encoding = RSLMAIL_MIME::HENCODING;
			else if (!is_string($encoding)) $errors[] = 'invalid encoding type';
			if (count($errors) == 0) {
				if (isset($rep[$addr])) throw RSLMAIL_FUNC::exception_rewrite($exception, 'address already exists', 1);
				else if (!RSLMAIL_FUNC::is_mail($addr)) throw RSLMAIL_FUNC::exception_rewrite($exception, 'invalid address value', 1);
				else {
					$charset = RSLMAIL_FUNC::str_clear($charset, array(' '));
					$charlen = strlen($charset);
					if ($charlen < 4 || $charlen > 64) {
						$errors[] = 'invalid charset value';
						$charset = RSLMAIL_MIME::HCHARSET;
					}
					$encoding = RSLMAIL_FUNC::str_clear($encoding, array(' '));
					$encoding = strtolower($encoding);
					if ($encoding == '' || !isset(RSLMAIL_MIME::$_hencoding[$encoding])) {
						$errors[] = 'invalid encoding value';
						$encoding = RSLMAIL_MIME::HENCODING;
					}
					$name = RSLMAIL_FUNC::str_clear($name);
					$name = trim($name);
					if ($name == '') $rep[$addr] = false;
					else {
						$code = RSLMAIL_MIME::encode_header($name, $charset, $encoding);
						$rep[$addr] = ($code != $name) ? $code : '"'.str_replace('"', '\\"', $name).'"';
					}
					if (count($errors) > 0) {
						$ret = true;
						throw RSLMAIL_FUNC::exception_rewrite($exception, implode(', ', $errors), 1);
					}
					return true;
				}
			} else throw RSLMAIL_FUNC::exception_rewrite($exception, implode(', ', $errors), 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, $ret); }
	}

	public function addto($addr = null, $name = null, $charset = null, $encoding = null) {
		return $this->_addaddr($addr, $name, $charset, $encoding, $this->_to, new RSLMAIL_Exception());
	}

	public function addcc($addr = null, $name = null, $charset = null, $encoding = null) {
		return $this->_addaddr($addr, $name, $charset, $encoding, $this->_cc, new RSLMAIL_Exception());
	}

	public function addbcc($addr = null) {
		try {
			if (!is_string($addr)) throw new Exception('invalid address type', 0);
			else if (!RSLMAIL_FUNC::is_mail($addr)) throw new Exception('invalid address value', 0);
			else if (isset($this->_bcc[$addr])) throw new Exception('address already exists', 1);
			else {
				$this->_bcc[$addr] = false;
				return true;
			}
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	private function _deladdr($addr, &$rep, $exception) {
		try {
			if ($addr == null) {
				$rep = array();
				return true;
			} else if (is_string($addr) && RSLMAIL_FUNC::is_mail($addr)) {
				$found = false;
				$new = array();
				foreach ($rep as $key => $val) {
					if ($key == $addr) $found = true;
					else $new[$key] = $val;
				}
				if ($found) {
					$rep = $new;
					return true;
				} else return false;
			} else throw RSLMAIL_FUNC::exception_rewrite($exception, 'invalid address value', 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e); }
	}

	public function delto($addr = null) {
		return $this->_deladdr($addr, $this->_to, new RSLMAIL_Exception());
	}

	public function delcc($addr = null) {
		return $this->_deladdr($addr, $this->_cc, new RSLMAIL_Exception());
	}

	public function delbcc($addr = null) {
		return $this->_deladdr($addr, $this->_bcc, new RSLMAIL_Exception());
	}

	public function from($addr = null, $name = null, $charset = null, $encoding = null) {
		$ret = $this->_from = false;
		try {
			$errors = array();
			if (!is_string($addr)) $errors[] = 'invalid address type';
			else if (!RSLMAIL_FUNC::is_mail($addr)) $errors[] = 'invalid address value';
			if ($name == null) $name = '';
			else if (!is_string($name)) $errors[] = 'invalid name type';
			if ($charset == null) $charset = RSLMAIL_MIME::HCHARSET;
			else if (!is_string($charset)) $errors[] = 'invalid charset type';
			if ($encoding == null) $encoding = RSLMAIL_MIME::HENCODING;
			else if (!is_string($encoding)) $errors[] = 'invalid encoding type';
			if (count($errors) == 0) {
				$charset = RSLMAIL_FUNC::str_clear($charset, array(' '));
				$charlen = strlen($charset);
				if ($charlen < 4 || $charlen > 64) {
					$errors[] = 'invalid charset value';
					$charset = RSLMAIL_MIME::HCHARSET;
				}
				$encoding = RSLMAIL_FUNC::str_clear($encoding, array(' '));
				$encoding = strtolower($encoding);
				if ($encoding == '' || !isset(RSLMAIL_MIME::$_hencoding[$encoding])) {
					$errors[] = 'invalid encoding value';
					$encoding = RSLMAIL_MIME::HENCODING;
				}
				$name = RSLMAIL_FUNC::str_clear($name);
				$name = trim($name);
				if ($name == '') $this->_from = array('address' => $addr, 'name' => false);
				else {
					$code = RSLMAIL_MIME::encode_header($name, $charset, $encoding);
					$repl = ($code != $name) ? $code : '"'.str_replace('"', '\\"', $name).'"';
					$this->_from = array('address' => $addr, 'name' => $repl);
				}
				if (count($errors) > 0) {
					$ret = true;
					throw new Exception(implode(', ', $errors), 1);
				}
				return true;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, $ret); }
	}

	public function addheader($name = null, $value = null, $charset = null, $encoding = null) {
		$ret = false;
		try {
			$errors = array();
			if (!is_string($name)) $errors[] = 'invalid name type';
			if (!is_string($value)) $errors[] = 'invalid value type';
			if ($charset == null) $charset = RSLMAIL_MIME::HCHARSET;
			else if (!is_string($charset)) $errors[] = 'invalid charset type';
			if ($encoding == null) $encoding = RSLMAIL_MIME::HENCODING;
			else if (!is_string($encoding)) $errors[] = 'invalid encoding type';
			if (count($errors) == 0) {
				$name = RSLMAIL_FUNC::str_clear($name);
				$name = trim($name);
				$value = RSLMAIL_FUNC::str_clear($value);
				$value = trim($value);
				if ($name == '') $errors[] = 'invalid name value';
				if ($value == '') $errors[] = 'value are empty';
				if (count($errors) == 0) {
					$ver = strtolower($name);
					$err = false;
					if ($ver == 'to') $err = 'can not set "To", for this, use function "AddTo()"';
					else if ($ver == 'cc') $err = 'can not set "Cc", for this, use function "AddCc()"';
					else if ($ver == 'bcc') $err = 'can not set "Bcc", for this, use function "AddBcc()"';
					else if ($ver == 'from') $err = 'can not set "From", for this, use function "From()"';
					else if ($ver == 'subject') $err = 'can not set "Subject", for this, use function "Send()"';
					else if ($ver == 'x-priority') $err = 'can not set "X-Priority", for this, use function "Priority()"';
					else if ($ver == 'x-msmail-priority') $err = 'can not set "X-MSMail-Priority", for this, use function "Priority()"';
					else if ($ver == 'date') $err = 'can not set "Date", this value is automaticaly set';
					else if ($ver == 'content-type') $err = 'can not set "Content-Type", this value is automaticaly set';
					else if ($ver == 'content-transfer-encoding') $err = 'can not set "Content-Transfer-Encoding", this value is automaticaly set';
					else if ($ver == 'content-disposition') $err = 'can not set "Content-Disposition", this value is automaticaly set';
					else if ($ver == 'mime-version') $err = 'can not set "Mime-Version", this value is automaticaly set';
					else if ($ver == 'x-mailer') $err = 'can not set "X-Mailer", this value is automaticaly set';
					if ($err) throw new Exception($err, 0);
					else {
						$charset = RSLMAIL_FUNC::str_clear($charset, array(' '));
						$charlen = strlen($charset);
						if ($charlen < 4 || $charlen > 64) {
							$errors[] = 'invalid charset value';
							$charset = RSLMAIL_MIME::HCHARSET;
						}
						$encoding = RSLMAIL_FUNC::str_clear($encoding, array(' '));
						$encoding = strtolower($encoding);
						if ($encoding == '' || !isset(RSLMAIL_MIME::$_hencoding[$encoding])) {
							$errors[] = 'invalid encoding value';
							$encoding = RSLMAIL_MIME::HENCODING;
						}
						$this->_header[] = array('name' => ucfirst($name), 'value' => RSLMAIL_MIME::encode_header($value, $charset, $encoding));
						if (count($errors) > 0) {
							$ret = true;
							throw new Exception(implode(', ', $errors), 1);
						}
						return true;
					}
				} else throw new Exception(implode(', ', $errors), 0);
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, $ret); }
	}

	public function delheader($name = null) {
		try {
			if ($name == null) {
				$this->_header = array();
				return true;
			} else if(is_string($name)) {
				$name = RSLMAIL_FUNC::str_clear($name);
				$name = trim(strtolower($name));
				if ($name != '') {
					$found = false;
					$new = array();
					foreach ($this->_header as $arr) {
						if (strtolower($arr['name']) == $name) $found = true;
						else $new[] = $arr;
					}
					if ($found) {
						$this->_header = $new;
						return true;
					}
				} else return false;
			} else throw new Exception('invalid name value', 1);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function host($name = null, &$mx) {
		try {
			if ($name == null) {
				$this->_host = false;
				return true;
			} else if (is_string($name)) {
				$name = RSLMAIL_FUNC::str_clear($name);
				$name = trim(strtolower($name));
				if ($name != '' && ($name == 'localhost' || RSLMAIL_FUNC::is_hostname($name))) {
					$mx = false;
					$this->_host = $name;
					if ($name != 'localhost') $mx = RSLMAIL_FUNC::is_win() ? RSLMAIL_FUNC::getmxrr_win($name, $mxarr) : getmxrr($name, $mxarr);
					return true;
				} else throw new Exception('invalid hostname value', 0);
			} else throw new Exception('invalid hostname type', 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function returnpath($addr = null) {
		try {
			if ($addr == null) {
				$this->_path = false;
				return true;
			} else if (is_string($addr)) {
				if (trim($addr) != '' && RSLMAIL_FUNC::is_mail($addr)) {
					$this->_path = $addr;
					return true;
				} else throw new Exception('invalid address value', 0);
			} else throw new Exception('invalid address type', 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function port($value = null) {
		try {
			if (is_int($value) && $value > 0) {
				$this->_port = $value;
				return true;
			} else {
				$this->_port = RSLMAIL_SMTP::PORT;
				throw new Exception('invalid value type', 0);
			}
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function timeout($value = null) {
		try {
			if (is_int($value) && $value > 0) {
				$this->_timeout = $value;
				return true;
			} else {
				$this->_timeout = RSLMAIL_SMTP::TIMEOUT;
				throw new Exception('invalid value type', 0);
			}
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function priority($level = null) {
		try {
			if ($level == null) {
				$this->_priority = false;
			} else if (is_int($level) && ($level == 1 || $level == 3 || $level == 5)) {
				if ($level == 1) $this->_priority = array('1', 'High');
				else if ($level == 3) $this->_priority = array('3', 'Normal');
				else if ($level == 5) $this->_priority = array('5', 'Low');
			} else if (is_string($level)) {
				$level = RSLMAIL_FUNC::str_clear($level, array(' '));
				$level = strtolower($level);
				if ($level == 'high') $this->_priority = array('1', 'High');
				else if ($level == 'normal') $this->_priority = array('3', 'Normal');
				else if ($level == 'low') $this->_priority = array('5', 'Low');
				else throw new Exception('invalid level value', 0);
			} else throw new Exception('invalid level type', 0);
			return true;
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function text($value = null, $charset = null, $encoding = null, $disposition = null) {
		if ($charset == null) $charset = RSLMAIL_MIME::MCHARSET;
		if ($encoding == null) $encoding = RSLMAIL_MIME::MENCODING;
		if ($disposition == null) $disposition = 'inline';
		if ($this->_text = RSLMAIL_MIME::message($value, 'text/plain', null, $charset, $encoding, $disposition, null, new RSLMAIL_Exception())) return true;
		else return false;
	}

	public function html($value = null, $charset = null, $encoding = null, $disposition = null) {
		if ($charset == null) $charset = RSLMAIL_MIME::MCHARSET;
		if ($encoding == null) $encoding = RSLMAIL_MIME::MENCODING;
		if ($disposition == null) $disposition = 'inline';
		if ($this->_html = RSLMAIL_MIME::message($value, 'text/html', null, $charset, $encoding, $disposition, null, new RSLMAIL_Exception())) return true;
		else return false;
	}

	public function attachsource($value = null, $type = null, $name = null, $charset = null, $encoding = null, $disposition = null, $id = null) {
		if ($encoding == null) $encoding = 'base64';
		if ($disposition == null) $disposition = 'attachment';
		if ($arr = RSLMAIL_MIME::message($value, $type, $name, $charset, $encoding, $disposition, $id, new RSLMAIL_Exception())) {
			$this->_attach[] = $arr;
			return true;
		} else return false;
	}

	public function attachfile($file = null, $type = null, $name = null, $charset = null, $encoding = null, $disposition = null, $id = null) {
		try {
			$error = '';
			if (!is_string($file)) $error = 'invalid filename type';
			else {
				$file = RSLMAIL_FUNC::str_clear($file);
				$file = trim($file);
				if ($file != '' && is_file($file) && is_readable($file)) {
					if ($type == null) $type = RSLMAIL_FUNC::mimetype($file);
					if ($name == null) {
						$exp1 = explode('/', $file);
						$name = $exp1[count($exp1)-1];
						$exp2 = explode('\\', $name);
						$name = $exp2[count($exp2)-1];
					}
					if ($encoding == null) $encoding = 'base64';
					if ($disposition == null) $disposition = 'attachment';
				} else $error = 'invalid file resource';
			}
			if ($error == '') {
				if ($arr = RSLMAIL_MIME::message(file_get_contents($file), $type, $name, $charset, $encoding, $disposition, $id, new RSLMAIL_Exception())) {
					$this->_attach[] = $arr;
					return true;
				} else return false;
			} else throw new Exception($error, 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function delattach($name = null) {
		try {
			if ($name == null) {
				$this->_attach = array();
				return true;
			} else if (is_string($name)) {
				$name = RSLMAIL_FUNC::str_clear($name);
				$name = trim(strtolower($name));
				if ($name != '') {
					$found = false;
					$new = array();
					foreach ($this->_attach as $arr) {
						if (strtolower($arr['name']) == $name) $found = true;
						else $new[] = $arr;
					}
					if ($found) {
						$this->_attach = $new;
						return true;
					} else return false;
				} else throw new Exception('invalid name value', 1);
			} else throw new Exception('invalid name type', 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, false); }
	}

	public function relay($host = null, $user = null, $pass = null, $port = null, $ssl = null, $timeout = null) {
		$name = $this->_host ? $this->_host : null;
		$arr = RSLMAIL_SMTP::connect($host, $user, $pass, $port, $ssl, $timeout, $name, new RSLMAIL_Exception());
		$this->result = $arr['result'];
		if ($this->_conn = $arr['connection']) return true;
		else return false;
	}

	public function send($subject = null, $charset = null, $encoding = null) {
		$ret = false;
		try {
			if ($charset == null) $charset = RSLMAIL_MIME::MCHARSET;
			if ($encoding == null) $encoding = RSLMAIL_MIME::MENCODING;
			$errors = array();
			if (!is_string($subject)) $errors[] = 'invalid subject type';
			else {
				$subject = RSLMAIL_FUNC::str_clear($subject);
				$subject = trim($subject);
				if ($subject == '') $errors[] = 'invalid subject value';
			}
			if (!is_string($charset)) $errors[] = 'invalid charset type';
			if (!is_string($encoding)) $errors[] = 'invalid encoding type';
			if (count($this->_to) == 0) $errors[] = 'to address is not set';
			if (!($this->_text || $this->_html)) $errors[] = 'message is not set';
			if (count($errors) == 0) {
				$subject = RSLMAIL_MIME::encode_header($subject, $charset, $encoding);
				$charset = RSLMAIL_FUNC::str_clear($charset, array(' '));
				$charlen = strlen($charset);
				if ($charlen < 4 || $charlen > 22) {
					$errors[] = 'invalid charset value';
					$charset = RSLMAIL_MIME::HCHARSET;
				}
				$encoding = RSLMAIL_FUNC::str_clear($encoding, array(' '));
				$encoding = strtolower($encoding);
				if ($encoding == '' || !isset(RSLMAIL_MIME::$_hencoding[$encoding])) {
					$errors[] = 'invalid encoding value';
					$encoding = RSLMAIL_MIME::HENCODING;
				}
				$hlocal = $hclient = $this->_header;
				if ($this->_from) $hfrom = $this->_from['name'] ? $this->_from['name'].' <'.$this->_from['address'].'>' : $this->_from['address'];
				else {
					$hfrom = @ini_get('sendmail_from');
					if ($hfrom == '' || !RSLMAIL_FUNC::is_mail($hfrom)) $hfrom = (isset($_SERVER['SERVER_ADMIN']) && RSLMAIL_FUNC::is_mail($_SERVER['SERVER_ADMIN'])) ? $_SERVER['SERVER_ADMIN'] : 'postmaster@localhost';
				}
				$ato = $alladdrs = array();
				foreach ($this->_to as $kto => $vto) {
					$ato[] = $vto ? $vto.' <'.$kto.'>' : $kto;
					$alladdrs[] = $kto;
				}
				$hto = implode(', '.RSLMAIL_MIME::CRLF."\t", $ato);
				$hcc = $hbcc = false;
				if (count($this->_cc) > 0) {
					$acc = array();
					foreach ($this->_cc as $kcc => $vcc) {
						$acc[] = $vcc ? $vcc.' <'.$kcc.'>' : $kcc;
						$alladdrs[] = $kcc;
					}
					$hcc = implode(', '.RSLMAIL_MIME::CRLF."\t", $acc);
				}
				if (count($this->_bcc) > 0) {
					$abcc = array();
					foreach ($this->_bcc as $kbcc => $vbcc) {
						$abcc[] = $kbcc;
						$alladdrs[] = $kbcc;
					}
					$hbcc = implode(', '.RSLMAIL_MIME::CRLF."\t", $abcc);
				}
				$hxmail = array('name' => 'X-Mailer', 'value' => 'Relyon Web Email Handler');
				$hlocal[] = array('name' => 'From', 'value' => $hfrom);
				if ($hcc) $hlocal[] = array('name' => 'Cc', 'value' => $hcc);
				if ($hbcc) $hlocal[] = array('name' => 'Bcc', 'value' => $hbcc);
				$hlocal[] = $hxmail;
				$hclient[] = array('name' => 'From', 'value' => $hfrom);
				$hclient[] = array('name' => 'To', 'value' => $hto);
				$hclient[] = array('name' => 'Subject', 'value' => $subject);
				if ($hcc) $hclient[] = array('name' => 'Cc', 'value' => $hcc);
				$hclient[] = array('name' => 'Date', 'value' => date('D, d M Y H:i:s O \(T\)'));
				if ($this->_priority && is_array($this->_priority)) {
					$hlocal[] = array('name' => 'X-Priority', 'value' => $this->_priority[0]);
					$hlocal[] = array('name' => 'X-MSMail-Priority', 'value' => $this->_priority[1]);
					$hclient[] = array('name' => 'X-Priority', 'value' => $this->_priority[0]);
					$hclient[] = array('name' => 'X-MSMail-Priority', 'value' => $this->_priority[1]);
				}
				$hclient[] = $hxmail;
				$hclient[] = array('name' => 'Message-Id', 'value' => '<'.RSLMAIL_MIME::unique($this->_unique++).'@relyonsoft.com>');
				$message = RSLMAIL_MIME::compose($this->_text, $this->_html, $this->_attach, $this->_unique);
				$this->_unique += 3;
				$header['local'] = $header['client'] = '';
				foreach ($hlocal as $arrloc) $header['local'] .= $arrloc['name'].': '.$arrloc['value'].RSLMAIL_MIME::CRLF;
				foreach ($hclient as $arrcli) $header['client'] .= $arrcli['name'].': '.$arrcli['value'].RSLMAIL_MIME::CRLF;
				$header['local'] .= $message['addheader'];
				$header['client'] .= $message['addheader'];
				$name = $this->_host ? $this->_host : '';
				$from = $this->_from ? $this->_from['address'] : $hfrom;
				$path = $this->_path ? $this->_path : '';
				foreach ($this->_vord as $delivery) {
					if (!$ret) {
						if ($delivery == 'relay') {
							if ($this->_conn && is_resource($this->_conn)) {
								$res = RSLMAIL_SMTP::sendtohost($this->_conn, $alladdrs, $from, $header['client'].RSLMAIL_MIME::CRLF.RSLMAIL_MIME::CRLF.$message['body'], $name, $path, $this->_port, $this->_timeout, new RSLMAIL_Exception());
								$ret = $res[0];
								$this->result = $res[1];
							} else {
								$ret = false;
								$errors[] = 'relay connection is not set or invalid ';
								break;
							}
						} else if ($delivery == 'client') {
							$ret = true;
							foreach ($alladdrs as $maddr) {
								$exp = explode('@', $maddr);
								$res = RSLMAIL_SMTP::sendtohost($exp[1], array($maddr), $from, $header['client'].RSLMAIL_MIME::CRLF.RSLMAIL_MIME::CRLF.$message['body'], $name, $path, $this->_port, $this->_timeout, new RSLMAIL_Exception());
								if (!$res[0]) $ret = false;
								$this->result[$maddr] = $res[1];
							}
						} else if ($delivery == 'local') {
							$replh = RSLMAIL_FUNC::is_win() ? $header['local'] : str_replace("\r\n", "\n", $header['local']);
							$replb = RSLMAIL_FUNC::is_win() ? str_replace("\n.", "\n..", $message['body']) : str_replace("\r\n", "\n", $message['body']);
							$rpath = (!RSLMAIL_FUNC::is_win() && $this->_path) ? '-f'.$this->_path : null;
							$spath = $this->_path ? @ini_set('sendmail_from', $this->_path) : false;
							if (!mail(implode(', ', $ato), $subject, $replb, $replh, $rpath)) {
								$res = RSLMAIL_SMTP::sendtohost('127.0.0.1', $alladdrs, $from, $header['client'].RSLMAIL_MIME::CRLF.RSLMAIL_MIME::CRLF.$message['body'], $name, $path, RSLMAIL_SMTP::PORT, $this->_timeout, new RSLMAIL_Exception());
								$ret = $res[0];
								$this->result = array('local' => array('error' => 'mail() return FALSE'), '127.0.0.1' => $res[1]);
							} else {
								$this->result['success'] = 'mail() return TRUE';
								$ret = true;
							}
							if ($spath) @ini_restore('sendmail_from');
						}
					} else break;
				}
				if (count($errors) > 0) throw new Exception(implode(', ', $errors), 1);
				return $ret;
			} else throw new Exception(implode(', ', $errors), 0);
		} catch (Exception $e) { return RSLMAIL_FUNC::exception_handler($e, $ret); }
	}

	public function quit() {
		$res = RSLMAIL_SMTP::quit($this->_conn);
		if ($res[1]) $this->result = $res[1];
		return $res[0];
	}

	public function close() {
		return RSLMAIL_FUNC::close($this->_conn);
	}

}

?>