<?php
//
// Definition of siblocksupdate cronjob
//
// Created on: <28-Apr-2008 10:06:19 jr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'extension/ezsi/classes/ezsiblockfunction.php' );
include_once( 'kernel/classes/ezcontentcachemanager.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$ini                     = eZINI::instance( 'site.ini' );
$avalaibleSiteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
$viewCaching             = $ini->variable( 'ContentSettings', 'ViewCaching' );
$indexPage               = $ini->variable( 'SiteSettings', 'IndexPage' );

$ini                     = eZINI::instance( 'ezsi.ini' );
$SIBlockHandler          = $ini->variable( 'SIBlockSettings', 'BlockHandler' );
$forceRegenerationString = $ini->variable( 'TemplateFunctionSettings', 'ForceRegenerationString' );
$forceRegenerationValue  = $ini->variable( 'TemplateFunctionSettings', 'ForceRegenerationValue' );
$hostMatchMapItems       = $ini->variable( 'HostSettings'            , 'HostMatchMapItems' );

$hostMapList = array();
foreach( $hostMatchMapItems as $mapItems )
{
    list( $hostName, $siteAccessName ) = explode( ';', $mapItems );
    $hostMapList[strtolower( $siteAccessName )] = $hostName;
}

$cli->output( 'Finding expired blocks' );

$db   = eZDB::instance();
$sql  = 'SELECT * FROM ezsi_files WHERE (' . time() . ' - mtime) >= ttl ORDER BY TTL DESC';
$rows = $db->arrayQuery( $sql );

foreach( $rows as $expiredBlock )
{
    if( in_array( $expiredBlock['siteaccess'], $avalaibleSiteAccessList ) )
    {
        $pageURL = 'http://' . $hostMapList[ strtolower( $expiredBlock['siteaccess'] )] . '/';

        $pageURL .= $expiredBlock['urlalias'];

        // flushing content cache for this page if needed
        if( $viewCaching == 'enabled' )
        {
            $urlAlias = $expiredBlock['urlalias'];

            // index page
            if( strlen( $urlAlias ) == 1 )
            {
                // missing trailing slash
                if( substr( $indexPage, -1, 1 ) != '/' )
                {
                    $indexPage = $indexPage . '/';
                }

                $urlAlias = $indexPage;
            }

            $destinationURLArray = explode( '/', substr( $urlAlias, 0, -1 ) );
            $nodeID = array_slice( $destinationURLArray, -1, 1 );
            $nodeID = $nodeID[0];

            // 0 => false
            // 1 => true or top level node
            // I do not want that
            if( is_numeric( $nodeID ) && $nodeID > 1 )
            {
                $node     = eZContentObjectTreeNode::fetch( $nodeID );
                $object   = $node->object();
                $objectID = $object->attribute( 'id' );

                // clearing view cache for this node
                eZContentCacheManager::clearContentCache( $objectID );
                eZDebug::writeNotice( 'Clearing ViewCache for object ' . $objectID, 'eZSIBlockFunction::process' );
            }
        }

        $cli->output( 'Calling ' . $pageURL . ' : ', false );

        // regenerating si blocks by calling the page
        // storing the results is useless
        if(  !@file_get_contents( $pageURL, FILE_BINARY ) )
        {
            $cli->output( 'FAILED' );
            removeFileIfNeeded( $expiredBlock, $db);
            //eZDebug::writeError( 'Update of SI blocks for page ' . $pageURL . ' failed', 'SI Block Update Cronjob' );
        }
        else
        {
            $sql  = "SELECT mtime FROM ezsi_files WHERE namehash = '" . $expiredBlock['namehash'] . "'";
            $rows = $db->arrayQuery( $sql );
            if( $rows[0]['mtime'] > $expiredBlock['mtime'] )
            {
                $cli->output( 'SUCCESS' );
            }
            else
            {
                $cli->output( 'CHECKING IF REMOVAL IS NEEDED' );
                removeFileIfNeeded( $expiredBlock, $db);
                //eZDebug::writeError( 'Update of SI blocks for page ' . $pageURL . ' failed', 'SI Block Update Cronjob' );
            }
        }
    }
    else
    {
        eZDebug::writeError( 'Use of an undefined siteaccess : ' . $expiredBlock['siteaccess'], 'SI Block Update Cronjob' );
    }
}

function removeFileIfNeeded( $expiredBlock, $db )
{
    $ini                    = & eZINI::instance( 'ezsi.ini' );
    $deleteSIBlockOnFailure = $ini->variable( 'CronjobSettings', 'DeleteSIBlockOnFailure' );

    $fileHandler = eZSIBlockFunction::loadSIFileHandler();

    if( $deleteSIBlockOnFailure == 'enabled' )
    {
        $sql = "DELETE FROM ezsi_files WHERE namehash = '" . $expiredBlock['namehash'] . "'";
        if( $db->query( $sql ) )
        {
            $pathInfo = pathinfo( $expiredBlock['filepath'] );
            if( !$fileHandler->removeFile( $pathInfo['dirname'], $pathInfo['basename'] ) )
            {
                eZDebug::writeError( 'Removing of SI block ' . $expiredBlock['filepath'] . ' failed' );
            }
        }
        else
        {
            eZDebug::writeError( 'Unable to remove the SI block row ' . $expiredBlock['namehash'] . ' from the database' );
        }
    }
}
?>
