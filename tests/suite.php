<?php
/**
 * File containing the eZSITestSuite class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZSITestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZSI Test Suite" );

        $this->addTestSuite( 'eZSIBlockFunctionTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>