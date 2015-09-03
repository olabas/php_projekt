CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `read` int(1) unsigned NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `recipient_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_messages_1` (`sender_id`),
  KEY `FK_messages_2` (`recipient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

ALTER TABLE `messages`
  ADD (
    CONSTRAINT `FK_messages_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
    CONSTRAINT `FK_messages_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`)
  );