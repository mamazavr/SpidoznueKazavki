CREATE TABLE IF NOT EXISTS shared_notes (
                                            id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                                            user_id INT UNSIGNED NOT NULL,
                                            note_id INT UNSIGNED NOT NULL,

                                            FOREIGN KEY (user_id) REFERENCES users(id),
                                            FOREIGN KEY (note_id) REFERENCES notes(id)
);