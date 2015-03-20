# mess2utf8
Converts a mixture of Windows CP-1252 and UTF-8 in to pure UTF-8

This defines a function, mess2utf8, which takes a string as input and
returns a string as output.

Windows CP-1252 9Code page 1252) is a superset of ISO 8859-1 which
Microsoft developed in the 1990s; there are still systems which use
this coding system.

The input string can be almost any combination of Windows code page 1252,
ISO 8859-1, and/or UTF-8 text.  Using heuristics, code points which do
not look like valid UTF-8 are converted from CP-1252 in to UTF-8.

The file mess2utf8.php can be included in other PHP scripts via the
"require_once()" directive.

