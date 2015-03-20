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

## Example

The included script mess2utf8example.php shows a simple PHP script using
the mess2utf8 function. 

## Bugs

UTF-8 technically only allows mappings to valid Unicode points, which
means a given UTF-8 sequence is four bytes or less.  This code allows
UTF-8 sequences up to six bytes in length, as was allowed in earlier
UTF-8 RFCs.

It technically is an error if a UTF-8 sequence is too long to represent
a given Unicode code point.  This code does not consider sequences like
that errors.


