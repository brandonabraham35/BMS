<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance - SMS Uganda</title>
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
        <h2 class="mb-4">Attendance: <?php echo date('d M, Y', strtotime($date)); ?></h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Attendance saved successfully!</div>
        <?php endif; ?>

        <div class="card">
            <form action="/attendance" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                <input type="hidden" name="stream_id" value="<?php echo \App\Core\Security::escape($stream_id); ?>">
                <input type="hidden" name="date" value="<?php echo \App\Core\Security::escape($date); ?>">

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo \App\Core\Security::escape($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td>
                                        <select name="status[<?php echo $student['id']; ?>]">
                                            <option value="present" <?php echo ($existing[$student['id']] ?? '') === 'present' ? 'selected' : ''; ?>>Present</option>
                                            <option value="absent" <?php echo ($existing[$student['id']] ?? '') === 'absent' ? 'selected' : ''; ?>>Absent</option>
                                            <option value="late" <?php echo ($existing[$student['id']] ?? '') === 'late' ? 'selected' : ''; ?>>Late</option>
                                            <option value="sick" <?php echo ($existing[$student['id']] ?? '') === 'sick' ? 'selected' : ''; ?>>Sick</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Save Attendance</button>
            </form>
        </div>
    </main>
</body>
</html>
