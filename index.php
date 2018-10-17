<?php
require_once './class.soralinx.php';

use \msgtrwn\Soralinx as Soralinx;

header( 'Content-Type: application/json' );
$url = $_GET['try'];
$slx = new Soralinx( $url );
header( 'Location: ' . $slx->getResult()[$url] );
exit( $slx->getResult()[$url] );