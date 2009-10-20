CREATE TABLE ezsi_files (
    filepath VARCHAR2(4000) NOT NULL,
    namehash CHAR(32) PRIMARY KEY,
    mtime INTEGER NOT NULL DEFAULT 0,
    urlalias VARCHAR2(4000) NOT NULL,
    siteaccess VARCHAR2(100) NOT NULL,
    ttl INTEGER NOT NULL,
    blockkeys VARCHAR2(4000) NOT NULL);
