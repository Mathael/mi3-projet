CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `url` varchar(1024) DEFAULT NULL,
  `category` varchar(64) DEFAULT NULL,
  `comment` varchar(1024) DEFAULT NULL
) DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX image_id_uindex ON image(id);

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `role` varchar(10) DEFAULT 'USER' NOT NULL
) DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX user_id_uindex ON user(id);

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT 'Unamed album',
  `createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `album_id_uindex` (`id`),
  KEY `album_user_id_fk` (`owner`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `album_images` (
  `album` int(11) NOT NULL,
  `image` int(11) NOT NULL,
  `image_index` int(11) NOT NULL,
  KEY `album_images_album_id_fk` (`album`),
  KEY `album_images_image_id_fk` (`image`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `image_stars` (
  `userId` int(11) NOT NULL,
  `imageId` int(11) NOT NULL,
  `stars` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`userId`,`imageId`),
  KEY `image` (`imageId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;