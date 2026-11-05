<?php
require_once 'config.php';

$errors = [];
$name   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_pattern = '/^[a-zA-Z0-9_]{3,16}$/';

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($name === '' || !preg_match($user_pattern, $name)) {
        $errors[] = 'Please enter a valid username (3-16 characters: letters, numbers, underscores).';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($pass) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    // Only touch the database if everything above passed
    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)'
        );

        try {
            $stmt->execute([$name, $email, $hash]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                // Duplicate entry (needs a UNIQUE key on email and/or name)
                $errors[] = 'That email or username is already registered.';
            } else {
                // Shows the real error while developing; use a generic message in production
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
        <p class="fs-lg fw-light mb-4">Got an account? Head to the login page</p>

        <div class="d-grid gap-2 sm:d-flex sm:justify-content-center">
            <a href="login.php" class="btn-outline theme-secondary btn-lg px-4">
                Login
            </a>

        </div>
    </div>



</div>

<div class="container mt-5">
    <h1 class="fs-4xl fw-bold fg-emphasis-inverse">Get Started</h1>
    <p class="fs-lg fw-light mb-4">Register to our platform</p>

    <?php if (!empty($errors)): ?>
        <div class="alert theme-danger mb-5" role="alert" id="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="post" class="p-4 md:p-5 border rounded-3 bg-1">
        <div class="form-floating mb-3">
            <label for="floatingEmail">Email address</label>
            <input
                type="email"
                name="email"
                class="form-control"
                id="floatingEmail"
                placeholder="name@example.com"
                value="<?= htmlspecialchars($email) ?>"
                required>
        </div>

        <div class="form-floating mb-3">
            <label for="floatingUsername">Username</label>
            <input
                type="text"
                name="name"
                class="form-control"
                id="floatingUsername"
                placeholder="example_username"
                value="<?= htmlspecialchars($name) ?>"
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
            Sign up
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