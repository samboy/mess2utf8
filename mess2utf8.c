/* 
 * Copyright (c) 2015, Sam Trenholme
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, 
 *   this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF 
 * THE POSSIBILITY OF SUCH DAMAGE.

 * This takes a string as standard input and converts it in to a UTF-8
 * string.  UTF-8 is generally left untouched; non-UTF-8 is assumed to
 * be Microsoft CP-1252.  Output is standard out.
 */

#include <stdio.h>

int count_leading_1s(int num) {
	int out = 0;
	if(num > 255 || num < 0) {
		return -1;
	}
	while((num & 0x80) == 0x80) {
		num <<= 1;
		num &= 0xff;
		out++;
	}
	return out;
}

char *cp1252_table(int num) {
                if(num == 0x80) {
                        return "€";
                } else if(num == 0x82) {
                        return "‚";
                } else if(num == 0x83) {
                        return "ƒ";
                } else if(num == 0x84) {
                        return "„";
                } else if(num == 0x85) {
                        return "…";
                } else if(num == 0x86) {
                        return "†";
                } else if(num == 0x87) {
                        return "‡";
                } else if(num == 0x88) {
                        return "ˆ";
                } else if(num == 0x89) {
                        return "‰";
                } else if(num == 0x8a) {
                        return "Š";
                } else if(num == 0x8b) {
                        return "‹";
                } else if(num == 0x8c) {
                        return "Œ";
                } else if(num == 0x8e) {
                        return "Ž";
                } else if(num == 0x91) {
                        return "‘";
                } else if(num == 0x92) {
                        return "’";
                } else if(num == 0x93) {
                        return "“";
                } else if(num == 0x94) {
                        return "”";
                } else if(num == 0x95) {
                        return "•";
                } else if(num == 0x96) {
                        return "–";
                } else if(num == 0x97) {
                        return "—";
                } else if(num == 0x98) {
                        return "˜";
                } else if(num == 0x99) {
                        return "™";
                } else if(num == 0x9a) {
                        return "š";
                } else if(num == 0x9b) {
                        return "›";
                } else if(num == 0x9c) {
                        return "œ";
                } else if(num == 0x9e) {
                        return "ž";
                } else if(num == 0x9f) {
                        return "Ÿ";
                }
	return "";
}

void cp1252_to_utf8(char *in, int length) {
	int index;
	int value;
	for(index = 0; index < length; index++) {
		value = *(unsigned char *)(in + index);
		if(value >= 0xa0 && value <= 0xff) {
			printf("%c%c",(value >> 6) | 0xc0,
				(value & 0x3f) | 0x80);
		} else if(value < 0x7f) {
			printf("%c",value);
		} else { 
			printf("%s",cp1252_table(value));
		}
	}
}

void mess2utf8() {
	char buffer[12];
	int length = 0;
	int count = 0;
	int state = 0;
	int a = 0, b = 0;
	int s1 = 0;

	while(!feof(stdin)) {
		a = getc(stdin);
		if(a < 0 || a > 255) {
			break;
		}
		s1 = count_leading_1s(a);
		if(s1 == 0) {
			if(state != 0) {
				cp1252_to_utf8(buffer, length);
			}
			putc(a,stdout);
			length = 0;
			state = 0;
		} else if(s1 == 1) {
			if(state == 0) {
				buffer[0] = a;
				length = 1;
				cp1252_to_utf8(buffer, length);
				length = 0;
			} else {
				state--;
				if(length < 10) {
					buffer[length] = a;
					length++;
				}
				if(state == 0) {
					for(b = 0; b < length; b++) {
						putc(buffer[b],stdout);
					}
					length = 0;
				}
			}
		} else if(s1 <= 6 && s1 > 1) {
			if(state != 0) {
				cp1252_to_utf8(buffer, length);
			}
			state = s1 - 1;
			length = 1;
			buffer[0] = a;
		} else {
			if(length < 10) {
				buffer[length] = a;
				length++;
			}
			cp1252_to_utf8(buffer, length);
			length = 0;
		}
	}
	if(state != 0) {
		cp1252_to_utf8(buffer, length);
	}
}

int main() {
	mess2utf8();
}
