CREATE TABLE IF NOT EXISTS user_list (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) DEFAULT NULL,
  password_hash VARCHAR(255) DEFAULT NULL,
  UNIQUE KEY username_unique (username),
  UNIQUE KEY email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS module_list (
  id INT AUTO_INCREMENT PRIMARY KEY,
  modulename VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS question (
  id INT AUTO_INCREMENT PRIMARY KEY,
  questiontext TEXT NOT NULL,
  questiondate DATE DEFAULT (CURRENT_DATE()),
  authorid INT DEFAULT NULL,
  categoryid INT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  username VARCHAR(255) DEFAULT NULL,
  moduleid INT DEFAULT NULL,
  FOREIGN KEY (authorid) REFERENCES user_list(id) ON DELETE SET NULL,
  FOREIGN KEY (moduleid) REFERENCES module_list(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS message (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO user_list (username, email, password_hash) VALUES
('Admin', 'admin@example.com', NULL)
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO module_list (modulename) VALUES
('General'),
('Programming')
ON DUPLICATE KEY UPDATE modulename = VALUES(modulename);

INSERT INTO question (questiontext, questiondate, authorid, categoryid, image, username, moduleid) VALUES
('Why do programmers prefer dark mode? Because light attracts bugs.', CURRENT_DATE(), 1, 1, 'pic2.png', 'Admin', 1),
('I told my computer I needed a break, and it said: No problem - it started updating.', CURRENT_DATE(), 1, 1, 'pic3.jpg', 'Admin', 1);
