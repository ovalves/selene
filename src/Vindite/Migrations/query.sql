CREATE TABLE `session` (
    `id` int(11) NOT NULL,
    `session_id` varchar(20000) NOT NULL,
    `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id`),
    KEY `time_created` (`time_created`),
    KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
