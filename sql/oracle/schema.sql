CREATE TABLE ezsi_files (
    filepath VARCHAR2(4000) NOT NULL,
    namehash CHAR(32) PRIMARY KEY,
    mtime INTEGER(11) NOT NULL DEFAULT 0,
    urlalias VARCHAR2(4000) NOT NULL,
    siteaccess VARCHAR2(100) NOT NULL,
    ttl INTEGER(10) NOT NULL,
    blockkeys VARCHAR2(4000) NOT NULL);
