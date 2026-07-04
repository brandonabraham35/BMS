<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Cards - SMS Uganda</title>
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
        <h2 class="mb-4">Student Reports</h2>

        <div class="card mb-4">
            <form action="/reports" method="GET" class="grid grid-2" style="align-items: end;">
                <div class="form-group mb-0">
                    <label>Select Stream</label>
                    <select name="stream_id" required onchange="this.form.submit()">
                        <option value="">Select Stream</option>
                        <?php foreach ($streams as $s): ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo ($stream_id === $s['id']) ? 'selected' : ''; ?>>
                                <?php echo \App\Core\Security::escape($s['class_name'] . ' ' . $s['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($stream_id): ?>
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Adm No.</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo \App\Core\Security::escape($student['admission_number']); ?></td>
                                    <td><?php echo \App\Core\Security::escape($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td>
                                        <select onchange="if(this.value) window.open('/reports/generate?student_id=<?php echo $student['id']; ?>&term_id='+this.value)">
                                            <option value="">View Report Card...</option>
                                            <?php foreach ($terms as $t): ?>
                                                <option value="<?php echo $t['id']; ?>"><?php echo \App\Core\Security::escape($t['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
