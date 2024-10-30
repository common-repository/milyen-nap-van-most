<html><head><title>Milyen nap van most? - smoke test</title></head><body><?php

require_once 'milyen-nap-van-most.php';

echo "MILYENNAP_MA: ", MILYENNAP_MA;

echo "<h1>section a</h1>";

// milyennap_hibas_e
if ( ! milyennap_jo_e( 'vasarnap', 0 ) )
	echo '<li>hiba a1 ';
if ( ! milyennap_jo_e( 'Vasárnap', 0 ) )
	echo '<li>hiba a2 ';
if ( ! milyennap_jo_e( 'KEDD', 2 ) )
	echo '<li>hiba a3 ';

echo "<h1>section b</h1>";

// milyennap_varazsszo_e
if ( ! milyennap_varazsszo_e( 'fergeteges' ) )
	echo '<li>hiba b1 ';
if ( milyennap_varazsszo_e( 'semmilyen' ) )
	echo '<li>hiba b2 ';

?></body></html>