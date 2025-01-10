ALTER TABLE `games`
ADD CONSTRAINT `fk_user_id`
FOREIGN KEY (`user_id`) REFERENCES `users_games`(`id`)
ON DELETE CASCADE;
