<?php
function query($pdo, $sql, $parameters = []) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parameters);
    return $stmt;
}

function columnExists($pdo, $table, $column) {
    $sql = 'SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table AND COLUMN_NAME = :column';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':table' => $table, ':column' => $column]);
    $row = $stmt->fetch();
    return ($row && $row[0] > 0);
}

function moduleColumns($pdo) {
    $columns = [];
    if (columnExists($pdo, 'question', 'moduleid')) {
        $columns[] = 'moduleid';
    }
    if (columnExists($pdo, 'question', 'categoryid')) {
        $columns[] = 'categoryid';
    }
    return $columns;
}

function ensureUsernameColumn($pdo) {
    if (!columnExists($pdo, 'question', 'username')) {
        $pdo->exec('ALTER TABLE question ADD COLUMN username VARCHAR(255) DEFAULT NULL');
    }
}
function moduleTableIdColumn($pdo) {
    if (columnExists($pdo, 'module_list', 'modulid')) {
        return 'modulid';
    }
    if (columnExists($pdo, 'module_list', 'moduleid')) {
        return 'moduleid';
    }
    if (columnExists($pdo, 'module_list', 'id')) {
        return 'id';
    }
    return null;
}
function moduleTableNameColumn($pdo) {
    if (columnExists($pdo, 'module_list', 'modulename')) {
        return 'modulename';
    }
    if (columnExists($pdo, 'module_list', 'module_name')) {
        return 'module_name';
    }
    return null;
}

function updateQuestion($pdo, $questionId, $questiontext, $userid = null, $moduleid = null, $image = null) {
    $fields = [];
    $parameters = [':id' => $questionId];

    if ($questiontext !== null) {
        $fields[] = 'questiontext = :questiontext';
        $parameters[':questiontext'] = $questiontext;
    }
    if ($userid !== null) {
        $fields[] = 'userid = :userid';
        $parameters[':userid'] = $userid;
    }
    if ($moduleid !== null) {
        $modCols = moduleColumns($pdo);
        foreach ($modCols as $col) {
            $fields[] = $col . ' = :moduleid';
        }
        if (!empty($modCols)) {
            $parameters[':moduleid'] = $moduleid;
        }
    }
    if ($image !== null && columnExists($pdo, 'question', 'image')) {
        $fields[] = 'image = :image';
        $parameters[':image'] = $image;
    }

    // Always update the question date to now when editing
    $fields[] = 'questiondate = CURDATE()';

    if (count($fields) === 0) {
        return;
    }

    $sql = 'UPDATE question SET ' . implode(', ', $fields) . ' WHERE id = :id';
    query($pdo, $sql, $parameters);
}

function deleteQuestion($pdo, $id) {
    query($pdo, 'DELETE FROM question WHERE id = :id', [':id' => $id]);
}

function insertQuestion($pdo, $questiontext, $userid, $moduleid, $image = null, $username = null) {
    ensureUsernameColumn($pdo);

    $hasImage = columnExists($pdo, 'question', 'image');
    $columns = ['questiontext', 'userid', 'questiondate'];
    $values  = [':questiontext', ':userid', 'CURDATE()'];
    $params  = [':questiontext' => $questiontext, ':userid' => $userid];

    $modCols = moduleColumns($pdo);
    if (!empty($modCols)) {
        foreach ($modCols as $col) {
            $columns[] = $col;
            $values[]  = ':moduleid';
        }
        $params[':moduleid'] = $moduleid;
    }

    if ($hasImage) {
        $columns[] = 'image';
        $values[]  = ':image';
        $params[':image'] = $image;
    }
    if ($username !== null) {
        $columns[] = 'username';
        $values[]  = ':username';
        $params[':username'] = $username;
    }

    $sql = 'INSERT INTO question (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';
    query($pdo, $sql, $params);
}

function getQuestion($pdo, $id) {
    $query = query($pdo, 'SELECT * FROM question WHERE id = :id', [':id' => $id]);
    return $query->fetch();
}

function totalQuestions($pdo, $userId = null) {
    if ($userId !== null) {
        $stmt = query($pdo, 'SELECT COUNT(*) FROM question WHERE userid = :userid', [':userid' => $userId]);
    } else {
        $stmt = $pdo->query('SELECT COUNT(*) FROM question');
    }
    $row = $stmt->fetch();
    return $row[0];
}

function allAuthors($pdo) {
    ensureUserListTable($pdo);
    $authors = query($pdo, 'SELECT * FROM users ORDER BY username');
    return $authors->fetchAll();
}

function allCategories($pdo) {
    ensureModuleTable($pdo);
    $idCol = moduleTableIdColumn($pdo);
    $nameCol = moduleTableNameColumn($pdo);
    $modules = query($pdo, 'SELECT ' . $idCol . ' AS id, ' . $nameCol . ' AS modulename FROM module_list ORDER BY ' . $nameCol);
    return $modules->fetchAll();
}

