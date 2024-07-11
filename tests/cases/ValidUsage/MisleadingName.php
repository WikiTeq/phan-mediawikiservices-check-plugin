<?php

namespace FooBar;

use MediaWiki\MediaWikiServices;

// Not the real SpecialPage class since we are in a namespace
// @phan-suppress-next-line PhanUndeclaredExtendedClass
class MySpecial extends SpecialPage {

    public function test1() {
        $service = MediaWikiServices::getInstance()->getService( 'MyService' );
        $service->run( $par );
    }

}