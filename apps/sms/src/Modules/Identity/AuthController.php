<?php

namespace App\Modules\Identity;

use App\Core\Database;
use App\Core\Security;
use App\Core\Auth;

class AuthController {
    public function showLogin() {
        if (Auth::check()) {
            header('Location: /dashboard');
            exit;
        }
        include __DIR__ . '/../../Views/login.php';
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) {
            die("CSRF token validation failed");
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1 AND deleted_at IS NULL");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && Security::verifyPassword($password, $user['password'])) {
            Auth::login($user);

            // Log the login action
            $this->logAction($user['id'], 'login', 'users', $user['id']);

            // Set school settings in session for global access
            $school = $db->query("SELECT * FROM schools LIMIT 1")->fetch();
            if ($school) {
                $_SESSION['school_name'] = $school['name'];
                $_SESSION['school_address'] = $school['address'];
            }

            header('Location: /dashboard');
            exit;
        }

        $error = "Invalid credentials";
        include __DIR__ . '/../../Views/login.php';
    }

    public function logout() {
        if (Auth::check()) {
            $this->logAction(Auth::id(), 'logout', 'users', Auth::id());
        }
        Auth::logout();
        header('Location: /login');
        exit;
    }

    public function dashboard() {
        Auth::requireLogin();

        if (Auth::isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->teacherDashboard();
        }
    }

    private function adminDashboard() {
        $db = Database::getInstance();
        $studentCount = $db->query("SELECT COUNT(*) FROM students WHERE deleted_at IS NULL")->fetchColumn();
        $teacherCount = $db->query("SELECT COUNT(*) FROM users WHERE role = 'teacher' AND deleted_at IS NULL")->fetchColumn();
        $classCount = $db->query("SELECT COUNT(*) FROM class_levels")->fetchColumn();

        include __DIR__ . '/../../Views/admin_dashboard.php';
    }

    private function teacherDashboard() {
        $db = Database::getInstance();
        $teacher_id = Auth::id();

        $stmt = $db->prepare("SELECT s.*, cl.name as class_name
                              FROM streams s
                              JOIN class_levels cl ON s.class_level_id = cl.id
                              JOIN user_stream_assignments usa ON s.id = usa.stream_id
                              WHERE usa.user_id = ?");
        $stmt->execute([$teacher_id]);
        $assignedStreams = $stmt->fetchAll();

        include __DIR__ . '/../../Views/teacher_dashboard.php';
    }

    private function logAction($user_id, $action, $entity_type, $entity_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO audit_logs (id, user_id, action, entity_type, entity_id, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->uuid(),
            $user_id,
            $action,
            $entity_type,
            $entity_id,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    private function uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
