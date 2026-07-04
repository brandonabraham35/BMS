<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - SMS Uganda</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">SMS UGANDA</div>
                <a href="/dashboard" class="btn btn-secondary">Dashboard</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-4">
            <h2>Students</h2>
            <?php if (\App\Core\Auth::isAdmin()): ?>
                <a href="/students/create" class="btn btn-primary">Add Student</a>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Adm No.</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Class/Stream</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo \App\Core\Security::escape($student['admission_number']); ?></td>
                                <td><?php echo \App\Core\Security::escape($student['first_name']); ?></td>
                                <td><?php echo \App\Core\Security::escape($student['last_name']); ?></td>
                                <td><?php echo strtoupper($student['gender'][0]); ?></td>
                                <td><?php echo \App\Core\Security::escape($student['class_name'] . ' ' . $student['stream_name']); ?></td>
                                <td>
                                    <a href="/reports/generate?student_id=<?php echo $student['id']; ?>&term_id=1" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Report Card</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
