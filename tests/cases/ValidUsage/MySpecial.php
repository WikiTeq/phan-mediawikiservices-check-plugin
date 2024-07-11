<?php

use MediaWiki\MediaWikiServices;

class MySpecial extends SpecialPage {

    public function testPeek() {
        return MediaWikiServices::getInstance()->peekService( 'MyService' );
    }

    public static function testStatic( $par ) {
        // Static method should be ignored
        $service = MediaWikiServices::getInstance()->getService( 'MyService' );
        $service->run( $par );
    }

}