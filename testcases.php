<?php

require_once("mess2utf8.php");

function prove($in,$expected) {
	if($in != $expected) {
		print "Test failure: $in should be $expected\n";
		exit(123);
	}
	print "PASS: $in\n";
}

# Test #1: Pure simple UTF-8
prove(mess2utf8("aáéíóúñ"),"aáéíóúñ");
# Test #2: More complicated UTF-8
prove(mess2utf8("‘’“a”¡"),"‘’“a”¡");
# Test #3: Some CP-1252
prove(mess2utf8("\x95\x99\x93\x94"),"•™“”");
# Test #4: FE and FF do not appear in UTF-8
prove(mess2utf8("\xfe\xff"),"þÿ");
# Test #5: Some ISO-8859-1
prove(mess2utf8("\xa1!\xbf?\xe1a\xe9e\xedi\xf3o\xfau\xf1"),"¡!¿?áaéeíióoúuñ");

print "All tests PASS\n"

?>
