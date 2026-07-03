<?php

namespace App\Modules\Academic;

use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;

class StudentController {
    public function __construct() {
        Auth::requireLogin();
    }

    public function index() {
        $stream_id = $_GET['stream_id'] ?? '';
        $db = Database::getInstance();

        // RBAC: Class Teachers MUST provide a stream_id they are assigned to
        if (Auth::isTeacher()) {
            if (!$stream_id) {
                die("Unauthorized: Stream ID is required for teachers.");
            }
            Auth::requireStreamAccess($stream_id);
        }

        $query = "SELECT s.*, st.name as stream_name, cl.name as class_name
                  FROM students s
                  LEFT JOIN streams st ON s.current_stream_id = st.id
                  LEFT JOIN class_levels cl ON st.class_level_id = cl.id
                  WHERE s.deleted_at IS NULL";
        $params = [];

        if ($stream_id) {
            $query .= " AND s.current_stream_id = ?";
            $params[] = $stream_id;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $students = $stmt->fetchAll();

        include __DIR__ . '/../../Views/academic/students.php';
    }

    public function create() {
        Auth::requireAdmin();
        $db = Database::getInstance();
        $streams = $db->query("SELECT s.*, cl.name as class_name FROM streams s JOIN class_levels cl ON s.class_level_id = cl.id ORDER BY cl.name, s.name")->fetchAll();
        include __DIR__ . '/../../Views/academic/student_create.php';
    }

    public function store() {
        Auth::requireAdmin();
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $admission_number = $_POST['admission_number'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $stream_id = $_POST['stream_id'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        $db = Database::getInstance();
        $id = Security::uuid();
        $stmt = $db->prepare("INSERT INTO students (id, first_name, last_name, admission_number, gender, current_stream_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $first_name, $last_name, $admission_number, $gender, $stream_id]);

        Security::log('student_create', 'students', $id, null, $_POST);

        header('Location: /students?stream_id=' . $stream_id);
        exit;
    }

    public function delete() {
        Auth::requireAdmin();
        $id = $_POST['id'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE students SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);

        Security::log('student_delete', 'students', $id);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
