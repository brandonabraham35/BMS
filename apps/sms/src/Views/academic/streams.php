<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Streams - SMS Uganda</title>
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
        <h2 class="mb-4">Streams & Class Teachers</h2>

        <div class="card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Stream</th>
                            <th>Assign Class Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($streams as $stream): ?>
                            <tr>
                                <td><?php echo \App\Core\Security::escape($stream['class_name']); ?></td>
                                <td><strong><?php echo \App\Core\Security::escape($stream['name']); ?></strong></td>
                                <td>
                                    <form action="/streams/assign" method="POST" style="display: flex; gap: 0.5rem;">
                                        <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                                        <input type="hidden" name="stream_id" value="<?php echo $stream['id']; ?>">
                                        <select name="user_id" style="padding: 0.25rem;">
                                            <option value="">None</option>
                                            <?php foreach ($teachers as $t): ?>
                                                <option value="<?php echo $t['id']; ?>">
                                                    <?php echo \App\Core\Security::escape($t['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Assign</button>
                                    </form>
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
