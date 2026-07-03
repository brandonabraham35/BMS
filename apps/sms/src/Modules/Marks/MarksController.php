<?php

namespace App\Modules\Marks;

use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;

class MarksController {
    public function __construct() {
        Auth::requireLogin();
    }

    public function index() {
        $stream_id = $_GET['stream_id'] ?? '';
        $subject_id = $_GET['subject_id'] ?? '';
        $assessment_type = $_GET['assessment_type'] ?? 'mid_term';

        if (!$stream_id) die("Stream ID is required");

        // Strict RBAC check
        Auth::requireStreamAccess($stream_id);

        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM students WHERE current_stream_id = ? AND deleted_at IS NULL ORDER BY last_name, first_name");
        $stmt->execute([$stream_id]);
        $students = $stmt->fetchAll();

        $subjects = $db->query("SELECT * FROM subjects ORDER BY name")->fetchAll();

        $existing = [];
        if ($subject_id) {
            $stmt = $db->prepare("SELECT * FROM marks WHERE subject_id = ? AND assessment_type = ? AND student_id IN (SELECT id FROM students WHERE current_stream_id = ?)");
            $stmt->execute([$subject_id, $assessment_type, $stream_id]);
            foreach ($stmt->fetchAll() as $row) {
                $existing[$row['student_id']] = $row['score'];
            }
        }

        include __DIR__ . '/../../Views/marks/index.php';
    }

    public function store() {
        $stream_id = $_POST['stream_id'] ?? '';
        $subject_id = $_POST['subject_id'] ?? '';
        $assessment_type = $_POST['assessment_type'] ?? '';
        $scores = $_POST['scores'] ?? [];
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!Security::validateCsrfToken($csrf_token)) die("CSRF failed");

        // Strict RBAC check
        Auth::requireStreamAccess($stream_id);

        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $term_id = $db->query("SELECT id FROM academic_terms WHERE is_active = 1 LIMIT 1")->fetchColumn();
            if (!$term_id) die("No active term found");

            foreach ($scores as $student_id => $score) {
                if ($score === '') continue;

                $stmt = $db->prepare("INSERT INTO marks (id, student_id, subject_id, academic_term_id, assessment_type, score, recorded_by)
                                      VALUES (?, ?, ?, ?, ?, ?, ?)
                                      ON DUPLICATE KEY UPDATE score = VALUES(score), recorded_by = VALUES(recorded_by)");
                $stmt->execute([$this->uuid(), $student_id, $subject_id, $term_id, $assessment_type, $score, Auth::id()]);
            }
            $db->commit();
            header("Location: /marks?stream_id=$stream_id&subject_id=$subject_id&assessment_type=$assessment_type&success=1");
        } catch (\Exception $e) {
            $db->rollBack();
            die($e->getMessage());
        }
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
