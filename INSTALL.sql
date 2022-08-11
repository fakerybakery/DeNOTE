SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-08:00";

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `note` longtext NOT NULL,
  `noteid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `note`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
