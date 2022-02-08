<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Soko La Njue</title>
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/css/shop.css') ?>">
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
                <li><a href="<?= base_url('/login') ?>">Login</a></li>
                <li><a href="<?= base_url('/register') ?>">Sign Up</a></li>
            </ul>
        </div>
    </nav>

    <main>

        <header class="main-header">
            <h1 class="main-header__title">Soko La Njue</h1>
            <p class="main-header__text">Enjoy our expansive and vibrant catalog</p>
        </header>

        <section class="all-products">
            <div class="container flex">
                <?php // if (isset($products)) :
                foreach ($products as $product) {
                    extract($product); ?>
                    <div class="product">
                        <div class="product__container">
                            <img src="<?= base_url('images/database/' . $product_image) ?>" alt="" class="product__img">
                            <div class="product__text">
                                <h2 class="product__name"><?= $product_name ?></h2>
                                <p class="product__description"><?= $product_description ?></p>
                                <p class="product__price">Ksh. <?= $unit_price ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>

        <header class="pets-header">
            <h1 class="pets-header__title">To all Pet Lovers..</h1>
            <p class="pets-header__text">Aren't they so cute?<br>Pick one up from our pet store!</p>
        </header>

        <section class="all-pets">
            <div class="container flex">
                <?php // if (isset($products)) :
                foreach ($pets as $pet) {
                    extract($pet); ?>
                    <div class="product">
                        <div class="product__container">
                            <img src="<?= base_url('images/database/' . $product_image) ?>" alt="" class="product__img">
                            <div class="product__text">
                                <h2 class="product__name"><?= $product_name ?></h2>
                                <p class="product__description"><?= $product_description ?></p>
                                <p class="product__price">Ksh. <?= $unit_price ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>


    <footer class="footer">
        <p class="foot-note">&#169; 2021-2022 Soko La Njue. All rights reserved</p>
    </footer>
</body>

</html>