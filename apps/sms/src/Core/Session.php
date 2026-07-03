<?php

namespace App\Core;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Production session settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 0); // Should be 1 in production with HTTPS
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.gc_maxlifetime', 1800); // 30 minutes

            session_start();
        }

        // Check for session timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            self::destroy();
            header('Location: /login?error=session_expired');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }

    public static function regenerate() {
        session_regenerate_id(true);
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/', '', false, true);
        }
        session_destroy();
    }
}
