<?php

# This function takes a string as input and converts it in to a UTF-8
# string.  UTF-8 is generally left untouched; non-UTF-8 is assumed to
# be Microsoft CP-1252

	# Input: 8-bit number
	# Output: Number of leading binary 1s in the input number
	function count_leading_1s($num) {
		if($num > 255 || $num < 0) {
			return -1;
		}
		$out = 0;
		while(($num & 0x80) == 0x80) {
			$num <<= 1;
			$num &= 0xff;
			$out++;
		}
		return $out;
	}

	# Input: 8-bit number corresponding to a CP-1252 point between
	# 0x80 and 0x9f (the area not defined in ISO-8859-1
	# Output: A string with the UTF-8 for that codepoint
	function cp1252_table($num) {
		if($num == 0x80) {
			return "€";
		} else if($num == 0x82) {
			return "‚";
		} else if($num == 0x83) {
			return "ƒ";
		} else if($num == 0x84) {
			return "„";
		} else if($num == 0x85) {
			return "…";
		} else if($num == 0x86) {
			return "†";
		} else if($num == 0x87) {
			return "‡";
		} else if($num == 0x88) {
			return "ˆ";
		} else if($num == 0x89) {
			return "‰";
		} else if($num == 0x8a) {
			return "Š";
		} else if($num == 0x8b) {
			return "‹";
		} else if($num == 0x8c) {
			return "Œ"; 
		} else if($num == 0x8e) {
			return "Ž";
		} else if($num == 0x91) {
			return "‘";
		} else if($num == 0x92) {
			return "’";
		} else if($num == 0x93) {
			return "“";
		} else if($num == 0x94) {
			return "”";
		} else if($num == 0x95) {
			return "•";
		} else if($num == 0x96) {
			return "–";
		} else if($num == 0x97) {
			return "—";
		} else if($num == 0x98) {
			return "˜";
		} else if($num == 0x99) {
			return "™";
		} else if($num == 0x9a) {
			return "š";
		} else if($num == 0x9b) {
			return "›";
		} else if($num == 0x9c) {
			return "œ";
		} else if($num == 0x9e) {
			return "ž";
		} else if($num == 0x9f) {
			return "Ÿ";
		}
	}

	# Input:  CP1252 string.
	# Output: UTF-8 string.
	function cp1252_to_utf8($in) {
		$out = "";
		$index = 0;
		for($index = 0; $index < strlen($in); $index++) {
			$value = ord(substr($in,$index));
			if($value >= 0xa0 && $value <= 0xff) {
				$out .= sprintf("%c%c",($value >> 6) | 0xc0,
						($value & 0x3f) | 0x80);
			} else if($value <= 0x7f) {
				$out .= chr($value);
			} else {
				$out .= cp1252_table($value);
			}
		}
		return $out;
	}

function mess2utf8($in) {
	$out = "";
	$buffer = "";
	$index = 0;
	$count = 0;
	$state = 0;

	for($index = 0; $index < strlen($in); $index++) {
		$value = ord(substr($in,$index));
		$s1 = count_leading_1s($value);
		if($s1 == 0) {
			if($state != 0) {
				$out .= cp1252_to_utf8($buffer);
			}
			$out .= chr($value);
			$buffer = "";
			$state = 0;
		} else if($s1 == 1) {
			if($state == 0) {
				$out .= cp1252_to_utf8(chr($value));
				$buffer = "";
			} else {
				$state--;
				$buffer .= chr($value);
				if($state == 0) {
					$out .= $buffer;
					$buffer = "";
				}
			}
		} else if($s1 <= 6 && $s1 > 1) {
			if($state != 0) {
				$out .= cp1252_to_utf8($buffer);
			}
			# While UTF-8 currently only allows sequences
			# one to four bytes long, we will keep the 
			# original spec which allows sequences up to
			# six bytes long
			$state = $s1 - 1;	
			$buffer = chr($value);
		} else {
			$buffer .= chr($value);
			$out .= cp1252_to_utf8($buffer);
			$buffer = "";
		}
			
	}
	if($state != 0) {
		$out .= cp1252_to_utf8($buffer);
	}

	return $out;
	
}
?>
