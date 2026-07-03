<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Marks - SMS Uganda</title>
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
        <h2 class="mb-4">Enter Marks</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Marks saved successfully!</div>
        <?php endif; ?>

        <div class="card mb-4">
            <form action="/marks" method="GET" class="grid grid-3" style="align-items: end;">
                <div class="form-group mb-0">
                    <label>Subject</label>
                    <select name="subject_id" required>
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo ($subject_id === $s['id']) ? 'selected' : ''; ?>>
                                <?php echo \App\Core\Security::escape($s['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label>Assessment Type</label>
                    <select name="assessment_type" required>
                        <option value="mid_term" <?php echo ($assessment_type === 'mid_term') ? 'selected' : ''; ?>>Mid Term</option>
                        <option value="end_of_term" <?php echo ($assessment_type === 'end_of_term') ? 'selected' : ''; ?>>End of Term</option>
                    </select>
                </div>
                <input type="hidden" name="stream_id" value="<?php echo \App\Core\Security::escape($stream_id); ?>">
                <button type="submit" class="btn btn-secondary">Load List</button>
            </form>
        </div>

        <?php if ($subject_id): ?>
            <div class="card">
                <form action="/marks" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                    <input type="hidden" name="stream_id" value="<?php echo \App\Core\Security::escape($stream_id); ?>">
                    <input type="hidden" name="subject_id" value="<?php echo \App\Core\Security::escape($subject_id); ?>">
                    <input type="hidden" name="assessment_type" value="<?php echo \App\Core\Security::escape($assessment_type); ?>">

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Score (Max 100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo \App\Core\Security::escape($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                        <td>
                                            <input type="number" name="scores[<?php echo $student['id']; ?>]"
                                                   value="<?php echo $existing[$student['id']] ?? ''; ?>"
                                                   min="0" max="100" step="0.5" style="max-width: 100px;">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Save Marks</button>
                </form>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
