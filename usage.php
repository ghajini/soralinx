<?php
require_once './class.soralinx.php';

use \msgtrwn\Soralinx as Soralinx;

$url = 'http://davinsurance.com/?id=WFJveDVKbFZUQkpIcXZVZXlPNlRsMis5VHVTN2Fnbjllb0pkU3U2bW54UDY4bW5HRlJDYkoxYkFncElTVWVUenIyQkpBcitvZFlydm9BQTQ2QWhGTGpmMXZhNGJudTl1N1lhK2oySk9GNlpyL1ZJelkzZ1RJU0M5enpiYzVLamtCTVlRZ2M4SFB5TVAwejNGelB4Y21pYjF6TUd6WmxjL3VDdUhIT3pLTUFpNUZYanlJYVllUzNJajl1dzRXQmJEc1UzdFg4dlNPUWk5K0FPcnlsbVQ0dz09';
$slx = new Soralinx( $url );

print( json_encode( $slx->getResult() ) );