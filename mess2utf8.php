<?php

# This function takes a string as input and converts it in to a UTF-8
# string.  UTF-8 is generally left untouched; non-UTF-8 is assumed to
# be Microsoft CP-1252

function mess2utf8($in) {
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
			}
		}
		return $out;
	}

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
		} 	
	}

	return $out;
	
}

print mess2utf8("aáéíóúñ") . "\n";

?>