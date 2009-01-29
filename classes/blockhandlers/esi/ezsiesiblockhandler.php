<?php

class eZSIESIBlockHandler extends eZSIBlockHandler
{
    public function generateMarkup()
    {
        $ttlInfos = $this->parseTTL();

        $markup = '<esi:include src="' . $this->Src . '" ttl="' . $ttlInfos['ttl_value'] . $ttlInfos['ttl_unit'] . '" onerror="continue"/>';
        eZDebug::writeNotice( $markup, 'eZSIESIBlockHandler::generateMarkup' );
        return $markup;
    }
}
?>
