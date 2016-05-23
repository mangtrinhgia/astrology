<?php
/*Various conversions
	DecToLong - converts decimal value to longitude
	DecToLat  - converts decimal value to latitude
	DecToTime - converts decimal value to time - 12 or 24 hour clock
	RecToPol  - converts rectangular coordinates to polar
	PolToRec  - converts polar coordinates to rectangular
	DecToZod  - converts decimal to zodiac
	DecToZodGlyhp - comverts decimal to zodiac using charater for glyph
	DecToZodStruct - converts decimal to zodiac structure
	StrToTime - converts string to decimal time
	Mod360 
	BasInt
*/

define("SMALL",  1.7453e-09);

class Polar {
	public $a;
	public $r;
}

class Rectangular {
	public $x;
	public $y;
}

class Coordinates {
	public $a;
	public $r;
	public $x;
	public $y;
	
	function RecToPol($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
		if (! $y)
			$y = SMALL;
		$this->r = sqrt($x * $x + $y * $y);
		$this->a = atan($y / $x);
		if ($this->a < 0)
			$this->a += M_PI;
		if ($y < 0)
			$this->a += M_PI;
	}
	function PolToRec($a, $r)
	{
		$this->a = $a;
		$this->r = $r;
		if (! $a)
			$a=SMALL;
		$this->x = $r * cos($a);
		$this->y = $r * sin($a);	
	}
}

/***********************************************************************/

function DecToTime ($dec_time, $clock_type)
{
	$time_hours = (int) $dec_time;
	$dec_time -= $time_hours;
	$dec_time = $dec_time * 60.0 +.5;
	
	$time_min = (int) $dec_time;
	if ($clock_type == 12)
	{
		if ($time_hours >= 12)
		{
			if ($time_hours > 12)
				$time_hours -= 12;
			$am_pm = 'P';
		}
		else
		{
			if ($time_hours == 0)
				$time_hours += 12;
			$am_pm = 'A';
		}
		$txtTime = sprintf ("%d:%02d %sM", $time_hours, $time_min, $am_pm);
	}
	else
		$txtTime = sprintf ("%d:%02d", $time_hours, $time_min);

	return $txtTime;
}
/***********************************************************************/
function DecToLong($dec_lng)
{
	if ($dec_lng < 0)
		$dir= 'E';
	else
		$dir = 'W';
	$dec_lng = abs($dec_lng);
	$lng_deg = (int) $dec_lng;
	$dec_lng -= $lng_deg;
	$dec_lng = $dec_lng * 60.0 + .5;
	$lng_min = (int) $dec_lng;
	$txtLong = sprintf ("%d%s%02d",$lng_deg,$dir,$lng_min);
	return $txtLong;
}

/***********************************************************************/
function DecToLat($dec_lat)
{
	if ($dec_lat < 0)
		$dir = 'S';
	else
		$dir = 'N';
	$dec_lat = abs($dec_lat);
	$lat_deg = (int) $dec_lat;
	$dec_lat -= $lat_deg;
	$dec_lat = $dec_lat * 60.0 + .5;
	$lat_min = (int) $dec_lat;
	$txtLat = sprintf ("%d%s%02d",$lat_deg,$dir,$lat_min);
	return $txtLat;
}

/***********************************************************************/

function RecToPol($x, $y)
/* rectangular to polar covnversion */
{
	if (! $y)
		$y = SMALL;
	$rr = sqrt($x * $x + $y * $y);
	$aa = atan($y / $x);
	if ($aa < 0)
		$aa += M_PI;
	if ($y < 0)
		$aa += M_PI;
		
	$pol = new Polar();
	$pol->a = $aa;
	$pol->r = $rr;
	return $pol;
}

/***********************************************************************/

function PolToRec($a, $r)
/* polar to rectangular conversion */
{
	if (! $a)
		$a=SMALL;
	$rec = new Rectangular();
	$rec->x = $r * cos($a);
	$rec->y = $r * sin($a);
	return $rec;
}

/*************************************************************************/

function Mod360($x)
{
/*  returns result within circle    */
	$x = ( $x - ((int)($x / 360)) * 360.0);
	if ($x < 0)
	{
		$x = 360.0 + $x;
	}
	return $x;
}

function Mod2Pi($x)
/*  returns result within circle in radians    */
{
	return ( $x - ((int)($x / M_PI*2)) * M_PI*2);
}
function DecToZod($plce)
{
$SignNames= array(' ARI ', ' TAU ', ' GEM ', ' CAN ', ' LEO ', ' VIR ', ' LIB ', ' SCO ', ' SAG ', ' CAP ', ' AQU ', ' PIC ');
	$s = (int) ($plce / 30.0);
	$plce -= ($s * 30);
	$d = (int) $plce;
	$m = (int) ((($plce - $d) * 60.0) + .5);

	if ($m >=60)
	{
		$m -=60;
		$d++;
	}
	if ($d >= 30)
	{
		$d-=30;
		$s++;
	}
	if ($s >=12)
	{
		$s-=12;
	}

	$zod = sprintf("%d %s %02d", $d, $SignNames[$s], $m);
	return $zod;
}

function DecToZodGlyph($plce)
{
$SignNames= array(' a ', ' b ', ' c ', ' d ', ' e ', ' f ', ' g ', ' h ', ' i ', ' j ', ' k ', ' l ');
	$s = (int) ($plce / 30.0);
	$plce -= ($s * 30);
	$d = (int) $plce;
	$m = (int) ((($plce - $d) * 60.0) + .5);

	if ($m >=60)
	{
		$m -=60;
		$d++;
	}
	if ($d >= 30)
	{
		$d-=30;
		$s++;
	}
	if ($s >=12)
	{
		$s-=12;
	}

	$zod = sprintf("%d %s %02d", $d, $SignNames[$s], $m);
	return $zod;
}
/*************************************************************************/

/*
$c = new Coordinates();

$c->PolToRec(1,2);
print_r($c);
echo "<br>";

echo $c->x;
echo $c->y;
echo "<br>";

$x = $c->x;
$y = $c->y;

$c->RecToPol($x, $y);
echo "<br>";

print_r($c);
echo "<br>";
echo $c->x;
echo $c->y;
echo "<br>";

$p = new Polar();
$r = new Rectangular();

echo "<br>";
$p = RecToPol($c->x, $c->y);

print_r($p);

echo "<br>";
$r= PolToRec($c->a, $c->r);
print_r($r);

echo "<br>";
echo Rad(180);
echo "<br>";
echo Deg(M_PI);
echo "<br>";
echo '---';
echo DecToZod(36.1);
echo "<br>";
echo '---';
echo "<br>";
*/