<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register - Phil's Soko</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
    <script src="<?= base_url('/scripts/register.js') ?>"></script>
    <link href="<?= base_url('/css/register.css') ?>" type="text/css" rel="stylesheet">
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
                <li><a href="<?= base_url('/')?>">Home</a></li>
<!--                <li><a href="#">About</a></li>-->
<!--                <li><a href="#">Contact</a></li>-->
                <li><a href="<?= base_url('/login')?>">Login</a></li>
<!--                <li><a href="--><?//= base_url('/register')?><!--">Sign Up</a></li>-->
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

    <div class="w3-display-container w3-container w3-red w3-section" style="margin: auto; width: 60%; display: none;" id="reg-failed-msg">
        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
        <h3>Registration Failed</h3>
        <p>Try again...</p>
    </div>

    <div class="w3-display-container w3-container w3-red w3-section" style="margin: auto; width: 60%; display: none;" id="email-msg">
        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
        <h3>Registration Failed</h3>
        <p>Email already exists...</p>
    </div>

    <?php if (isset($validation)) : ?>
        <div class="w3-display-container w3-container w3-red w3-section" style="margin: auto; width: 60%;">
            <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <?php if (isset($data)) : ?>
        <div class="w3-display-container w3-container w3-blue w3-section" style="margin: auto; width: 60%;">
            <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
            <?= print_r($data) ?>
        </div>
    <?php endif; ?>

    <main style="margin: auto; width: 60%;">

        <div class="w3-card-4 w3-section w3-animate-opacity" style="background-color: #fff;">

            <div class="w3-container w3-teal">
                <h1>Register to Soko La Njue</h1>
                <p>Sign up to upgrade your wardrobe!</p>
            </div>

            <form class="w3-container" method="POST" action="<?= base_url('/Registration/registerUser') ?>">
                <br>
                <label for="first-name">First Name</label>
                <input class="w3-input" type="text" name="fname" id="first-name">
                <p id="fNameResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>

                <label for="last-name">Last Name</label>
                <input class="w3-input" type="text" name="lname" id="last-name">
                <p id="lNameResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="emailaddress">Email</label>
                <input class="w3-input" type="email" id="emailaddress" name="email">
                <p id="emailResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="password1">Set Password</label>
                <input class="w3-input" type="password" id="password1" name="pword1">
                <p id="passResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="password2">Confirm Password</label>
                <input class="w3-input" type="password" id="password2" name="pword2">
                <p id="pass2Result" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="genders">Gender</label>
                <select id="genders" name="gender" class="w3-input">
                    <option value="male" selected>Male</option>
                    <option value="female">Female</option>
                </select>

            </form>
            <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3-section" name="Login" onclick="registerUser()">Login</button>

            <div class="w3-container w3-teal">
                <h4>Already Have An Account?</h4>
                <p>Click <a href="<?= base_url('login') ?>">here</a> to log back in!</p>
            </div>
        </div>

        <script>

        </script>
    </main>

    <p class="foot-note">&#169; 2021 Phil Nyaga . All rights reserved</p>

</body>

</html>