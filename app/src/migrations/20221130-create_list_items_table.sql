CREATE TABLE `list_items` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `is_complete` TINYINT DEFAULT 0
);