function allQuestions($pdo, $usernameFilter = null, $search = null) {
    ensureUsernameColumn($pdo);
    ensureModuleTable($pdo);
    $includeImage = columnExists($pdo, 'question', 'image');
    $moduleCols = moduleColumns($pdo);
    $moduleJoinColumn = $moduleCols[0] ?? null;
    $moduleIdCol = moduleTableIdColumn($pdo);

    $moduleNameCol = moduleTableNameColumn($pdo) ?: 'modulename';

    $select = 'SELECT question.id, question.questiontext, question.questiondate, question.username, users.username AS authorname';
    if ($moduleJoinColumn !== null && $moduleNameCol !== null) {
        $select .= ', module_list.' . $moduleNameCol . ' AS modulename';
    }
    if ($includeImage) {
        $select .= ', COALESCE(question.image, \'pic3.jpg\') AS image';
    }

    $sql = $select . ' FROM question LEFT JOIN users ON question.userid = users.id';
    if ($moduleJoinColumn !== null && $moduleIdCol !== null) {
        $sql .= ' LEFT JOIN module_list ON module_list.' . $moduleIdCol . ' = question.' . $moduleJoinColumn;
    }

    $params = [];
    $clauses = [];
    if ($usernameFilter !== null && $usernameFilter !== '') {
        $clauses[] = 'question.username = :username';
        $params[':username'] = $usernameFilter;
    }
    if ($search !== null && $search !== '') {
        $clauses[] = 'question.questiontext LIKE :search';
        $params[':search'] = '%' . $search . '%';
    }
    if (!empty($clauses)) {
        $sql .= ' WHERE ' . implode(' AND ', $clauses);
    }

    $questions = query($pdo, $sql, $params);
    return $questions->fetchAll();
}

function findAuthorIdByName($pdo, $name) {
    ensureUserListTable($pdo);
    $query = query($pdo, 'SELECT id FROM users WHERE username = :name LIMIT 1', [':name' => $name]);
    $row = $query->fetch();
    return $row['id'] ?? null;
}

function createAuthor($pdo, $name) {
    ensureUserListTable($pdo);
    $safeName = trim($name);
    if ($safeName === '') {
        return null;
    }
    // auto-generate a predictable email to satisfy NOT NULL/UNIQUE constraints
    $safeEmail = preg_replace('/[^a-z0-9]+/i', '.', strtolower($safeName)) . '@user.local';
    query(
        $pdo,
        'INSERT INTO users (username, email) VALUES (:name, :email)
         ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)',
        [':name' => $safeName, ':email' => $safeEmail]
    );
    return $pdo->lastInsertId();
}

function findOrCreateAuthorByName($pdo, $name) {
    $existing = findAuthorIdByName($pdo, $name);
    if ($existing !== null) {
        return $existing;
    }
    return createAuthor($pdo, $name);
}

function findUserByName($pdo, $name) {
    ensureUserListTable($pdo);
    $stmt = query($pdo, 'SELECT * FROM users WHERE username = :name LIMIT 1', [':name' => $name]);
    return $stmt->fetch();
}

function findUserByEmail($pdo, $email) {
    ensureUserListTable($pdo);
    $stmt = query($pdo, 'SELECT * FROM users WHERE email = :email LIMIT 1', [':email' => $email]);
    return $stmt->fetch();
}

function verifyUserCredentials($pdo, $name, $password) {
    $user = findUserByName($pdo, $name);
    if (!$user || empty($user['password_hash'])) {
        return null;
    }
    return password_verify($password, $user['password_hash']) ? $user : null;
}

function allUsernames($pdo, $extraUsername = null) {
    ensureUsernameColumn($pdo);
    ensureUserListTable($pdo);

    $questionUsers = query(
        $pdo,
        'SELECT username, COUNT(*) AS total FROM question WHERE username IS NOT NULL AND username <> "" AND username <> "Admin" GROUP BY username'
    )->fetchAll();

    $explicitUsers = query(
        $pdo,
        'SELECT id, username, email FROM users WHERE username <> "Admin" ORDER BY username'
    )->fetchAll();

    $map = [];
    foreach ($questionUsers as $row) {
        $u = $row['username'];
        $map[strtolower($u)] = ['id' => null, 'username' => $u, 'total' => (int)$row['total'], 'email' => null];
    }
    foreach ($explicitUsers as $row) {
        $u = $row['username'];
        $key = strtolower($u);
        if (!isset($map[$key])) {
            $map[$key] = ['id' => $row['id'], 'username' => $u, 'total' => 0, 'email' => $row['email']];
        } else {
            $map[$key]['id'] = $row['id'];
            $map[$key]['email'] = $row['email'];
        }
    }

    $extra = trim((string)$extraUsername);
    if ($extra !== '' && strcasecmp($extra, 'Admin') !== 0) {
        $key = strtolower($extra);
        if (!isset($map[$key])) {
            $map[$key] = ['id' => null, 'username' => $extra, 'total' => 0, 'email' => null];
        }
    }

    usort($map, function ($a, $b) {
        return strcasecmp($a['username'], $b['username']);
    });

    return array_values($map);
}

