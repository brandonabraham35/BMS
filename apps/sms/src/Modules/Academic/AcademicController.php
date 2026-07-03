<?php

namespace App\Modules\Academic;

use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;

class AcademicController {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function sections() {
        $db = Database::getInstance();
        $sections = $db->query("SELECT * FROM sections ORDER BY name")->fetchAll();
        include __DIR__ . '/../../Views/academic/sections.php';
    }

    public function classLevels() {
        $db = Database::getInstance();
        $class_levels = $db->query("SELECT cl.*, s.name as section_name FROM class_levels cl JOIN sections s ON cl.section_id = s.id ORDER BY s.name, cl.name")->fetchAll();
        include __DIR__ . '/../../Views/academic/class_levels.php';
    }

    public function streams() {
        $db = Database::getInstance();
        $streams = $db->query("SELECT s.*, cl.name as class_name FROM streams s JOIN class_levels cl ON s.class_level_id = cl.id ORDER BY cl.name, s.name ASC")->fetchAll();

        $teachers = $db->query("SELECT id, name FROM users WHERE role = 'teacher' AND deleted_at IS NULL")->fetchAll();

        include __DIR__ . '/../../Views/academic/streams.php';
    }

    public function subjects() {
        $db = Database::getInstance();
        $subjects = $db->query("SELECT s.*, sec.name as section_name FROM subjects s JOIN sections sec ON s.section_id = sec.id ORDER BY sec.name, s.name ASC")->fetchAll();
        include __DIR__ . '/../../Views/academic/subjects.php';
    }

    public function assignTeacher() {
        $user_id = $_POST['user_id'] ?? '';
        $stream_id = $_POST['stream_id'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        $db = Database::getInstance();

        // Remove existing assignments for this stream (if it's a one-teacher-per-stream rule,
        // though many schools have multiple teachers, the requirement says "Class Teacher"
        // usually implies one primary responsible person).

        $stmt = $db->prepare("INSERT INTO user_stream_assignments (id, user_id, stream_id) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_id = VALUES(user_id)");
        $stmt->execute([Security::uuid(), $user_id, $stream_id]);

        Security::log('stream_assign_teacher', 'streams', $stream_id, null, ['user_id' => $user_id]);

        header('Location: /streams');
        exit;
    }
}
