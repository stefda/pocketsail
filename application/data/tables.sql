
CREATE TABLE IF NOT EXISTS `poi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nearId` int(255) unsigned DEFAULT NULL,
  `countryId` int(255) unsigned DEFAULT NULL,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `features` text NOT NULL,
  `latLng` point NOT NULL,
  `boundary` polygon DEFAULT NULL,
  `rank` float unsigned NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  SPATIAL KEY `latLng` (`latLng`),
  KEY `userId` (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111;

CREATE TABLE IF NOT EXISTS `poi_new` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nearId` int(255) unsigned DEFAULT NULL,
  `countryId` int(255) unsigned DEFAULT NULL,
  `userId` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `features` text NOT NULL,
  `latLng` point NOT NULL,
  `boundary` polygon DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `other` (`cat`,`sub`,`latLng`(25),`boundary`(25)),
  SPATIAL KEY `latLng` (`latLng`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `label_zoom` (
  `id` int(10) unsigned NOT NULL,
  `zoom` tinyint(3) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `lat` float(9,6) NOT NULL,
  `lng` float(9,6) NOT NULL,
  `desc` varchar(50) NOT NULL
  PRIMARY KEY (`id`,`zoom`),
  KEY `lat` (`lat`),
  KEY `lng` (`lng`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `label_feature` (
  `id` int(10) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `cat` varchar(20) NOT NULL,
  `sub` varchar(20) NOT NULL,
  `lat` float(9,6) NOT NULL,
  `lng` float(9,6) NOT NULL,
  `rank` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lat` (`lat`),
  KEY `lng` (`lng`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
