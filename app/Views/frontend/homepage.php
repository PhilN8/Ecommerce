<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="<?= base_url('/css/w3.css') ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?= base_url('/scripts/homepage.js') ?>"></script>
    <style>
        .container {
            font-size: 5em;
            background-color: #a8a8a8;
            color: white;
            width: 8em;
            height: 2em;
            line-height: 2;
            text-align: center;
            font-family: Helvetica, Arial, sans-serif;
            font-weight: bold;
            cursor: pointer;
            position: relative;
        }

        .link {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        th,
        td {
            text-align: center;
            padding: 15px;
        }

        th,
        #total-cost {
            border: solid black;
            background-color: #000;
            color: #fff;
        }

        table {
            background-color: #fff;
            margin-left: auto;
            margin-right: auto;
        }

        th {
            background-color: black;
            color: white;
        }
    </style>
</head>

<body>
    <nav class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width: 20%; float: left;">
        <h5 class="w3-bar-item w3-black" style="margin-top: 0; margin-bottom: 0;">Users</h5>
        <button class="w3-bar-item w3-button tablinks w3-blue" onclick="showSection(event, 'intro', 'tablinks', 'home-section', ' w3-blue')">Home</button><br>

        <h5 class="w3-bar-item w3-black" style="margin-top: 0; margin-bottom: 0;">Wallet</h5>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'wallet-section', 'tablinks', 'home-section', ' w3-blue'); getAmount(<?= $_SESSION['id'] ?>)">Add to Wallet</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'view-products-section', 'tablinks', 'home-section', ' w3-blue'); getCats();">View Products</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'view-cart-section', 'tablinks', 'home-section', ' w3-blue');">View Cart <span class="w3-orange w3-round w3-text-white"><b><?php echo (count($_SESSION['orders']) > 0) ? count($_SESSION['orders']) : "" ?></b></span></button><br>

        <a class="w3-bar-item w3-button w3-hover-red tablinks" href="<?= base_url('/logout') ?>">Logout</a>
    </nav>

    <main style="width: 80%; float: right;">

        <?php if (isset($delete)) :  ?>
            <div class="w3-section w3-center w3-container w3-blue w3-display-container">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <p>Product Removed from Cart...</p>
            </div>
        <?php endif; ?>

        <?php if (isset($complete)) :  ?>
            <div class="w3-section w3-center w3-container w3-blue w3-display-container">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h1>Order Completed!</h1>
                <p>Thank you for shopping with us!</p>
            </div>
        <?php endif; ?>

        <section id="intro" class="home-section w3-animate-opacity">
            <h1>Home Page</h1>
            <p>Welcome back, <?php echo $_SESSION['name'] ?></p>
        </section>

        <!-- <div class="container">
            W3Docs
            <a href="https://www.w3docs.com/" target="_blank">
                <span class="link"></span>
            </a>
        </div> -->

        <section id="wallet-section" class="home-section w3-animate-opacity" style="display: none;">

            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="wallet-msg">
                <span onclick="this.parentElement.style.display='none'; $('#wallet').val('')" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>Funds have been added to your wallet...</p>
            </div>

            <h1>Add to Wallet</h1>

            <label for="wallet">Enter amount</label>
            <input type="text" class="w3-input" id="wallet">
            <p id="walletResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>

            <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3-section" onclick="addToWallet(<?= $_SESSION['id'] ?>); getAmount(<?= $_SESSION['id'] ?>)">Add to Wallet</button>

            <h1>Total Amount</h1>
            <p id="wallet-amount" class="w3-section">Amount: </p>

        </section>

        <section id="view-products-section" class="home-section w3-animate-opacity" style="display: none;">

            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="cart-msg">
                <span onclick="this.parentElement.style.display='none'; $('#wallet').val('')" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>Product has been added to your cart...</p>
            </div>

            <div class="w3-display-container w3-container w3-blue w3-section" style="display: none;" id="already-in-cart-msg">
                <span onclick="this.parentElement.style.display='none'; $('#wallet').val('')" class="w3-button w3-large w3-display-topright">&times;</span>
                <!-- <h3>Success</h3> -->
                <p>Product already added to Cart</p>
            </div>

            <h1>View Products</h1>

            <label for="category-list">Choose Category</label>
            <select name="" id="category-list" onchange="getSubs()"></select>

            <label for="sub-list">Choose Sub-Category</label>
            <select name="" id="sub-list" onchange="getProducts()"></select>

            <label for="product-list">Choose Products</label>
            <select name="" id="product-list"></select>

            <button onclick="addToCart()" class="w3-button w3-center w3-blue">Add to Cart</button>

            <p hidden id="product-result" class="w3-text-red"></p>

            <div class="w3-row-padding" id="product-images">
                <!-- LOAD PRODUCT IMAGES HERE -->
            </div>
        </section>

        <section id="view-cart-section" class="home-section w3-animate-opacity" style="display: none;">
            <h1>Cart</h1>
            <form method="POST" action="<?= base_url('Homepage/updateOrder') ?>" class="w3-center">
                <table>
                    <thead>
                        <th>Food</th>
                        <th>Quantity</th>
                        <th>Remove from Cart</th>
                    </thead>
                    <tbody>
                        <?php
                        $j = 1;

                        foreach ($_SESSION['orders'] as $key => $value) {

                        ?>
                            <tr>
                                <td><?php echo $value ?></td>
                                <td><input type="number" id="order-value" value="1" min="1" name="<?php echo "order" . $j++ ?>" /></td>
                                <td><button type="submit" name="delete-order" value="<?php echo $key ?>" class="w3-button w3-red">&times;</td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table><br>
                <input type="submit" value="Complete Order" class="w3-button w3-blue w3-hover-black" name="complete-order" />
            </form>
        </section>
    </main>

    <script>
        function addToCart() {
            var product = $('#product-list').val() ?? null

            $('#product-result').hide()
            $('#cart-msg').hide()
            $('#already-in-cart-msg').hide()

            if (product == null) {
                $('#product-result').show().text('* No Product Selected')
                return;
            }

            $.ajax({
                url: 'http://localhost:8080/Homepage/addToCart/' + product,
                success: function(result) {
                    if (result.message == 1)
                        $('#cart-msg').show()
                    else
                        $('#already-in-cart-msg').show()
                },
                error: function() {
                    $('#product-result').show().text('* Error in adding to Cart')
                }
            })
        }
    </script>

</body>

</html>