$(function() {
    checkCart()
})

function addToWallet(id) {
    var money = $('#wallet').val()

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
        success: function() {
            $('#wallet-msg').show()
        },
        error: function() {
            $('#walletResult').show().text('* Wallet Update failed. Try again!')
        }
    })

}

function showSection(event, section, menu, option, color) {    
    var i, tablinks;
    var x = document.getElementsByClassName(option);
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName(menu);
    for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(color, "");
    }
    document.getElementById(section).style.display = "block"; 
    event.currentTarget.className += color;     
}

function getCats() {
    $('#category-list').empty()

    $.ajax({
        url: 'http://localhost:8080/Homepage/getCategories',
        success: function(result) {
            $.each(result, function(x, i) {
                $('#category-list').append('<option value=' + i.category_id + '>' + i.category_name + '</option>')
            })
            getSubs();
        }
    })
}

function getSubs() {
    var cat_id = $('#category-list').val()

    $('#sub-list').empty()

    $.ajax({
        url: 'http://localhost:8080/Homepage/getSubs/' + cat_id,
        success: function(result) {
            $.each(result, function(x, i) {
                $('#sub-list').append('<option value=' + i.subcategory_id + '>' + i.subcategory_name + '</option>')
            })
            getProducts();
        }
    })
}

function getProducts() {
    var sub_id = $('#sub-list').val()

    $('#product-list').empty()
    $('#product-result').hide()

    $.ajax({
        url: 'http://localhost:8080/Homepage/getProducts/' + sub_id,
        success: function(result) {
            $.each(result, function(x, i) {
                $('#product-list').append('<option value=' + i.product_id + '>' + i.product_name + ' - ' + i.unit_price + '</option>')
            })

            if (result.length == 0) {
                $('#product-result').show().text('* No Products Found')
            }
        }
    })

}

function getProducts() {
    var sub_id = $('#sub-list').val()

    $('#product-list').empty()
    $('#product-images').empty()
    $('#product-result').hide()

    $.ajax({
        url: 'http://localhost:8080/Homepage/getProducts/' + sub_id,
        success: function(result) {
            $.each(result, function(x, i) {
                $('#product-list').append('<option value=' + i.product_id + '>' + i.product_name + ' - ' + i.unit_price + '</option>')
                if (i.product_image == null)
                    source = '';
                else
                    source = "http://localhost:8080/images/database/" + i.product_image;
                $('#product-images').append('<div class="w3-third w3-section"><div class="w3-card-4 w3-white w3-round" style="width: 100%;"><img src="' + source + '" alt=' + i.product_name + ' class="w3-round" style="width: 100%;" /><div class="w3-container w3-center"><h4><b>' + i.product_name + '</b></h4><p>Price: <b>' + i.unit_price + '</b></p></div></div></div>')
            })

            if (result.length == 0) {
                $('#product-result').show().text('* No Products Found')
            }
        }
    })

}

function getAmount(id) {
    $.ajax({
        url: 'http://localhost:8080/Homepage/getWallet/' + id,
        success: function(result) {
            if (result.amount == null)
                $('#wallet-amount').text("No money in wallet!")
            else
                $('#wallet-amount').text("Amount: " + result.amount)

        }
    })
}

function addToCart() {
    var product = $('#product-list').val() ?? null

    $('#product-result').hide()
    $('#cart-msg').hide()
    $('#already-in-cart-msg').hide()
    $('#cart-table').empty()

    if (product == null) {
        $('#product-result').show().text('* No Product Selected')
        return;
    }

    $.ajax({
        url: 'http://localhost:8080/Homepage/cart/' + product,
        success: function(result) {
            if (result.message == 1)
                $('#cart-msg').show()
            else
                $('#already-in-cart-msg').show()

            checkCart()
        },
        error: function() {
            $('#product-result').show().text('* Error in adding to Cart')
        }
    })
}

function orderHistory() {
    $('#history-table').empty()

    $.ajax({
        url: 'http://localhost:8080/Homepage/orderHistory',
        success: function(result) {
            $.each(result, function(x, i) {
                console.log(x, i)
                $('#history-table').append('<tr><td>' + i.order_id + '</td><td>' + i.order_amount + '</td><td>' + i.order_status + '</td><td>' + i.updated_at.slice(0, 10) + '</td><tr>')
            })
        }
    })
}

function checkCart() {
    $('#cart-table').empty()

    $.ajax({
        url: 'http://localhost:8080/Homepage/cart',
        success: function(result) {

            if (result.orders.length == 0) return;

            $.each(result.orders, function(x, i) {
                $('#cart-table').append('<tr><td>' + i + '</td><td><input type="number" id="order-value" value="1" min="1" name="order' + (x + 1) + '" /></td><td><button type="submit" name="delete-order" value="' + x + '" class="w3-button w3-red">&times;</button></td></tr>')
            })

            $('#cart-count').text(result.count)
        }
    })
}