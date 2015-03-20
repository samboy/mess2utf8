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

print "All tests PASS\n"

?>
