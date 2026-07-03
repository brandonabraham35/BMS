<?php

namespace App\Modules\Settings;

use App\Core\Database;
use App\Core\Security;
use App\Core\Auth;
use PDO;

class SetupController {
    public function showSetup() {
        $db = Database::getInstance();
        if ($this->isSetup()) {
            header('Location: /login');
            exit;
        }

        include __DIR__ . '/../../Views/setup.php';
    }

    public function processSetup() {
        if ($this->isSetup()) {
            die("System already setup.");
        }

        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!Security::validateCsrfToken($csrf_token)) {
            die("CSRF token validation failed");
        }

        $db = Database::getInstance();

        $school_name = $_POST['school_name'] ?? '';
        $school_type = $_POST['school_type'] ?? '';
        $admin_name = $_POST['admin_name'] ?? '';
        $admin_email = $_POST['admin_email'] ?? '';
        $admin_username = $_POST['admin_username'] ?? '';
        $admin_password = $_POST['admin_password'] ?? '';

        if (!$school_name || !$school_type || !$admin_name || !$admin_email || !$admin_username || !$admin_password) {
            die("All fields are required");
        }

        $db->beginTransaction();
        try {
            // 1. Create School
            $school_id = Security::uuid();
            $stmt = $db->prepare("INSERT INTO schools (id, name, type, is_setup) VALUES (?, ?, ?, 1)");
            $stmt->execute([$school_id, $school_name, $school_type]);

            // 2. Create Admin User
            $admin_id = Security::uuid();
            $hashed_password = Security::hashPassword($admin_password);
            $stmt = $db->prepare("INSERT INTO users (id, name, email, username, password, role) VALUES (?, ?, ?, ?, ?, 'admin')");
            $stmt->execute([$admin_id, $admin_name, $admin_email, $admin_username, $hashed_password]);

            // 3. Create Sections and Class Levels
            if ($school_type === 'secondary') {
                $section_id = Security::uuid();
                $db->prepare("INSERT INTO sections (id, name) VALUES (?, 'Secondary')")->execute([$section_id]);

                $classes = ['Senior 1', 'Senior 2', 'Senior 3', 'Senior 4', 'Senior 5', 'Senior 6'];
                foreach ($classes as $class_name) {
                    $class_id = Security::uuid();
                    $db->prepare("INSERT INTO class_levels (id, section_id, name) VALUES (?, ?, ?)")->execute([$class_id, $section_id, $class_name]);
                    $db->prepare("INSERT INTO streams (id, class_level_id, name) VALUES (?, ?, 'A')")->execute([Security::uuid(), $class_id]);
                }
            } else {
                $nursery_section = Security::uuid();
                $db->prepare("INSERT INTO sections (id, name) VALUES (?, 'Nursery')")->execute([$nursery_section]);
                $primary_section = Security::uuid();
                $db->prepare("INSERT INTO sections (id, name) VALUES (?, 'Primary')")->execute([$primary_section]);

                $nursery = ['Baby Class', 'Middle Class', 'Top Class'];
                foreach ($nursery as $class_name) {
                    $class_id = Security::uuid();
                    $db->prepare("INSERT INTO class_levels (id, section_id, name) VALUES (?, ?, ?)")->execute([$class_id, $nursery_section, $class_name]);
                    $db->prepare("INSERT INTO streams (id, class_level_id, name) VALUES (?, ?, 'A')")->execute([Security::uuid(), $class_id]);
                }

                $primary = ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7'];
                foreach ($primary as $class_name) {
                    $class_id = Security::uuid();
                    $db->prepare("INSERT INTO class_levels (id, section_id, name) VALUES (?, ?, ?)")->execute([$class_id, $primary_section, $class_name]);
                    $db->prepare("INSERT INTO streams (id, class_level_id, name) VALUES (?, ?, 'A')")->execute([Security::uuid(), $class_id]);
                }
            }

            Security::log('system_setup', 'schools', $school_id);

            $db->commit();
            header('Location: /login?setup=success');
        } catch (\Exception $e) {
            $db->rollBack();
            die("Setup failed: " . $e->getMessage());
        }
    }

    private function isSetup() {
        $db = Database::getInstance();
        return $db->query("SELECT COUNT(*) FROM schools WHERE is_setup = 1")->fetchColumn() > 0;
    }
}
