<?php

require_once (__DIR__ . "/../phpmin.php");

$html = file_get_contents("http://en.wikipedia.org/wiki/Minification_%28programming%29");
$minified = PHPMin\Minify::html($html);
echo print_performance_graph("Wikipedia", $minified, $html);

$html = file_get_contents("http://www.codeproject.com/Articles/759094/Step-by-Step-PHP-Tutorials-for-Beginners-Creating");
$minified = PHPMin\Minify::html($html);
echo print_performance_graph("The Code Project",$minified,$html);


$html = file_get_contents("https://github.com/php/php-src");
$minified = PHPMin\Minify::html($html);
print_performance_graph("GiHub",$minified,$html);


$html = file_get_contents("http://www.w3schools.com/php/");
$minified = PHPMin\Minify::html($html);
print_performance_graph("W3Schools",$minified,$html);


$html = file_get_contents("http://searchturbine.com");
$minified = PHPMin\Minify::html($html);
print_performance_graph("SearchTurbine",$minified,$html);


///////////////////////////////////////////////////////////////

function print_performance_graph($subject, $minified, $html)
{
	$before = strlen(gzcompress($html));
	$after = strlen(gzcompress($minified));	
	$improvement =  100 * (1-($after/$before));

	echo  "<table style='width:100%; border:1px solid grey;text-align:center'>
	<tr>
		<th colspan=3><b>$subject</th>
	</tr>
	<tr>
		<th>Gzipped Bytes Before PHPMin</th>
		<th>Gzipped Bytes After PHPMin</th>
		<th>% Performance Boost</th>
	</tr>
	<tr>
		<td>$before before</td>
		<td>$after after</td>
		<td>$improvement % faster</td>
	</tr>
</table>" . "\n\n\n";
}
