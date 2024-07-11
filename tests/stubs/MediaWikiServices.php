<?php

namespace MediaWiki;

// Stub for testing
class MediaWikiServices {

	public static function getInstance(): self {
		return new MediaWikiServices();
	}

	public function get( string $name ) {
		return $this->getService( $name );
	}
	
	public function getService( string $name ) {
		return $name;
	}

	public function peekService( string $name ) {
		return $name;
	}

	public function getUserFactory() {
		return 1;
	}

	public function getUserOptionsManager() {
		return 2;
	}
}