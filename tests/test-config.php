<?php

// Configuration for tests

$cfg = [];

$cfg['plugins'] = [
	__DIR__ . '/../MediaWikiServicesCheckPlugin.php'
];

// Don't break on the nonsense calls to the fake services
$cfg['suppress_issue_types'] = [
	'PhanNonClassMethodCall',
];

// For testing aliases
$cfg['enable_class_alias_support'] = true;

return $cfg;
