<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Phil's Soko</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('/css/w3.css') ?>" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
    <link href="<?= base_url('/css/login.css') ?>" type="text/css" rel="stylesheet">
    <script src="<?= base_url('/scripts/login.js') ?>"></script>
    <style>
        html,
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Lato", sans-serif;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="brand-title">Soko la Njue</div>
        <a href="#" class="toggle-button">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>
        <div class="navbar-links">
            <ul>
                <li><a href="<?= base_url('/') ?>">Home</a></li>
                <!--                <li><a href="#">About</a></li>-->
                <!--                <li><a href="#">Contact</a></li>-->
                <!--                <li><a href="--><? //= base_url('/login')
                                                    ?>
                <!--">Login</a></li>-->
                <li><a href="<?= base_url('/register') ?>">Sign Up</a></li>
            </ul>
        </div>
    </nav>

    <script>
        const toggleButton = document.getElementsByClassName('toggle-button')[0]
        const navbarLinks = document.getElementsByClassName('navbar-links')[0]

        toggleButton.addEventListener('click', () => {
            navbarLinks.classList.toggle('active')
        })
    </script>

    <main style="margin: auto; width: 60%;">

        <?php
        if (isset($num)) : ?>
            <div class="w3-display-container w3-container w3-blue w3-section">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Not Authenticated</h3>
                <p>You have to login first...</p>
            </div>
        <?php endif ?>

        <?php
        if (isset($_SESSION['logout'])) : ?>
            <div class="w3-display-container w3-container w3-green w3-section">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>Logout Successful</p>
            </div>
        <?php endif ?>

        <div class="w3-display-container w3-container w3-red w3-section w3-animate-opacity" id="invalid-msg" style="display: none;">
            <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3>Invalid Credentials</h3>
            <p>Try Again...</p>
        </div>

        <div class="w3-display-container w3-container w3-red w3-section w3-animate-opacity" id="no-record-msg" style="display: none;">
            <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <h3>Invalid Credentials</h3>
            <p>User not registered to Phil's Soko...</p>
        </div>

        <div class="w3-card-4 w3-section w3-animate-opacity" style="background-color: #fff;">

            <div class="w3-container w3-teal">
                <h1>Login</h1>
                <p>Log back in to order more clothes!</p>
            </div>

            <div class="w3-container">
                <br>
                <label for="emailaddress">Email</label>
                <input class="w3-input" type="text" id="emailaddress" name="email" autocomplete="new-password">
                <p id="emailResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>

                <label for="password1">Password</label>
                <input class="w3-input" type="password" id="password1" name="password">
                <p id="passResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p>

                <button class="w3-button w3-center w3-teal w3-hover-black w3-section" name="Login" onclick="checkEmail()">Login</button>

            </div>

            <div class="w3-container w3-teal">
                <h4>Don't Have An Account?</h4>
                <p>Click <a href="<?= base_url('register') ?>">here</a> to sign up to Soko la Njue</p>
            </div>

        </div>
    </main>

</body>

</html>