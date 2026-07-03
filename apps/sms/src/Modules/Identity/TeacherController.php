<?php

namespace App\Modules\Identity;

use App\Core\Database;
use App\Core\Security;
use App\Core\Auth;

class TeacherController {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index() {
        $db = Database::getInstance();
        $teachers = $db->query("SELECT * FROM users WHERE role = 'teacher' AND deleted_at IS NULL ORDER BY name ASC")->fetchAll();
        include __DIR__ . '/../../Views/teachers/index.php';
    }

    public function create() {
        include __DIR__ . '/../../Views/teachers/create.php';
    }

    public function store() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        $db = Database::getInstance();
        $id = Security::uuid();
        $hashed_password = Security::hashPassword($password);

        $stmt = $db->prepare("INSERT INTO users (id, name, email, username, password, role) VALUES (?, ?, ?, ?, ?, 'teacher')");
        $stmt->execute([$id, $name, $email, $username, $hashed_password]);

        Security::log('teacher_create', 'users', $id, null, ['name' => $name, 'email' => $email, 'username' => $username]);

        header('Location: /teachers');
        exit;
    }

    public function edit() {
        $id = $_GET['id'] ?? '';
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $teacher = $stmt->fetch();

        include __DIR__ . '/../../Views/teachers/edit.php';
    }

    public function update() {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        $db = Database::getInstance();

        // Fetch old values for audit log
        $stmt = $db->prepare("SELECT name, email FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $old_values = $stmt->fetch();

        $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $id]);

        Security::log('teacher_update', 'users', $id, $old_values, ['name' => $name, 'email' => $email]);

        header('Location: /teachers');
        exit;
    }

    public function delete() {
        $id = $_POST['id'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET deleted_at = NOW(), is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);

        Security::log('teacher_delete', 'users', $id);

        header('Location: /teachers');
        exit;
    }
}
