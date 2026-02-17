<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Allocation</title>
    <style>
        /* Reusing the CSS from the installer for consistency */
        body { font-family: -apple-system, sans-serif; background-color: #f4f5f7; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 3px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 350px; }
        h1 { color: #0052cc; text-align: center; font-size: 24px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 12px; font-weight: 600; color: #5e6c84; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 2px solid #dfe1e6; border-radius: 3px; box-sizing: border-box; }
        input:focus { border-color: #0052cc; outline: none; }
        .btn { background: #0052cc; color: white; width: 100%; padding: 10px; border: none; border-radius: 3px; font-weight: bold; cursor: pointer; margin-top: 10px;}
        .btn:hover { background: #0065ff; }
        .error { background: #ffebe6; color: #de350b; padding: 10px; border-radius: 3px; margin-bottom: 15px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>System Login</h1>

        <?php if(isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="/auth/login" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="admin@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
    </div>

</body>
</html>