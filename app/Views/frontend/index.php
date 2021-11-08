<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phil's Soko</title>
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('/css/index.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/w3.css') ?>">
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
    <nav>
        <ul class="navigation" style="margin-top: 0;"><span class="my-name">soko la njue</span>
            <li><a href="<?= base_url('/css/index.css') ?>">home</a></li>
            <li><a href="<?= base_url('/login') ?>">login</a></li>
            <li style="float: right;"><a href="<?= base_url('/register') ?>">sign up</a></li>
        </ul>
    </nav>

    <main>
        <div class="content" style="margin-top: 0;">
            <h1>Welcome to Soko La Njue</h1>
            <p>The premium clothing store for ...</p>
        </div>

        <div class="button" id="fifty">
            <a href="register.php"><button class="button" style="vertical-align:middle"><span>Join Now </span></button></a>
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

                <!-- Slideshow container -->
                <div class="slideshow-container">

                    <!-- Full-width images with number and caption text -->
                    <div class="mySlides fade w3-container">
                        <div class="numbertext w3-text-black">1 / 3</div>
                        <img src="../images/sports1.jpg" style="width:100%" class="w3-sepia">
                        <div class="text-slide w3-text-black w3-display-bottomright"><b>Sports Wear</b></div>
                    </div>

                    <div class="mySlides fade">
                        <div class="numbertext">2 / 3</div>
                        <img src="../images/casual.jpg" style="width:100%" class="w3-sepia">
                        <div class="text-slide w3-text-black">Casual</div>
                    </div>

                    <div class="mySlides fade">
                        <div class="numbertext w3-text-black">3 / 3</div>
                        <img src="../images/formal.jpg" style="width:100%" class="w3-sepia">
                        <div class="text-slide w3-text-black"><b>Formal</b></div>
                    </div>

                    <!-- Next and previous buttons -->
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>
                </div>
                <br>

                <!-- The dots/circles -->
                <div style="text-align:center" class="w3-section" style="margin-top: 0;">

                    <a href="menu.php" class="w3-button w3-black w3-hover-blue">View Catalogue</a>
                </div>
            </div>
            <script>
                var slideIndex = 0;
                showSlides();

                function showSlides() {
                    var i;
                    var slides = document.getElementsByClassName("mySlides");
                    for (i = 0; i < slides.length; i++) {
                        slides[i].style.display = "none";
                    }
                    slideIndex++;
                    if (slideIndex > slides.length) {
                        slideIndex = 1
                    }
                    slides[slideIndex - 1].style.display = "block";
                    setTimeout(showSlides, 3000); // Change image every 3 seconds
                }
            </script>
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

    </main>

    <footer>
        <p class="foot-note">&#169; 2021 Phil Nyaga . All rights reserved</p>
    </footer>
</body>

</html>