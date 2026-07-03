<?php

namespace App\Modules\Attendance;

use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;

class AttendanceController {
    public function __construct() {
        Auth::requireLogin();
    }

    public function index() {
        $stream_id = $_GET['stream_id'] ?? '';
        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$stream_id) die("Stream ID is required");

        // Strict RBAC check
        Auth::requireStreamAccess($stream_id);

        $db = Database::getInstance();

        // Fetch students in stream
        $stmt = $db->prepare("SELECT * FROM students WHERE current_stream_id = ? AND deleted_at IS NULL ORDER BY last_name, first_name");
        $stmt->execute([$stream_id]);
        $students = $stmt->fetchAll();

        // Fetch existing attendance
        $stmt = $db->prepare("SELECT * FROM attendance WHERE stream_id = ? AND attendance_date = ?");
        $stmt->execute([$stream_id, $date]);
        $existing = [];
        foreach ($stmt->fetchAll() as $row) {
            $existing[$row['student_id']] = $row['status'];
        }

        include __DIR__ . '/../../Views/attendance/index.php';
    }

    public function store() {
        $stream_id = $_POST['stream_id'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        $status = $_POST['status'] ?? [];
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        // Strict RBAC check
        Auth::requireStreamAccess($stream_id);

        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $term_id = $db->query("SELECT id FROM academic_terms WHERE is_active = 1 LIMIT 1")->fetchColumn();
            if (!$term_id) $term_id = $this->getOrCreateTermId();

            foreach ($status as $student_id => $s) {
                // Using ON DUPLICATE KEY UPDATE for efficiency and data integrity
                $stmt = $db->prepare("INSERT INTO attendance (id, student_id, stream_id, academic_term_id, attendance_date, status, recorded_by)
                                      VALUES (?, ?, ?, ?, ?, ?, ?)
                                      ON DUPLICATE KEY UPDATE status = VALUES(status), recorded_by = VALUES(recorded_by)");
                $stmt->execute([$this->uuid(), $student_id, $stream_id, $term_id, $date, $s, Auth::id()]);
            }
            $db->commit();
            header("Location: /attendance?stream_id=$stream_id&date=$date&success=1");
        } catch (\Exception $e) {
            $db->rollBack();
            die($e->getMessage());
        }
    }

    private function getOrCreateTermId() {
        $db = Database::getInstance();
        $year_id = $this->uuid();
        $db->prepare("INSERT INTO academic_years (id, name, is_active) VALUES (?, ?, 1)")->execute([$year_id, date('Y')]);
        $term_id = $this->uuid();
        $db->prepare("INSERT INTO academic_terms (id, academic_year_id, name, is_active) VALUES (?, ?, 'Term 1', 1)")->execute([$term_id, $year_id]);
        return $term_id;
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
