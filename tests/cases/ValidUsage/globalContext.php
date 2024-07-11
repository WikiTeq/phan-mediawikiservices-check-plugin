<?php

use MediaWiki\MediaWikiServices;

function test1() {
    $service = MediaWikiServices::getInstance()->getService( 'MyService' );
    $service->run( $par );
}
