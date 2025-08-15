CREATE TABLE `todos` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `completed` boolean NOT NULL DEFAULT FALSE,
  `created` timestamp(3) NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `user_id` integer NOT NULL
);

CREATE TABLE `users` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` timestamp(3) NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `options` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `created` timestamp(3) NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `autoload` boolean NOT NULL DEFAULT false
);

CREATE TABLE `pages` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `uri` varchar(191) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(191) NOT NULL,
  `private` boolean NOT NULL DEFAULT false,
  `content` text DEFAULT NULL
);

ALTER TABLE `todos` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
