<?php

namespace App\Core;

class Auth {
    public static function user() {
        return Session::get('user');
    }

    public static function check() {
        return Session::has('user');
    }

    public static function id() {
        $user = self::user();
        return $user ? $user['id'] : null;
    }

    public static function role() {
        $user = self::user();
        return $user ? $user['role'] : null;
    }

    public static function isAdmin() {
        return self::role() === 'admin';
    }

    public static function isTeacher() {
        return self::role() === 'teacher';
    }

    public static function login($user) {
        unset($user['password']);
        Session::set('user', $user);
        Session::regenerate();
    }

    public static function logout() {
        Session::destroy();
    }

    public static function requireAdmin() {
        if (!self::isAdmin()) {
            http_response_code(403);
            die("403 Forbidden: Administrator access required.");
        }
    }

    public static function requireLogin() {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Strict check for teacher's access to a specific stream
     */
    public static function canAccessStream($stream_id) {
        if (self::isAdmin()) return true;
        if (!self::isTeacher()) return false;

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_stream_assignments WHERE user_id = ? AND stream_id = ?");
        $stmt->execute([self::id(), $stream_id]);
        return $stmt->fetchColumn() > 0;
    }

    public static function requireStreamAccess($stream_id) {
        if (!self::canAccessStream($stream_id)) {
            http_response_code(403);
            die("403 Forbidden: You do not have access to this stream.");
        }
    }
}
