<?php

// Simple autoloader for PSR-4 like structure
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\Session;
use App\Core\Router;
use App\Core\Auth;

Session::start();

$router = new Router();

// Auth & Setup
$router->add('GET', '/', function() {
    if (!Auth::check()) { header('Location: /login'); exit; }
    header('Location: /dashboard'); exit;
});
$router->add('GET', 'setup', 'Settings\SetupController@showSetup');
$router->add('POST', 'setup', 'Settings\SetupController@processSetup');
$router->add('GET', 'login', 'Identity\AuthController@showLogin');
$router->add('POST', 'login', 'Identity\AuthController@login');
$router->add('GET', 'logout', 'Identity\AuthController@logout');
$router->add('GET', 'dashboard', 'Identity\AuthController@dashboard');

// Teachers (Admin Only)
$router->add('GET', 'teachers', 'Identity\TeacherController@index');
$router->add('GET', 'teachers/create', 'Identity\TeacherController@create');
$router->add('POST', 'teachers/create', 'Identity\TeacherController@store');
$router->add('GET', 'teachers/edit', 'Identity\TeacherController@edit');
$router->add('POST', 'teachers/edit', 'Identity\TeacherController@update');
$router->add('POST', 'teachers/delete', 'Identity\TeacherController@delete');

// Academic Structure
$router->add('GET', 'streams', 'Academic\AcademicController@streams');
$router->add('POST', 'streams/assign', 'Academic\AcademicController@assignTeacher');
$router->add('GET', 'subjects', 'Academic\AcademicController@subjects');

// Students
$router->add('GET', 'students', 'Academic\StudentController@index');
$router->add('GET', 'students/create', 'Academic\StudentController@create');
$router->add('POST', 'students/create', 'Academic\StudentController@store');
$router->add('POST', 'students/delete', 'Academic\StudentController@delete');

// Attendance
$router->add('GET', 'attendance', 'Attendance\AttendanceController@index');
$router->add('POST', 'attendance', 'Attendance\AttendanceController@store');

// Marks
$router->add('GET', 'marks', 'Marks\MarksController@index');
$router->add('POST', 'marks', 'Marks\MarksController@store');

// Reports
$router->add('GET', 'reports', 'Academic\ReportController@index');
$router->add('GET', 'reports/generate', 'Academic\ReportController@generate');

$router->dispatch();
