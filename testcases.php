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

print "All tests PASS\n"

?>
