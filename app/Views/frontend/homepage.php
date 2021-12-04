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
</head>

<body>
    <nav class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width: 20%; float: left;">
        <h5 class="w3-bar-item w3-black" style="margin-top: 0; margin-bottom: 0;">Users</h5>
        <button class="w3-bar-item w3-button tablinks w3-blue" onclick="showSection(event, 'intro', 'tablinks', 'home-section', ' w3-blue')">Home</button><br>

        <h5 class="w3-bar-item w3-black" style="margin-top: 0; margin-bottom: 0;">Wallet</h5>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'wallet-section', 'tablinks', 'home-section', ' w3-blue'); getAmount(<?= $_SESSION['id'] ?>)">Add to Wallet</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'view-products-section', 'tablinks', 'home-section', ' w3-blue'); getCats();">View Products</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'make-order-section', 'tablinks', 'home-section', ' w3-blue');">Make an Order</button><br>

        <a class="w3-bar-item w3-button w3-hover-red tablinks" href="<?= base_url('/logout') ?>">Logout</a>
    </nav>

    <main style="width: 80%; float: right;">
        <section id="intro" class="home-section w3-animate-opacity">
            <h1>Home Page</h1>
            <p>Welcome back, <?php echo $_SESSION['name'] ?></p>
        </section>

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
            <h1>View Products</h1>

            <label for="category-list">Choose Category</label>
            <select name="" id="category-list" onchange="getSubs()"></select>

            <label for="sub-list">Choose Sub-Category</label>
            <select name="" id="sub-list" onchange="getProducts()"></select>

            <label for="product-list">Choose Products</label>
            <select name="" id="product-list"></select>
            <p hidden id="product-result" class="w3-text-red"></p>

            <div class="w3-row-padding" id="product-images">
                <!-- LOAD PRODUCT IMAGES HERE -->
            </div>
        </section>

        <section id="make-order-section" class="home-section w3-animate-opacity" style="display: none;">
            <h1>Make Orders</h1>
        </section>
    </main>

    <script>

    </script>

</body>

</html>