function deleteUserAndQuestions($pdo, $username) {
    ensureUsernameColumn($pdo);
    ensureUserListTable($pdo);
    if (strcasecmp($username, 'Admin') === 0) {
        return;
    }
    query($pdo, 'DELETE FROM question WHERE username = :username', [':username' => $username]);
    query($pdo, 'DELETE FROM users WHERE username = :username', [':username' => $username]);
}

function renameUser($pdo, $oldUsername, $newUsername) {
    ensureUsernameColumn($pdo);
    if (strcasecmp($oldUsername, 'Admin') === 0 || strcasecmp($newUsername, 'Admin') === 0) {
        return;
    }
    query(
        $pdo,
        'UPDATE question SET username = :newUsername WHERE username = :oldUsername',
        [':newUsername' => $newUsername, ':oldUsername' => $oldUsername]
    );
}

function ensureUserListTable($pdo) {
    // assumes table `users` already exists with id, username, email, password_hash
    if (!columnExists($pdo, 'users', 'password_hash')) {
        $pdo->exec('ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) DEFAULT NULL');
    }
}

function ensureUserListEmailColumn($pdo) {
    if (!columnExists($pdo, 'users', 'email')) {
        $pdo->exec('ALTER TABLE users ADD COLUMN email VARCHAR(255) DEFAULT NULL');
    }
}

function ensureUserListPasswordColumn($pdo) {
    if (!columnExists($pdo, 'users', 'password_hash')) {
        $pdo->exec('ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) DEFAULT NULL');
    }
}

function addUserName($pdo, $username, $email = null, $password = null) {
    ensureUserListTable($pdo);
    $name = trim($username);
    $mail = trim((string)$email);
    $pass = (string)$password;

    if (
        strcasecmp($name, 'Admin') === 0 ||
        $name === '' ||
        $mail === '' ||
        !filter_var($mail, FILTER_VALIDATE_EMAIL)
    ) {
        return false;
    }
    // avoid clashing with an existing email on another account
    $existingEmail = findUserByEmail($pdo, $mail);
    if ($existingEmail && strcasecmp($existingEmail['username'], $name) !== 0) {
        return false;
    }

    // default password to "1" if nothing provided
    if ($pass === '') {
        $pass = '1';
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    query(
        $pdo,
        'INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :hash)
         ON DUPLICATE KEY UPDATE email = VALUES(email), password_hash = VALUES(password_hash)',
        [':username' => $name, ':email' => $mail, ':hash' => $hash]
    );
    return true;
}

function ensureMessageTable($pdo) {
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS message (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            content TEXT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );
}

function addMessage($pdo, $username, $email, $content) {
    ensureMessageTable($pdo);
    query(
        $pdo,
        'INSERT INTO message (username, email, content) VALUES (:username, :email, :content)',
        [
            ':username' => $username ?: null,
            ':email' => $email ?: null,
            ':content' => $content
        ]
    );
}

function allMessages($pdo) {
    ensureMessageTable($pdo);
    $stmt = query($pdo, 'SELECT * FROM message ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function deleteMessage($pdo, $id) {
    ensureMessageTable($pdo);
    query($pdo, 'DELETE FROM message WHERE id = :id', [':id' => $id]);
}

function ensureModuleTable($pdo) {
    // assume module_list with modulid, modulename already exists
}

function allModules($pdo) {
    ensureModuleTable($pdo);
    $idCol = moduleTableIdColumn($pdo);
    $nameCol = moduleTableNameColumn($pdo);
    $selectId = $idCol;
    $rows = query(
        $pdo,
        'SELECT ' . $selectId . ' AS id, ' . $nameCol . ' AS modulename FROM module_list ORDER BY ' . $nameCol
    );
    return $rows->fetchAll();
}

function addModuleName($pdo, $modulename) {
    ensureModuleTable($pdo);
    $name = trim($modulename);
    if ($name === '') {
        return;
    }
    query($pdo, 'INSERT IGNORE INTO module_list (modulename) VALUES (:modulename)', [':modulename' => $name]);
}

function deleteModule($pdo, $id) {
    ensureModuleTable($pdo);
    $idCol = moduleTableIdColumn($pdo);
    query($pdo, 'DELETE FROM module_list WHERE ' . $idCol . ' = :id', [':id' => $id]);
}

function renameModule($pdo, $id, $newName) {
    ensureModuleTable($pdo);
    $name = trim($newName);
    if ($id <= 0 || $name === '') {
        return;
    }
    query(
        $pdo,
        'UPDATE module_list SET modulename = :name WHERE ' . moduleTableIdColumn($pdo) . ' = :id',
        [':name' => $name, ':id' => $id]
    );
}
?>
