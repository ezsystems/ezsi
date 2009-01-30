<?php

class eZSISSIBlockHandler extends eZSIBlockHandler
{
    public function generateMarkup()
    {
        $markup = '<!--#include file="' . $this->Src . '" -->';
        eZDebug::writeNotice( $markup, 'eZSISSIBlockHandler::generateMarkup' );
        return $markup;
    }
}
?>
