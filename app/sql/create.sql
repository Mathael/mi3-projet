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

CREATE TABLE IF NOT EXISTS album
(
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) DEFAULT 'Unamed album' NOT NULL,
  `createDate` TIMESTAMP DEFAULT NOW() NOT NULL,
  `owner` int(11) NOT NULL,
  FOREIGN KEY (owner) REFERENCES user(id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX album_id_uindex ON album(id);