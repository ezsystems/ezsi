<?php
/*

[TemplateFunctionSettings]
# It is possible to force the regeneration
# of a block by passing <ForceRegenerationString>=<ForceRegenerationValue>
# in the URL.
# For example :
# http://www.site.com/path/to/page?<ForceRegenerationString>=<ForceRegenerationValue>
# will force eZ Publish to regenerate the SI blocks defined in this page.
ForceRegenerationString=force_siblock_generation
ForceRegenerationValue=true

[SIBlockSettings]
# two options are possible here :
# - ESI
# - SSI
BlockHandler=SSI

# This is useful when ESI blocks
# and HTTPD are not on the same server.
# The extension will automatically
# generate the string 'si-blocks/<cachefilename>.htm'
# but this is not sufficient for remote file systems.
# We need informations on how to acess the file.
# This may be a hostname to access the file directly
# like http://siblocks.mysite.com/si-blocks/<cachefilename>.htm
#
# If you plan to use a local storage then this directive
# MUST be empty : BlockFilePathPrependString=
# BlockFilePathPrependString=http://siblocks.mysite.com/
BlockFilePathPrependString=http://plop.com/path/to/esi/

[SIFilesSettings]
# two options are possible here :
# - FS ( local file system )
# - FTP
FileHandler=FS

# Connexion settings for
# the FTP file handler
# only usefull if you set [SIFilesSettings]/FileHandler=FTP
[FTPSettings]
Host=customers.executus
Port=21
Login=root
Password=publish
Timeout=10
DestinationFolder=path/to/esi/

[DevelopmentSettings]
# When set to disable the
# extension will not insert
# the SI tags in the HTML page
ActivateSIMarkup=enabled

[CronjobSettings]
DeleteSIBlockOnFailure=enabled

[HostSettings]
HostMatchMapItems[]
HostMatchMapItems[]=localhost:8080;plain_site

*/
?>
