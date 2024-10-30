<?php
/*
Plugin Name: Milyen nap van most?
Plugin URI: http://vbali.com/milyen-nap-van-most/
Description: Hozzászóláskor magyar nyelven meg kell adni, hogy milyen nap van most éppen (a szerver ideje szerint).
Author: Gabor Lenard (gaba), Varkonyi Balazs (vbali)
Version: 1.6.1
Author URI: http://vbali.com/

Copyright 2007 Gabor Lenard

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/

define( 'MILYENNAP_MEZO', 'milyennapmost' );
define( 'MILYENNAP_KERDES', 'Milyen nap van most?' );
define( 'MILYENNAP_BELEPVE', '(Nem kell válaszolnod arra, hogy milyen nap van most, mivel már beléptél.)' );
define( 'MILYENNAP_ROSSZ', 'Sajnos nem jól adtad meg, hogy milyen nap van most.' );
define( 'MILYENNAP_VISSZA', '<br /><a href="javascript:history.back();">&laquo; Vissza</a>' );
$milyennapok = array( 'vasarnap/vasárnap', 'hetfo/hétfő', 'kedd', 'szerda', 'csutortok/csütörtök', 'pentek/péntek', 'szombat' );
define( 'MILYENNAP_VARAZSSZAVAK', 'fergeteges remek rettenetes vacak' );
define( 'MILYENNAP_WP_ORA_HASZNALATA', false );
define( 'MILYENNAP_TITOK1', ( strlen( $_SERVER['HTTP_USER_AGENT'] ) > 0 ? $_SERVER['HTTP_USER_AGENT'] : $_SERVER['SERVER_ADDR'] ) );
define( 'MILYENNAP_TITOK2', $_SERVER['SERVER_NAME'] );

if ( function_exists( 'add_action' ) && function_exists( 'add_filter' ) ) {
  add_action( 'comment_form', 'milyennap_kerdesfelteves' );
  add_filter( 'preprocess_comment', 'milyennap_valaszellenorzes', 0 );
}

if ( ! function_exists( 'wp_die' ) ) {
  function wp_die( $last_wish ) { die( $last_wish ); }
}

function milyennap_kerdesfelteves() { 
  global $user_ID;
  if ( ! isset( $user_ID ) || 0 == $user_ID ) { 
	if ( md5( MILYENNAP_TITOK1 ) == $_COOKIE[ md5( MILYENNAP_TITOK2 ) ] ) { ?>
	    <p class="comment-login">
	      (Korábban egyszer már helyesen válaszoltál a <a href="http://gaba.lenard.hu/wordpress-plugins/milyen-nap-van-most/"><?php echo MILYENNAP_KERDES ?></a> kérdésre erről a gépről, úgyhogy többször nem kell.)
	    </p><?php
	} else {
	?>
    <p>
      <label for="<?php echo MILYENNAP_MEZO ?>"><a href="http://gaba.lenard.hu/wordpress-plugins/milyen-nap-van-most/"><?php echo MILYENNAP_KERDES ?></a></label>
      <input type="text" name="<?php echo MILYENNAP_MEZO ?>" id="<?php echo MILYENNAP_MEZO ?>" tabindex="5" size="10" maxlength="12" />
    </p><?php
	}
  } else { ?>
    <p class="comment-login"><?php echo MILYENNAP_BELEPVE ?></p><?php
  }
}

function milyennap_valaszellenorzes( $comment_data ) {
  global $user_ID;
  if ( (isset($user_ID) && $user_ID) || md5( MILYENNAP_TITOK1 ) == $_COOKIE[ md5( MILYENNAP_TITOK2 ) ] )
    return $comment_data;
  $comment_type = strtolower( $comment_data[ 'comment_type' ] );
  if ( '' == $comment_type ) { 
	$valasz = trim( $_POST[ MILYENNAP_MEZO ] );
	$jovalasz = ( milyennap_varazsszo_e( $valasz ) || milyennap_jo_e( $valasz ) );
    if ( strlen( $valasz ) > 0 && $jovalasz ) {
	  setcookie( md5( MILYENNAP_TITOK2 ), md5( MILYENNAP_TITOK1 ), time()+9999999, '/' );
	} else {
      wp_die( MILYENNAP_ROSSZ . MILYENNAP_VISSZA );
    }
  }
  return $comment_data;
}

function milyennap_varazsszo_e( $szo ) {
  if ( function_exists( 'mb_detect_encoding' ) && function_exists( 'mb_strtolower' ) && function_exists( 'mb_convert_encoding' ) ) {
    $encoding = mb_detect_encoding( $szo );
    $szo =  mb_strtolower( $szo, $encoding );
    $varazsszavak = mb_strtolower( mb_convert_encoding( MILYENNAP_VARAZSSZAVAK, $encoding, mb_detect_encoding( MILYENNAP_VARAZSSZAVAK ) ), $encoding );
    return in_array( $szo, explode( ' ', $varazsszavak ) );
  } else {
    return in_array( $szo, explode( ' ', $milyenvarazsszavak ) );
  }
}

if ( function_exists( 'current_time' ) && MILYENNAP_WP_ORA_HASZNALATA ) {
  define( 'MILYENNAP_MA', date( 'w', current_time( 'timestamp' ) ) );
} else {
  define( 'MILYENNAP_MA', date( 'w' ) );
}
define( 'MILYENNAP_ELV', '/' );
function milyennap_jo_e( $napnev, $napszam = MILYENNAP_MA ) {
  global $milyennapok;
  $milyennap = MILYENNAP_ELV . $milyennapok[ $napszam ] . MILYENNAP_ELV;
  if ( function_exists( 'mb_detect_encoding' ) && function_exists( 'mb_strtolower' ) && function_exists( 'mb_convert_encoding' ) && function_exists( 'mb_strpos' ) ) {
    $encoding = mb_detect_encoding( $milyennap );
    $milyennap = mb_strtolower( $milyennap, $encoding );
    $napnev = mb_convert_encoding( $napnev, $encoding, mb_detect_encoding( $napnev ) );
    $napnev = MILYENNAP_ELV . mb_strtolower( $napnev, $encoding ) . MILYENNAP_ELV;
    $jo_e = mb_strpos( $milyennap, $napnev, 0, $encoding ) !== false;
  } else {
    $napnev = MILYENNAP_ELV . $napnev . MILYENNAP_ELV;
    $jo_e = strpos( strtolower( $milyennap ), $napnev ) !== false;
  }
  return $jo_e;
}

?>