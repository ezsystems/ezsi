<?php

abstract class eZSIFileHandler
{
    abstract public function storeFile( $directory, $fileName, $fileContents );

    abstract public function removeFile( $director, $fileName );
}

?>
