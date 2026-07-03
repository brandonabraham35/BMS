<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher - SMS Uganda</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">SMS UGANDA</div>
                <a href="/teachers" class="btn btn-secondary">Back</a>
            </nav>
        </div>
    </header>

    <main class="container" style="max-width: 600px;">
        <h2>Add New Teacher</h2>
        <div class="card">
            <form action="/teachers/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="Full Name">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="email@example.com">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="username">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create Teacher Account</button>
            </form>
        </div>
    </main>
</body>
</html>
