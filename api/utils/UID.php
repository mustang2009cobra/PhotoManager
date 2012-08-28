<?php
/**
*	File UID

	@author Alberto Trevi�o, BYU Testing Center

@copyright (C) 2007, Alberto Trevi�o, BYU Testing Center
You may use, copy, modifiy, and distribute this file as desired as long as you
give credit to the original authors.

Description:
	Generates UIDs and transforms them to UUID's or GUID's and back.  It uses
	the mt_random function to generate the random data.
	
	UID's were developed by the author to strike a balance between storage
	space and index size.  UID's are 72 bits long and are encuded using a
	modified base64 encoding.  This results in a 12-byte identifier capable of
	generating up to 4,722,366,482,869,645,213,696 ID's.
	
	UID's are meant to be somewhat human readable.  For that reason, the only
	"proper" way to store or handle a UID is in its modified base64 encoding.
	The use of binary UID's is discouraged.
	
	The reasoning for 72 bits is as follows.  Base-64 encoding makes the binary
	data larger by a ration of 4:3.  To obtain the maximum packing ability of
	base64 (no padding) the total number of bytes (in binary) needs to be
	divisible by 3.  This leads to the following bit combinations:
	
		Bits   Bytes   Base64 Length  Number of available ID's
		---------------------------------------------------------------------
		 24     3       4 bytes       16,777,216 (too small)
		 48     6       8 bytes       281,474,976,710,656 (usable)
		 72     9      12 bytes       4,722,366,482,869,645,213,696 (desired)
		 96    12      16 bytes       79,228,162,514,264,337,593,543,950,336
		120    15      20 bytes       1.329 E+36 (too close to GUID size)
		
	As can be seen from the above table, using 24 or 48 bits produces too small
	of a pool of ID's when using random generation methods.  96 bits would take
	16 bytes to store -- the same as a binary GUID.  120 bits is too similar to
	a GUID in size and range.  72 bits appears to strike the right balance.
	
	To generate a UID, you need 72 random bits.  These bits are then converted
	to a 9-byte binary string and encoded using base64.  The base64-encoded
	string is then modified, replacing / for _ and + for - to make it usable
	in both SQL and URI's without modification.
	
	To convert a UID to to GUID, you convert the UID to binary, take the first
	32 bits (or 4 bytes), add 56 "0" bits, and add the remaining 40 bits to the
	end.  When converted to 36-byte HEX format, the GUID should look like this:
	
		########-0000-0000-0000-00##########

	It is possible to convert a GUID to a UID but the process is not reversible.
	To convert it, you take the binary GUID and concatenate the first 32 bits
	and the last 40 bits and encode to the modified base64.

	This PHP class allows you to genrate new UID's, convert them to 36-byte
	HEX, and convert 36-byte HEX GUID's to UID's.  It should also serve as a
	reference guide to port this class to other languages.
*/

/**
*	Class UID
*	@package LSAPI
*/
class GuidGenerator
{
	/***************************************************************************
	CONTRUCTOR AND DESTRUCTOR
	***************************************************************************/
	/**	 
	 * 	Defines an empty constructor
	 *	@author Alberto Trevi�o
	 *	Last revised: 2007-01-22
	 */
	public function __construct() { }

	/**
	 * 	Defines an empty destructor
	 *	@author Alberto Trevi�o
	 *	Last revised: 2007-01-22
	 */
	public function __destruct() { }

	/***************************************************************************
	PUBLIC PROPERTIES AND METHODS
	***************************************************************************/
	
	const StrLength = 12;
	
	/**	
	 * Reports whether the string is a valid UID
	 *	@author Alberto Trevi�o
	 *	@param string $String String to check
	 *	@return bool True if a valid UID, false otherwise
	 *	Last revised: 2007-0427
	 */
	public static function IsUID($String)
	{
		if (preg_match('/^[0-9A-Za-z_\-]{12}$/', $String))
		{
			return true;
		}
		return false;
	}

	/** Creates a new UID (random by definition)
	 *	@author Alberto Trevi�o
	 *	@return string UID
	 *	Last revised: 2007-04-27
	 */
	public static function Create()
	{
		# Generate 72 random bits, encode the bits into base64, and replace
		# / for _ and + for -
		return str_replace(array("/", "+"), array("_", "-"),
			base64_encode(pack("S", mt_rand(0, 65535)) .
				pack("S", mt_rand(0, 65535)) .
				pack("S", mt_rand(0, 65535)) .
				pack("S", mt_rand(0, 65535)) .
				pack("C", mt_rand(0, 255))));
	}
	
	/** Converts a GUID to a UID
	 *	@author Alberto Trevi�o
	 *	@param string $GUID 36-byte HEX GUID
	 *	@return string UID (12 bytes)
	 *	@throws exceptions
	 *	Last revised: 2007-04-27
	 */
	public static function fromGuid($GUID)
	{
		# Make sure we have a GUID
		if (! preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-' .
			'[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $GUID))
		{
			throw new InvalidArgumentException("Invalid GUID");
		}
		
		# Convert the string to binary, cut out the midlle 56 bits and convert
		# to a UID
		$guid = pack("H32", str_replace("-", "", $GUID));
		return str_replace(array("/", "+"), array("_", "-"),
			base64_encode(substr($guid, 0, 4) . substr($guid, 11, 10)));
	}

	/**
	 *  Converts a UID to a GUID
	 *	@author Alberto Trevi�o
	 *	@param string $UID 12-byte UID
	 *	@return string GUID (36 bytes)
	 *	@throws exceptions
	 *	Last revised: 2007-04-27
	 */
	public static function toGuid($UID)
	{
		# Make sure we have a valid UID
		if (! self::IsUID($UID))
		{
			throw new InvalidArgumentException("Invalid UID");
		}
		
		# Convert the UID to binary
		$bin_uid = base64_decode(str_replace(array("_", "-"), array("/", "+"),
			$UID));
		
		//The guidMiddle was originally hard coded as -0000-0000-0000-00  now we are generating a 14 character string and plugging the '-' hyphens in
		$guidMiddle = self::random_string(14);
		$guidMiddle = substr_replace($guidMiddle, '-', 12, 0);
		$guidMiddle = substr_replace($guidMiddle, '-', 8, 0);
		$guidMiddle = substr_replace($guidMiddle, '-', 4, 0);
		$guidMiddle = substr_replace($guidMiddle, '-', 0, 0);
		
		# Encode as something resembling a GUID
		return bin2hex(substr($bin_uid, 0, 4)) .
			$guidMiddle . bin2hex(substr($bin_uid, 4, 9));
		
	}
	
	/**
	 *  Returns random string of specified length with values grabbed from this set: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwz0123456789"
	 *	@author Greg Bodily - found on php site.
	 *	@param $l int length of string to return
	 *	@return string Random string of specified length
	 *	Last revised: 2012-03-15
	 */
	private static function random_string($l = 10){
	    $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwz0123456789";
	    $s = '';
	    for(;$l > 0;$l--) $s .= $c{rand(0,strlen($c)-1)};
	    return str_shuffle($s);
	}

	/***************************************************************************
	PROTECTED PROPERTIES AND METHODS
	***************************************************************************/

	/***************************************************************************
	PRIVATE PROPERTIES AND METHODS
	***************************************************************************/
}
