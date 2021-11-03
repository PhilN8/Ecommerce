<?php
ini_set('display_errors', '1');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Admin Page</title>
    <script src="<?= base_url('scripts/admin.js') ?>"></script>
    <style>
        th,
        td {
            text-align: center;
            padding: 15px;
        }

        th {
            border: solid black;
        }

        table {
            background-color: #fff;
        }

        .user-table {
            margin-left: auto;
            margin-right: auto;
        }

        .user-table th {
            background-color: black;
            color: white;
        }
    </style>
</head>

<body style="background-color: #eee;">

    <div class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width: 20%; float: left;">
        <h5 class="w3-bar-item w3-black" style="margin-top: 0; margin-bottom: 0;">Users</h5>
        <button class="w3-bar-item w3-button tablinks w3-blue" onclick="showSection(event, 'intro', 'tablinks', 'admin-section', ' w3-blue')">Home</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'new-admin-section', 'tablinks', 'admin-section', ' w3-blue')">Add an Admin</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'view-users-section', 'tablinks', 'admin-section', ' w3-blue'); loadTable(0)">View Users</button><br>

        <h5 class="w3-bar-item w3-black" style="margin-bottom: 0;">Clothes</h5>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'category-section', 'tablinks', 'admin-section', ' w3-blue')">Add New Category</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'sub-category-section', 'tablinks', 'admin-section', ' w3-blue')">Add New Sub-Category</button>
        <button class="w3-bar-item w3-button tablinks" onclick="showSection(event, 'product-section', 'tablinks', 'admin-section', ' w3-blue'); loadSubs()">Add Product</button><br>

        <a class="w3-bar-item w3-button w3-hover-red tablinks" href="<?= base_url('/logout') ?>">Logout</a>

    </div>

    <main style="width: 80%; float: right;">
        <section id="intro" class="admin-section w3-animate-opacity" style="width: 80%; margin: auto;">
            <h1>Admin Page</h1>
            <p>Welcome back, <?= $_SESSION['name'] ?></p>
        </section>

        <section id="new-admin-section" class="admin-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <!-- <h1>Register a New Admin</h1> -->

            <div class="w3-display-container w3-container w3-red w3-section" style="display: none;" id="reg-failed-msg">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Registration Failed</h3>
                <p>Try again...</p>
            </div>

            <div class="w3-display-container w3-container w3-red w3-section" style="display: none;" id="email-msg">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Registration Failed</h3>
                <p>Email already exists...</p>
            </div>

            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="admin-msg">
                <span onclick="this.parentElement.style.display='none'; document.getElementById('regForm').reset()" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>New Admin Registered...</p>
            </div>

            <div class="w3-card-4 w3-section w3-animate-opacity">

                <div class="w3-container w3-blue">
                    <h1>Register a New Admin</h1>
                    <!-- <p style="background-color: #fff;">Sign up to enjoy our delicious food!</p> -->
                </div>

                <form class="w3-container" method="POST" action="<?= base_url('/Registration/registerUser') ?>" id="regForm">
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
                <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3-section" onclick="registerAdmin()">Complete</button>
                <button class="w3-button w3-center w3-margin-right w3-red w3-hover-black w3-section w3-right" onclick="document.getElementById('regForm').reset()">Cancel</button>
            </div>
        </section>

        <section id="view-users-section" class="admin-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <h1 style="text-align: center; clear: right;">Users in the Database</h1>
            <button class="w3-button" onclick="loadTable(1)">Admins</button>
            <button class="w3-button" onclick="loadTable(2)">Users</button>
            <button class="w3-button" onclick="loadTable(0)">All</button>
            <table class="user-table" id="users">
                <thead>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </thead>
                <tbody id="users-table" class="w3-animate-opacity">
                </tbody>
            </table>
        </section>

        <section id="category-section" class="admin-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <div class="w3-display-container w3-container w3-green w3-section" style="display: none;" id="category-msg">
                <span onclick="this.parentElement.style.display='none'; $('#category-val').val('')" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>New Category Added...</p>
            </div>

            <h1>Category</h1>
            <label for="category-val">Enter a new category name:</label>
            <input type="text" id="category-val" class="w3-input">
            <p id="categoryResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>
            <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3-section" onclick="newCategory()">Complete</button>

        </section>

        <section id="sub-category-section" class="admin-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <div class="w3-display-container w3-container w3-green w3-section w3-animate-opacity" style="display: none;" id="subcategory-msg">
                <span onclick="this.parentElement.style.display='none'; $('#category-val').val('')" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Success</h3>
                <p>New Sub-Category Added...</p>
            </div>

            <h1>Sub-Category</h1>
            <label for="categories-dropdown">Choose category</label>
            <select name="" id="categories-dropdown" class="w3-input">
                <!-- <option value="">Choose an option</option> -->
            </select><br>

            <label for="subcategory">Enter a new sub-category:</label>
            <input type="text" id="subcategory" class="w3-input">
            <p id="subcategoryResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>
            <button class="w3-button w3-center w3-margin-left w3-teal w3-hover-black w3- w3-animate-opacity" onclick="newSub()">Complete</button>
        </section>

        <section id="product-section" class="admin-section w3-animate-opacity" style="width: 80%; margin: auto; display: none;">
            <h1>Add Product</h1>
            <form enctype="multipart/form-data" class="w3-container w3-section">
                <br>
                <label for="category-dropdown">Choose Category</label>
                <select name="" id="category-dropdown" class="w3-input" onchange="loadSubs()">
                    <!-- <option value="">Choose an option</option> -->
                </select><br>
                <!-- <input class="w3-input" type="text" name="fname" id="first-name"> -->
                <!-- <p id="fNameResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br> -->

                <label for="subcategory-dropdown">Pick a Sub-Category</label>
                <select name="" id="subcategory-dropdown" class="w3-input">
                    <!-- <option value="">Choose an option</option> -->
                </select><br>


                <label for="product-name">Product Name:</label>
                <input class="w3-input" type="text" name="productname" id="product-name">
                <p id="prodResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="product-desc">Brief Desc:</label>
                <input class="w3-input" type="email" id="product-desc" name="productdesc">
                <p id="descResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="price">Set Password</label>
                <input class="w3-input" type="password" id="price" name="unitprice">
                <p id="priceResult" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="password2">Confirm Password</label>
                <input class="w3-input" type="password" id="password2" name="pword2">
                <p id="pass2Result" class="w3-margin-bottom w3-text-red" hidden style="margin-top: 0;"></p><br>


                <label for="genders">Gender</label>
                <select id="genders" name="gender" class="w3-input">
                    <option value="male" selected>Male</option>
                    <option value="female">Female</option>
                </select> -->
            </form>
        </section>

        <script>
            // function loadTable(role) {
            //     $('#users').hide()
            //     $('#users-table').hide().empty()
            //     $(function() {
            //         $.ajax({
            //             url: "http://localhost:8080/Admin/viewUsers" + "/" + role,
            //             success: function(result) {
            //                 $.each(result, function(x, i) {
            //                     $('#users').fadeIn()
            //                     $('#users-table').fadeIn().append('<tr><td>' + i.user_id + '</td><td>' + i.first_name + '</td><td>' + i.last_name + '</td><td>' + i.email + '</td></tr>');
            //                 })
            //             }
            //         });
            //     });
            // }
        </script>

    </main>
</body>

</html>