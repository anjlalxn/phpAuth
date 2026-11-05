<?php

require_once 'config.php';


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

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

<div class="container mt-5">
    <h1 class="fs-4xl fw-bold fg-emphasis-inverse">Get Started</h1>
    <p class="fs-lg fw-light mb-4">Register to our platform</p>

    <form action="login.php" method="post" class="p-4 md:p-5 border rounded-3 bg-1">
        <div class="form-floating mb-3">
            <label for="floatingInput">Email address</label>
            <input
                type="email"
                name="email"
                class="form-control"
                id="floatingInput"
                placeholder="name@example.com"
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
