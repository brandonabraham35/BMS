<?php

namespace App\Core;

class Security {
    public static function generateCsrfToken() {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function validateCsrfToken($token) {
        if (!$token) return false;
        return hash_equals(Session::get('csrf_token', ''), $token);
    }

    public static function escape($data) {
        if (is_array($data)) {
            return array_map([self::class, 'escape'], $data);
        }
        if ($data === null) return '';
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function safePath($path) {
        return str_replace(['..', './', '../'], '', $path);
    }

    public static function validateUpload($file, $allowed_types = ['image/jpeg', 'image/png'], $max_size = 2097152) {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        if ($file['size'] > $max_size) return false;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        return in_array($mime, $allowed_types);
    }

    /**
     * Audit Logging Helper
     */
    public static function log($action, $entity_type, $entity_id, $old_values = null, $new_values = null) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO audit_logs (id, user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            self::uuid(),
            Auth::id(),
            $action,
            $entity_type,
            $entity_id,
            $old_values ? json_encode($old_values) : null,
            $new_values ? json_encode($new_values) : null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
}
