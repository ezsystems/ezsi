<?php
abstract class eZSIBlockHandler
{
    public function setTTL( $ttlString )
    {
        $this->TTL = $ttlString;
    }

    public function setKey( $keyString )
    {
        $this->Key = $keyString;
    }

    public function setSrc( $filePath )
    {
        $this->Src = $filePath;
    }

    public function validateKey()
    {
        if( !$this->Key )
        {
            return false;
        }

        return true;
    }

    public function TTLInSeconds()
    {
        $ttlInfos = $this->parseTTL();

        switch( $ttlInfos['ttl_unit'] )
        {
            case 'h' : $ttlInSeconds = $ttlInfos['ttl_value'] * 3600      ; break;
            case 'm' : $ttlInSeconds = $ttlInfos['ttl_value'] * 60        ; break;
            case 's' : $ttlInSeconds = $ttlInfos['ttl_value']             ; break;
            default  : $ttlInSeconds = $ttlInfos['ttl_value'] * 3600 * 24 ; break;
        }

        return $ttlInSeconds;
    }

    public function fileIsExpired( $mtime )
    {
        $TTLValue = $this->TTLInSeconds();
        return ( time() - $mtime ) >= $TTLValue;
    }

    public function parseTTL()
    {
        $ttlUnit  = substr( $this->TTL, -1);
        $ttlValue = (int)$this->TTL;

        return array( 'ttl_unit'  => $ttlUnit,
                      'ttl_value' => $ttlValue );
    }

    public function validateTTL()
    {
        // available time units are :
        // h : hours
        // m : minutes
        // s : seconds
        // d : days
        // units can not be combined

        $possibleUnits = array( 'h', 'm', 's', 'd' );

        $ttlInfos = $this->parseTTL();

        if( !in_array( $ttlInfos['ttl_unit'], $possibleUnits ) )
        {
            return false;
        }

        if( !$ttlInfos['ttl_value'] )
        {
            return false;
        }

        return true;
    }

    abstract public function generateMarkup();

    public $TTL = '';
    public $Key = '';
    public $Src = '';
}
?>
