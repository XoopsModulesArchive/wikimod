#
# Table structure for table `wikimod`
#

CREATE TABLE wikimod (
    id           INT(10)      NOT NULL AUTO_INCREMENT,
    keyword      VARCHAR(255) NOT NULL DEFAULT '',
    title        VARCHAR(255) NOT NULL DEFAULT '',
    body         TEXT         NOT NULL DEFAULT '',
    lastmodified DATETIME     NOT NULL DEFAULT '0000-00-00 00:00:00',
    u_id         INT(10)      NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
)
    ENGINE = ISAM;
