<?php
require_once 'config.php';
require 'auth_check.php';

if (isset($_POST['logout'])) {
    $_SESSION = [];

    // Delete the session cookie in the browser too
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();
    header('Location: login.php');
    exit;
}

$current_user = $_SESSION['name'] ?? 'User';
?>

<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product example</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap"
        rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        .product-device {
            position: absolute;
            right: 10%;
            bottom: -30%;
            width: 300px;
            height: 540px;
            background-color: rgba(255, 255, 255, .25);
            border-radius: 21px;
            transform: rotate(30deg);
        }

        .product-device-2 {
            top: -25%;
            right: auto;
            bottom: 0;
            left: 5%;
            background-color: rgba(255, 255, 255, .25);
        }

        .tile {
            min-height: 380px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-md bg-body-tertiary sticky-top border-bottom">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="#">Apple</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <form method="post" action="home.php" class="m-0">
                        <button type="submit" name="logout" value="1"
                                class="nav-link btn btn-link">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <!-- Hero -->
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">
        <div class="col-md-6 p-lg-5 mx-auto my-5">
            <h1 class="display-3 fw-bold">Welcome, <?= htmlspecialchars($current_user) ?></h1>
            <h3 class="fw-normal text-body-secondary mb-3">Welcome to your admin dashboard</h3>
            <div class="d-flex gap-3 justify-content-center lead fw-normal">
                <a class="btn btn-outline-secondary" href="#">Learn more</a>
                <a class="btn btn-primary" href="#">Buy</a>
            </div>
        </div>
        <div class="product-device shadow-sm d-none d-md-block"></div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
    </div>

    <!-- Tile grid -->
    <div class="container-fluid px-3 px-md-4">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="tile text-bg-dark rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 py-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile bg-body-tertiary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 p-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="tile bg-body-tertiary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 p-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile text-bg-primary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 py-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="tile bg-body-tertiary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 p-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile bg-body-tertiary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 py-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="tile bg-body-tertiary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 p-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile bg-body-tertiary rounded-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
                    <div class="my-3 py-3">
                        <h2 class="display-5">Another headline</h2>
                        <p class="lead">And an even wittier subheading.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="container py-5">
    <div class="row">
        <div class="col-12 col-md">
            <strong>Product</strong>
            <small class="d-block mb-3 text-body-secondary">&copy; 2017&ndash;2026</small>
        </div>

        <div class="col-6 col-md">
            <h5>Features</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="#">Cool stuff</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Random feature</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Team feature</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Stuff for developers</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Another one</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Last time</a></li>
            </ul>
        </div>

        <div class="col-6 col-md">
            <h5>Resources</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="#">Resource name</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Resource</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Another resource</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Final resource</a></li>
            </ul>
        </div>

        <div class="col-6 col-md">
            <h5>Resources</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="#">Business</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Education</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Government</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Gaming</a></li>
            </ul>
        </div>

        <div class="col-6 col-md">
            <h5>About</h5>
            <ul class="list-unstyled text-small">
                <li><a class="link-secondary text-decoration-none" href="#">Team</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Locations</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Privacy</a></li>
                <li><a class="link-secondary text-decoration-none" href="#">Terms</a></li>
            </ul>
        </div>
    </div>
</footer>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>