<?php
require_once 'config.php';
require 'auth_check.php';            // must be logged in; also starts the session

$errors = [];

// CSRF 1: generate once per session (auth_check.php doesn't create it)
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// CSRF 3: verify on every POST, before either form is handled
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Invalid CSRF token');
    }
}

if (isset($_POST['edit_details'])) {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $pass === '') {
        $errors[] = 'Name, email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // Check the password against the LOGGED-IN user's hash
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($pass, $hash)) {
            $errors[] = 'Wrong password.';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
            $stmt->execute([$name, $email, $_SESSION['user_id']]);

            $_SESSION['name'] = $name;       // keeps the welcome message on home.php up to date
            header('Location: profile.php?updated=1');
            exit;                            // redirect so refreshing doesn't resubmit
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) { // duplicate entry (UNIQUE email)
                $errors[] = 'That email is already used by another account.';
            } else {
                throw $e;
            }
        }
    }
}

if (isset($_POST['change_password'])) {
    $current_p = $_POST['current_password'] ?? '';
    $new_p     = $_POST['new_password'] ?? '';
    $confirm_p = $_POST['confirm_password'] ?? '';

    if ($current_p === '' || $new_p === '' || $confirm_p === '') {
        $errors[] = 'All password fields are required.';
    } elseif (strlen($new_p) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    } elseif ($new_p !== $confirm_p) {
        $errors[] = 'Passwords do not match.';
    }

    // Check the CURRENT password against the logged-in user's hash
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($current_p, $hash)) {
            $errors[] = 'Current password is wrong.';
        }
    }

    if (empty($errors)) {
        $newHash = password_hash($new_p, PASSWORD_DEFAULT, ['cost' => 12]);
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$newHash, $_SESSION['user_id']]);

        session_regenerate_id(true);   // session data (user_id, name, role, csrf) is kept
        header('Location: profile.php?updated=1');
        exit;
    }
}

// Load the user AFTER the updates so the page shows the latest values
$stmt = $pdo->prepare(
    'SELECT name, email, role, created_at FROM users WHERE id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: logout.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
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

<!-- Change password dialog -->
<dialog class="dialog" id="editPass">
    <form action="profile.php" method="post">

        <!-- CSRF 2: token in the form -->
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

        <div class="dialog-header">
            <h2 class="dialog-title fs-xl fw-bold">Change password</h2>
        </div>

        <div class="dialog-body">
            <div class="mb-3">
                <label for="currentPass" class="form-label">Current password</label>
                <input
                    type="password"
                    name="current_password"
                    id="currentPass"
                    class="form-control"
                    autocomplete="current-password"
                    required>
            </div>

            <div class="mb-3">
                <label for="newPass" class="form-label">New password</label>
                <input
                    type="password"
                    name="new_password"
                    id="newPass"
                    class="form-control"
                    autocomplete="new-password"
                    minlength="8"
                    required>
                <div class="form-text">At least 8 characters.</div>
            </div>

            <div class="mb-3">
                <label for="confirmNewPass" class="form-label">Confirm new password</label>
                <input
                    type="password"
                    name="confirm_password"
                    id="confirmNewPass"
                    class="form-control"
                    autocomplete="new-password"
                    minlength="8"
                    required>
            </div>
        </div>

        <div class="dialog-footer">
            <button type="button" class="btn-outline theme-secondary" data-bs-dismiss="dialog">
                Cancel
            </button>
            <button type="submit" class="btn-solid theme-danger" name="change_password" value="1">
                Update password
            </button>
        </div>
    </form>
</dialog>

<!-- Edit profile dialog -->
<dialog class="dialog" id="editProfileDialog">
    <form action="profile.php" method="post">

        <!-- CSRF 2: token in the form -->
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

        <div class="dialog-header">
            <h2 class="dialog-title fs-xl fw-bold">Edit profile</h2>
        </div>

        <div class="dialog-body">
            <div class="mb-3">
                <label for="editName" class="form-label">Full name</label>
                <input
                    type="text"
                    name="name"
                    id="editName"
                    class="form-control"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="editEmail" class="form-label">Email address</label>
                <input
                    type="email"
                    name="email"
                    id="editEmail"
                    class="form-control"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="editRole" class="form-label">Role</label>
                <input
                    type="text"
                    id="editRole"
                    class="form-control"
                    value="<?= htmlspecialchars(ucfirst($user['role'])) ?>"
                    disabled>
            </div>

            <div class="mb-3">
                <label for="confirmPass" class="form-label">Enter password to confirm changes</label>
                <input
                    type="password"
                    name="password"
                    id="confirmPass"
                    class="form-control"
                    autocomplete="current-password"
                    required>
            </div>
        </div>

        <div class="dialog-footer">
            <button type="button" class="btn-outline theme-secondary" data-bs-dismiss="dialog">
                Cancel
            </button>
            <button type="submit" class="btn-solid theme-primary" name="edit_details" value="1">
                Save changes
            </button>
        </div>
    </form>
</dialog>

<div class="container mt-5 mb-5">

    <!-- Header -->
    <div class="d-flex align-items-center gap-4 mb-5">
        <img
            src="https://i.pinimg.com/736x/37/bc/8c/37bc8ce0a09064470f0a5968bf2dd73e.jpg"
            alt="Profile picture"
            class="rounded-circle"
            width="96"
            height="96">

        <div>
            <h1 class="fs-4xl fw-bold fg-emphasis-inverse mb-1"><?= htmlspecialchars($user['name']) ?></h1>
            <p class="fs-lg fw-light mb-0"><?= htmlspecialchars($user['email']) ?></p>
        </div>
    </div>

    <!-- Messages -->
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert theme-success mb-4" role="alert">
            <p class="mb-0">Profile updated.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert theme-danger mb-4" role="alert">
            <?php foreach ($errors as $error): ?>
                <p class="mb-0"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Account details -->
    <div class="p-4 md:p-5 border rounded-3 bg-1 mb-4">
        <h2 class="fs-xl fw-bold mb-4">Account details</h2>

        <dl class="row mb-0">
            <dt class="col-sm-3">Full name</dt>
            <dd class="col-sm-9"><?= htmlspecialchars($user['name']) ?></dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9"><?= htmlspecialchars($user['email']) ?></dd>

            <dt class="col-sm-3">Role</dt>
            <dd class="col-sm-9"><?= htmlspecialchars(ucfirst($user['role'])) ?></dd>

            <dt class="col-sm-3">Member since</dt>
            <dd class="col-sm-9"><?= date('F j, Y', strtotime($user['created_at'])) ?></dd>
        </dl>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2">
        <a href="home.php" class="btn-outline theme-secondary btn-lg px-4">Back to home</a>
        <button type="button" class="btn-solid theme-primary btn-lg px-4"
                data-bs-toggle="dialog" data-bs-target="#editProfileDialog">
            Edit profile
        </button>

        <button type="button" class="btn-solid theme-danger btn-lg px-4"
                data-bs-toggle="dialog" data-bs-target="#editPass">
            Change Password
        </button>
    </div>
</div>

<script
    type="module"
    src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-1a/pXj49ZQ1aHEmrJ+gMw1otqoVsYwlEnlD8mIfY2TV03r20Y0CN7uqx1tQogjPL"
    crossorigin="anonymous"></script>

</body>
</html>