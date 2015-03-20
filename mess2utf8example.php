<?php

# This is a simple example script which shows mess2utf8 in action.
# This script takes data which may be CP-1252 -or- UTF-8 from the
# standard input, and outputs the UTF-8 conversion of that data to
# standard output.

# In a Linux or BSD or UNIX system, usage is like this:
# cat file | php mess2utf8example.php > file.utf8
# Where "file" is either CP-1252, ISO 8859-1, or UTF-8 contents
# file.utf8 will be in UTF-8 (Unicode) format

require_once("mess2utf8.php");

while($line = fgets(STDIN)) {
	print mess2utf8($line);
}
?>
