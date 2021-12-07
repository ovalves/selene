CREATE TABLE `session` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `session_id` varchar(2000) NOT NULL,
    `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id`),
    KEY `time_created` (`time_created`),
    KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1

CREATE TABLE `user` (
    `user_id` int(11)  unsigned NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `fullname` varchar(2000) NOT NULL,
    `password` varchar(2000) NOT NULL,
    `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`user_id`),
    UNIQUE KEY unique_email (`email`),
    KEY `time_created` (`time_created`),
    KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1

