<?php

// Stubs for testing
class SpecialPage {

	public function __construct($a, $b) {
	}
}

// For confirming that sub-sub classes also work
class FormSpecialPage extends SpecialPage {}

class ApiBase {}

class ApiQueryBase extends ApiBase {}

