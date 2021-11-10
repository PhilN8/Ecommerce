<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?= base_url('/scripts/homepage.js') ?>"></script>
</head>

<body>
    <nav>
        <button class="w3-button">Add to Wallet</button>
    </nav>
    <main>
        <section id="intro" class="home-section w3-animate-opacity">
            <h1>Home Page</h1>
            <p>Welcome back, <?php echo $_SESSION['name'] ?></p>
            <p><a href="<?= base_url('/logout') ?>">Logout</a></p>
        </section>

        <section id="wallet-section" class="home-section w3-animate-opacity">

            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="wallet-msg">
                <span onclick="this.parentElement.style.display='none'; document.getElementById('editForm').reset()" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>Funds have been added to your wallet...</p>
            </div>

            <h1>Add to Wallet</h1>

            <label for="wallet">Enter amount</label>
            <input type="text" class="w3-input" id="wallet">
            <p id="walletResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>

            <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3-section" onclick="addToWallet()">Add to Wallet</button>
        </section>
    </main>

    <script>
        function addToWallet() {
            var money = $('#wallet').val()
            var id = <?= $_SESSION['id'] ?>;


            $('#walletResult').hide()

            if (isNaN(money)) {
                $('#walletResult').show().text('* Not a valid number')
                $('#wallet').val('')
                return;
            }

            if (money < 100) {
                $('#walletResult').show().text('* Min Amount is Ksh. 100')
                $('#wallet').val('')
                return;
            }

            $.ajax({
                url: 'http://localhost:8080/wallet/' + id + '/' + money,
                success: function(result) {
                    $('#wallet-msg').show()
                },
                error: function() {
                    $('#walletResult').show().text('* Wallet Update failed. Try again!')
                }
            })

        }
    </script>

</body>

</html>