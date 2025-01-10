CREATE TABLE games_usr (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    result ENUM('win', 'lose', 'draw') NOT NULL,
    points_change INT,
    played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
