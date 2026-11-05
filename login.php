<?php
require_once 'config.php';
session_start();

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $errors[] = 'Please enter your email and password.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
                'SELECT id, name, password_hash, role FROM users WHERE email = ?'
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role']    = $user['role'];
            header('Location: home.php');
            exit;
        }

        // Same message for wrong email and wrong password,
        // so attackers can't tell which one was incorrect
        $errors[] = 'Wrong email or password.';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
            href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap"
            rel="stylesheet">

    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe"
            crossorigin="anonymous">
</head>
<body>

<div class="px-4 py-5 my-5 text-center">
    <img
            class="d-block mx-auto mb-4"
            src="https://i.pinimg.com/736x/37/bc/8c/37bc8ce0a09064470f0a5968bf2dd73e.jpg"
            alt=""
            width="72"
            height="72">

    <h1 class="fs-4xl fw-bold fg-emphasis-inverse">Hello</h1>

    <div class="lg:col-6 mx-auto">
        <p class="fs-lg fw-light mb-4">Got no account? Head to the signup page</p>

        <div class="d-grid gap-2 sm:d-flex sm:justify-content-center">
            <a href="register.php" class="btn-outline theme-secondary btn-lg px-4">
                Register for an account
            </a>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1 class="fs-4xl fw-bold fg-emphasis-inverse">Welcome back</h1>
    <p class="fs-lg fw-light mb-4">Login to our platform</p>

    <?php if (!empty($errors)): ?>
        <div class="alert theme-danger mb-5" role="alert" id="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post" class="p-4 md:p-5 border rounded-3 bg-1">
        <div class="form-floating mb-3">
            <label for="floatingInput">Email address</label>
            <input
                    type="email"
                    name="email"
                    class="form-control"
                    id="floatingInput"
                    placeholder="name@example.com"
                    value="<?= htmlspecialchars($email) ?>"
                    required>
        </div>

        <div class="form-floating mb-3">
            <label for="floatingPassword">Password</label>
            <input
                    type="password"
                    name="password"
                    class="form-control"
                    id="floatingPassword"
                    placeholder="Password"
                    required>
        </div>

        <button class="w-100 btn-solid theme-primary btn-lg" type="submit">
            Login
        </button>

        <hr class="my-4">
    </form>
</div>

<script
        type="module"
        src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-1a/pXj49ZQ1aHEmrJ+gMw1otqoVsYwlEnlD8mIfY2TV03r20Y0CN7uqx1tQogjPL"
        crossorigin="anonymous"></script>

</body>
</html>