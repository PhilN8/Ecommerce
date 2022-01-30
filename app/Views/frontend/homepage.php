<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page | Soko la Njue</title>
    <link rel="stylesheet" href="<?= base_url('/css/w3.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/css/homepage.css') ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
    <script src="<?= base_url('/scripts/homepage.js') ?>"></script>
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
                <li><button onclick="openSection('wallet-section')">Wallet</button></li>
                <li><button onclick="openSection('view-products-section')">Shop</button></li>
                <li><button onclick="openSection('view-cart-section')">Cart</button></li>
                <li><button onclick="openSection('pay-order-section')">Pay</button></li>
                <li><button onclick="openSection('history-section')">History</button></li>
                <li><a href="<?= base_url('/logout') ?>">Logout</a></li>
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

    <main style="">

        <?php if (isset($delete)) :  ?>
            <div class="w3-section w3-center w3-container w3-blue w3-display-container" style="width: 80%; margin: auto">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h1>Removed</h1>
                <p>Product Removed from Cart...</p>
            </div>
        <?php endif; ?>

        <?php if (isset($complete)) :  ?>
            <div class="w3-section w3-center w3-container w3-green w3-display-container" style="width: 80%; margin: auto;">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h1>Order Completed!</h1>
                <p>Thank you for shopping with us!</p>
            </div>
        <?php endif; ?>

        <?php if (isset($check) and isset($count)) :  ?>
            <div class="w3-section w3-center w3-container w3-yellow w3-display-container" style="width: 80%; margin: auto;">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h1>Incomplete Orders</h1>
                <p>The following items are out of stock:</p>
                <?php for($i = 0; $i < $count; $i++) {
                    echo '<p>'. $check[$i]. ' </p>';
                 } ?>
            </div>
        <?php endif; ?>

        <?php if (isset($incomplete) && isset($validation)) :  ?>
            <div class="w3-section w3-center w3-container w3-amber w3-display-container" style="width: 80%; margin: auto;">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h2>No Payment Type</h2>
                <p><?= $validation->listErrors() ?></p>
            </div>
        <?php endif; ?>

        <section id="intro" class="home-section w3-animate-opacity" style="width: 80%; margin: auto;">
            <h1>Home Page</h1>
            <p>Welcome back, <?= $_SESSION['name'] ?></p>
        </section>

        <section id="wallet-section" class="home-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">

            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="wallet-msg">
                <span onclick="this.parentElement.style.display='none'; $('#wallet').val('')" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>Funds have been added to your wallet...</p>
            </div>

            <h1 class="section_titles">Add to Wallet</h1>

            <label for="wallet">Enter amount</label>
            <input type="text" class="w3-input" id="wallet">
            <p id="walletResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>

            <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3-section" onclick="addToWallet(<?= $_SESSION['id'] ?>); getAmount(<?= $_SESSION['id'] ?>)">Add to Wallet</button>

            <h1>Total Amount</h1>
            <p id="wallet-amount" class="w3-section">Amount: </p>

        </section>

        <section id="view-products-section" class="home-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">

            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="cart-msg">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>Product has been added to your cart...</p>
            </div>

            <div class="w3-display-container w3-container w3-blue w3-section" style="display: none;" id="already-in-cart-msg">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <!-- <h3>Success</h3> -->
                <p>Product already added to Cart</p>
            </div>

            <h1 class="section_titles">View Products</h1>

            <label for="category-list">Choose Category</label>
            <select name="" id="category-list" onchange="getSubs()"></select>

            <label for="sub-list">Choose Sub-Category</label>
            <select name="" id="sub-list" onchange="getProducts()"></select>

            <p hidden id="product-result" class="w3-text-red"></p>


            <div class="small-container" id="product-images">
                <!-- LOAD PRODUCT IMAGES HERE -->
            </div>
        </section>

        <section id="view-cart-section" class="home-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <h1 class="section_titles">Cart</h1>
            <form method="POST" action="<?= base_url('Homepage/updateOrder') ?>" class="w3-center">
                <table>
                    <thead>
                        <tr>
                            <th>Clothes</th>
                            <th>Quantity</th>
                            <th>Remove from Cart</th>
                        </tr>
                    </thead>
                    <tbody id="cart-table">

                    </tbody>
                </table><br />

                <label for="payment-type">Choose Payment Type:</label>
                <select id="payment-type" class="w3-input" name="pay-type">
                    <option></option>
                </select><br />

                <input type="submit" value="Complete Order" class="w3-button w3-blue w3-hover-black" name="complete-order" />
            </form>
        </section>

        <section id="pay-order-section" class="home-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">

            <div class="w3-display-container w3-container w3-green w3-section" style="width: 80%; margin: auto; display: none;" id="pay-msg">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p id="pay-order-msg"></p>
            </div>

            <div class="w3-display-container w3-container w3-red w3-section" style="width: 80%; margin: auto; display: none;" id="no-money">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Failure</h3>
                <p>Amount in your wallet <span id="wallet-amt"></span> cannot cover the payment of this Order</p>
            </div>

            <div class="w3-display-container w3-container w3-red w3-section" style="width: 80%; margin: auto; display: none;" id="pay-fail">
                <span onclick="this.parentElement.style.display='none';" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Payment Failed</h3>
                <p>Try again later...</p>
            </div>

            <h1 class="section_titles">Pay for Current Orders</h1>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="order-table"></tbody>
            </table>
        </section>

        <section id="history-section" class="home-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <h1  class="section_titles">History of Orders</h1>

            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="history-table"></tbody>
            </table>

        </section>
    </main>

    <script>

    </script>

</body>

</html>