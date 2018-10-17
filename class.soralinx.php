<?php
/**
 * Soralinx v1 | MIT License
 */

namespace msgtrwn;

if( class_exists( 'Soralinx' ) ) return;

class Soralinx {
	private $document;
	private $element;
	protected $html;
	protected $url;
	protected $post;
	protected $id;
	protected $error = array();
	protected $result = array();
	public function __construct( $url = '' ) {
		libxml_use_internal_errors( true );
		$this->document = new \DOMDocument();
		if( !empty( $url ) ) {
			$this->url = $url;
			$this->get( $this->url );
		}
	}
	public function getHTML( $url, $postfields = array() ) {
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		if( !empty( $postfields ) ) {
			$build_query = http_build_query( $postfields );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $build_query );
		}
		$exec = curl_exec( $ch );
		$err = curl_error( $ch );
		$info = curl_getinfo( $ch );
		curl_close( $ch );
		if( $err ) {
			$message = sprintf( '(%1$s method from class %2$s) Cannot get HTML source from %3$s.', __METHOD__, __CLASS__, $url );
			$this->addErrorLog( $message, __LINE__ - 2 );
			return false;
		}
		$this->html = $exec;
		return $exec;
	}
	public function getFormData( $html ) {
		$this->document->loadHTML( $html );
		$document = $this->document;
		$container = $document->getElementById( 'srl' );
		$xpath = new \DOMXPath( $document );
		$form = $xpath->query( './/form[@method="post"]' );
		$id = $xpath->query( './/input[@name="get"]', $container );
		if( $form->length && $id->length ) {
			$this->post = $form[0]->getAttribute( 'action' );
			$this->id = $id[0]->getAttribute( 'value' );
			return true;
		}
		$message = sprintf( '(%1$s method from class %2$s) Invalid HTML.', __METHOD__, __CLASS__ );
		$this->addErrorLog( $message, __LINE__ );
		return false;
	}
	public function getDirectURL( $html ) {
		$pattern = '/var\sa\=\'(?<data>.*)\'\;/';
		if( preg_match( $pattern, $html, $match ) ) {
			return $match['data'];
		}
		$message = sprintf( '(%1$s method from class %2$s) Direct URL not found.', __METHOD__, __CLASS__ );
		$this->addErrorLog( $message, __LINE__ );
		return false;
	}
	// public function isSupportedURL( $url ) {}
	// public function addSupportURL( $url ) {}
	public function getErrorLog() {
		return $this->error;
	}
	public function addErrorLog( $message, $line ) {
		$this->error[] = sprintf( 'Error at line #%1$s: "%2$s"', $line, $message );
	}
	public function getResult() {
		return $this->result;
	}
	public function get( $url ) {
		$this->url = $url;
		if( $this->getHTML( $this->url ) ) {
			if( $this->getFormData( $this->html ) ) {
				if( $this->getHTML( $this->post, array(
					'get' => $this->id
				) ) ) {
					if( $result = $this->getDirectURL( $this->html ) ) {
						$this->result[ $url ] = $result;
					}
				}
			}
		}
	}
}