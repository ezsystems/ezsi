<?php
class eZSIFSFileHandler extends eZSIFileHandler
{
    private function eZSIFSFileHandler()
    {
    }

    public static function instance()
    {
        return new eZSIFSFileHandler;
    }

    public function storeFile( $directory, $fileName, $fileContents )
    {
        return eZFile::create( $fileName, 'var/' . $directory, $fileContents );
    }

    public function removeFile( $directory, $fileName )
    {
        eZDebug::writeNotice( 'Removing ' . $directory . '/' . $fileName, 'eZSIFSFileHandler::removeFile'  );
        return @unlink( $directory . '/' . $fileName );
    }
}
?>
