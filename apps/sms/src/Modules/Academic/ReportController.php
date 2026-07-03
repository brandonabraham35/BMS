<?php

namespace App\Modules\Academic;

use App\Core\Database;
use App\Core\Security;
use App\Core\Auth;

class ReportController {
    public function __construct() {
        Auth::requireLogin();
    }

    public function index() {
        $stream_id = $_GET['stream_id'] ?? '';
        $db = Database::getInstance();

        if ($stream_id) {
            Auth::requireStreamAccess($stream_id);
            $stmt = $db->prepare("SELECT * FROM students WHERE current_stream_id = ? AND deleted_at IS NULL ORDER BY last_name, first_name");
            $stmt->execute([$stream_id]);
            $students = $stmt->fetchAll();
        } else {
            $students = [];
        }

        $streams = [];
        if (Auth::isAdmin()) {
            $streams = $db->query("SELECT s.*, c.name as class_name FROM streams s JOIN class_levels c ON s.class_level_id = c.id ORDER BY c.name, s.name")->fetchAll();
        } else {
            $stmt = $db->prepare("SELECT s.*, c.name as class_name FROM streams s JOIN class_levels c ON s.class_level_id = c.id JOIN user_stream_assignments usa ON s.id = usa.stream_id WHERE usa.user_id = ?");
            $stmt->execute([Auth::id()]);
            $streams = $stmt->fetchAll();
        }

        $terms = $db->query("SELECT * FROM academic_terms ORDER BY is_active DESC, start_date DESC")->fetchAll();

        include __DIR__ . '/../../Views/academic/reports_list.php';
    }

    public function generate() {
        $student_id = $_GET['student_id'] ?? '';
        $term_id = $_GET['term_id'] ?? '';

        if (!$student_id || !$term_id) die("Student ID and Term ID are required");

        $db = Database::getInstance();

        // Fetch student and their stream access check
        $stmt = $db->prepare("SELECT s.*, st.name as stream_name, cl.name as class_name, st.id as stream_id
                              FROM students s
                              JOIN streams st ON s.current_stream_id = st.id
                              JOIN class_levels cl ON st.class_level_id = cl.id
                              WHERE s.id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();

        if (!$student) die("Student not found");
        Auth::requireStreamAccess($student['stream_id']);

        // Fetch marks and subjects
        $stmt = $db->prepare("SELECT m.*, s.name as subject_name
                              FROM subjects s
                              LEFT JOIN marks m ON s.id = m.subject_id AND m.student_id = ? AND m.academic_term_id = ?
                              ORDER BY s.name ASC");
        $stmt->execute([$student_id, $term_id]);
        $rawMarks = $stmt->fetchAll();

        // Process marks (group by subject)
        $processedMarks = [];
        $totalScore = 0;
        $subjectCount = 0;
        foreach ($rawMarks as $row) {
            $subjectId = $row['subject_id'] ?? 'none';
            if (!isset($processedMarks[$row['subject_name']])) {
                $processedMarks[$row['subject_name']] = [
                    'mid_term' => null,
                    'end_of_term' => null,
                    'continuous' => null,
                    'total' => 0
                ];
            }
            if ($row['assessment_type']) {
                $processedMarks[$row['subject_name']][$row['assessment_type']] = $row['score'];
            }
        }

        // Calculate Totals and Grades per subject
        foreach ($processedMarks as $name => &$data) {
            // In Uganda, often Mid Term is 40% and End of Term is 60%, or just simple sum.
            // Let's assume a simple sum for now or a standardized total.
            $mt = $data['mid_term'] ?? 0;
            $et = $data['end_of_term'] ?? 0;
            $data['total'] = $mt + $et;
            $data['grade'] = $this->calculateGrade($data['total']);
            $totalScore += $data['total'];
            $subjectCount++;
        }

        $average = $subjectCount > 0 ? $totalScore / $subjectCount : 0;

        // Calculate Position in Stream
        $position = $this->calculatePosition($student_id, $student['stream_id'], $term_id);

        // Fetch attendance stats
        $stmt = $db->prepare("SELECT status, COUNT(*) as count FROM attendance WHERE student_id = ? AND academic_term_id = ? GROUP BY status");
        $stmt->execute([$student_id, $term_id]);
        $attendance = $stmt->fetchAll();

        include __DIR__ . '/../../Views/academic/report_card.php';
    }

    private function calculateGrade($score) {
        if ($score >= 90) return 'D1';
        if ($score >= 80) return 'D2';
        if ($score >= 70) return 'C3';
        if ($score >= 60) return 'C4';
        if ($score >= 50) return 'C5';
        if ($score >= 45) return 'C6';
        if ($score >= 40) return 'P7';
        if ($score >= 35) return 'P8';
        return 'F9';
    }

    private function calculatePosition($student_id, $stream_id, $term_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT student_id, SUM(score) as total_score
            FROM marks
            WHERE academic_term_id = ? AND student_id IN (SELECT id FROM students WHERE current_stream_id = ?)
            GROUP BY student_id
            ORDER BY total_score DESC
        ");
        $stmt->execute([$term_id, $stream_id]);
        $rankings = $stmt->fetchAll();

        $pos = 1;
        foreach ($rankings as $rank) {
            if ($rank['student_id'] === $student_id) return $pos;
            $pos++;
        }
        return 'N/A';
    }
}
