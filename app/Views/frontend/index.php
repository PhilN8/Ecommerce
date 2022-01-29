<?php

if (isset($_POST['sendMsg'])) {
    header('location:index.php?Message=yes');
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Soko La Njue | Ecommerce Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="<?= base_url('/css/w3.css')?>">
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
    <link href="<?= base_url('/css/index.css')?>" type="text/css" rel="stylesheet">
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
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="<?= base_url('/login')?>">Login</a></li>
            <li><a href="<?= base_url('/register')?>">Sign Up</a></li>
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


<?php if (isset($_REQUEST['Message'])) { ?>
    <div class="w3-container w3-blue w3-display-container w3-text-left" style="margin-top: 0;">
        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
        <h3>Message Received</h3>
        <p>Thank you for your feedback!</p>
    </div>
<?php }
?>

<div class="row">
    <div class="w3-center col-2" style="align-self: center;">
        <h1>Welcome to <br/>Soko La Njue</h1>

        <p style="font-size: 1.2em;">
            The premium store for all your fashion desires
        </p>

        <a href="#" class="btn">Explore Now &#8594;</a>
    </div>

    <div class="col-2" id="bg-image">
        <img src="<?= base_url('/images/website/image-2.jpg')?>">
    </div>
</div>



<div class="w3-row" style="background-color: #fff;">
    <div class="w3-half w3-large w3-padding-large">

        <h1 class="w3-center">About Us</h1><br>
        <h5 class="w3-center">Tradition since 2021</h5>
        <p class="w3-large">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <p class="w3-large">
            We seek to partner with robust and enthusiastic businesses as part of our empowerment mission. Soko La Njue is committed to quality, reliable delivery and accessibility, hence our doors are open to collaborative ventures, given you meet our criteria.
            Email us! <a href="#sendMessage">sokolanjue@gmail.com</a></p>

    </div>

    <div class="w3-half">


    </div>

</div>

<!-- Contact -->
<div id="contact" class="w3-container w3-padding-64 w3-row">
    <div class="w3-half w3-large w3-padding-large">
        <h1>Contact</h1>

        <p class="w3-text-blue-grey w3-large">
            <b>find us at P.O. Box 75004-00200, Likoni Road,
                South B, Kenya</b>
        </p>
        <p><i class="fa fa-envelope"></i> Email: sokolanjue@gmail.com</p>
        <p><i class="fa fa-phone-square"></i> Phone: +254 758 300300</p>
    </div>
    <div class="w3-half w3-padding-large" id="sendMessage">
        <h1>Get In Touch</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <p><input class="w3-input w3-padding-16" type="text" placeholder="Name" required name="Name"></p>
            <p><input class="w3-input w3-padding-16" type="email" placeholder="Email" required name="Email"></p>
            <p><input class="w3-input w3-padding-16" type="text" placeholder="Message" required name="Message"></p>
            <p><button class="w3-button w3-black w3-hover-blue w3-section" type="submit" name="sendMsg">Send Message</button></p>
        </form>
    </div>
</div>

<p class="foot-note">&#169; 2021 Phil Nyaga . All rights reserved</p>

</body>

</html>