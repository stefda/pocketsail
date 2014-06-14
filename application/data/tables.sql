CREATE TABLE IF NOT EXISTS `poi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nearId` int(255) unsigned DEFAULT NULL,
  `countryId` int(255) unsigned DEFAULT NULL,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `border` polygon DEFAULT NULL,
  `attrs` text NOT NULL,
  `rank` float unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `other` (`cat`,`sub`,`border`(25)),
  KEY `lat` (`lat`,`lng`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `poi_new` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nearId` int(255) unsigned DEFAULT NULL,
  `countryId` int(255) unsigned DEFAULT NULL,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `border` polygon DEFAULT NULL,
  `attrs` text NOT NULL,
  `rank` float unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `other` (`cat`,`sub`,`border`(25)),
  KEY `lat` (`lat`,`lng`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `poi_edit` (
  `id` int(10) unsigned NOT NULL,
  `nearId` int(255) unsigned DEFAULT NULL,
  `countryId` int(255) unsigned DEFAULT NULL,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `border` polygon DEFAULT NULL,
  `attrs` text NOT NULL,
  `rank` float unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`userId`),
  KEY `other` (`cat`,`sub`,`border`(25)),
  KEY `lat` (`lat`,`lng`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `poi_archive` (
  `id` int(10) unsigned NOT NULL,
  `nearId` int(255) unsigned DEFAULT NULL,
  `countryId` int(255) unsigned DEFAULT NULL,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `border` polygon DEFAULT NULL,
  `attrs` text NOT NULL,
  `rank` float unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY (`id`),
  KEY `other` (`cat`,`sub`,`border`(25)),
  KEY `lat` (`lat`,`lng`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;