<?php
// users_seed.php
// Usage: php users_seed.php

$dbHost = '127.0.0.1';
$dbName = 'vetcheck';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Ensure business roles exist
$roles = [
    2 => 'veto',
    3 => 'asv',
];
$stmt = $pdo->prepare('INSERT INTO roles (id, name) VALUES (:id, :name) ON DUPLICATE KEY UPDATE name = VALUES(name)');
foreach ($roles as $id => $name) {
    $stmt->execute([':id' => $id, ':name' => $name]);
}

$users = [
    // Two vetos who are also admins
    ['name' => 'Dr. Pascale Chabanne', 'email' => 'pascale.chabanne@vetcheck.test', 'role_id' => 2, 'is_admin' => 1, 'password' => 'password123'],
    ['name' => 'Dr. Mathilde Molière', 'email' => 'mathilde.moliere@vetcheck.test', 'role_id' => 2, 'is_admin' => 1, 'password' => 'password123'],
    // One veto (not admin)
    ['name' => 'Dr. Mélanie Pohu', 'email' => 'melanie.pohu@vetcheck.test', 'role_id' => 2, 'is_admin' => 0, 'password' => 'password123'],
    // Four ASV
    ['name' => 'ASV Sophie', 'email' => 'sophie.asv@vetcheck.test', 'role_id' => 3, 'is_admin' => 0, 'password' => 'password123'],
    ['name' => 'ASV Charlène', 'email' => 'charlene.asv@vetcheck.test', 'role_id' => 3, 'is_admin' => 0, 'password' => 'password123'],
    ['name' => 'ASV Fabien', 'email' => 'fabien.asv@vetcheck.test', 'role_id' => 3, 'is_admin' => 0, 'password' => 'password123'],
    ['name' => 'ASV Laurie', 'email' => 'laurie.asv@vetcheck.test', 'role_id' => 3, 'is_admin' => 0, 'password' => 'password123'],
];

$insert = $pdo->prepare('INSERT INTO users (name, email, password_hash, role_id, is_admin, created_at) VALUES (:name, :email, :password_hash, :role_id, :is_admin, NOW()) ON DUPLICATE KEY UPDATE name = VALUES(name), password_hash = VALUES(password_hash), role_id = VALUES(role_id), is_admin = VALUES(is_admin)');

foreach ($users as $u) {
    $passwordHash = password_hash($u['password'], PASSWORD_DEFAULT);
    $insert->execute([
        ':name' => $u['name'],
        ':email' => $u['email'],
        ':password_hash' => $passwordHash,
        ':role_id' => $u['role_id'],
        ':is_admin' => $u['is_admin'],
    ]);
    echo "Upserted user: {$u['email']} (role_id={$u['role_id']}, is_admin={$u['is_admin']})" . PHP_EOL;
}

echo "Users seed completed." . PHP_EOL;

?>
