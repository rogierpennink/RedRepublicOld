<?
$attPoints = 3460;
$defPoints = 16265;

$quotient = $attPoints / $defPoints;

echo "$attPoints / $defPoints = $quotient<br />";

$check = 0;

echo "Check: $check<br />";

if ( $quotient <= 0.8 ) { $check = 800 * $quotient - 200; echo "$quotient <= 0.8, check = $check.<br />"; }
elseif ( $quotient >= 1 ) { $check = 300 * $quotient + 200; echo "$quotient >= 1, check = $check.<br />"; }
else { $check = 1000 * pow( $quotient - 1, 3 ) + 250 * $quotient + 250; echo "$quotient > 0.8 && $quotient < 1, check = $check.<br />"; }
$check = ( $check < 50 ) ? 50 : $check;
$check = ( $check > 950 ) ? 950 : $check;

echo "Changed check: $check<br />";

$rand = mt_rand( 1, 1000 );

echo "Rand: $rand.";
?>