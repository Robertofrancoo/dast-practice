USE testdb;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    password VARCHAR(100)
);

INSERT INTO users (name, password) VALUES 
    ('admin', 'supersecret123'),
    ('user1', 'password123'),
    ('user2', 'qwerty456');
