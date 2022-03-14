$(function() {
    checkCart()
    orderHistory()
    getCats()
    paymentTypes()
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

function openSection(section) {
    var x;
    x = document.getElementsByClassName('home-section')
    for (i = 0; i < x.length; i++)
        x[i].style.display = 'none'

    document.getElementById(section).style.display = "block"
    document.getElementById('nav-toggle').checked = false
}

function showSection(event, section) {
    var i, x, tablinks
    x = document.getElementsByClassName('home-section')
    for (i = 0; i < x.length; i++)
        x[i].style.display = 'none'

    tablinks = document.getElementsByClassName('tablinks')
    for (i = 0; i < x.length; i++)
        tablinks[i].className = tablinks[i].className.replace(' w3-blue', "");

    document.getElementById(section).style.display = "block"
    event.currentTarget.className += " w3-blue"
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
    $('#product-images').empty()
    $('#product-result').hide()

    $.ajax({
        url: 'http://localhost:8080/Homepage/getProducts/' + sub_id,
        success: function(result) {
            $.each(result, function(x, i) {
                $('#product-list').append(`<option value='${i.product_id}'> ${i.product_name} -  ${i.unit_price}</option>`)
                if (i.product_image == null)
                    source = '';
                else
                    source = "http://localhost:8080/images/database/" + i.product_image;
                // $('#product-images').append('<div class="w3-third w3-section"><div class="w3-card-4 w3-white w3-round"><img src="' + source + '" alt=' + i.product_name + ' class="product_images" /><div class="w3-container w3-center"><h4><b>' + i.product_name + '</b></h4><p>Price: <b>' + i.unit_price + '</b></p><button onclick="addToCart(' + i.product_id +')" class="w3-button w3-center"><i class="bi bi-cart-plus-fill"></i></button><br/></div></div></div>')
                $('#product-images').append(`<div class="col-3"><div class="w3-card-4 w3-white w3-round"><img src="${source}" alt=${i.product_name} class="product_images" /><div class="w3-container w3-center"><h4><b>${i.product_name}</b></h4><p>Price: <b>${i.unit_price}</b></p><button onclick="addToCart(${i.product_id})" class="w3-button w3-center"><i class="bi bi-cart-plus-fill"></i></button><br/></div></div></div>`)
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
            if (result.amount == null) {
                $('#wallet-amt').text('Ksh. 0')
                $('#wallet-amount').text("No money in wallet!")
            } else {
                $('#wallet-amt').text('Ksh. ' + result.amount)
                $('#wallet-amount').text("Amount: " + result.amount)
            }

        }
    })
}

function addToCart(product) {
    $('#product-result').hide()
    $('#cart-msg').hide()
    $('#already-in-cart-msg').hide()
    $('#cart-table').empty()

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
    $('#order-table').empty()

    $.ajax({
        url: 'http://localhost:8080/Homepage/orderHistory',
        success: function(result) {
            $.each(result, function(x, i) {
                i.order_status = i.order_status.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase()
                });
                $('#history-table').append('<tr><td>' + i.order_id + '</td><td>' + i.order_amount + '</td><td>' + i.order_status + '</td><td>' + i.created_at.slice(0, 10) + '</td><td><a target="_blank" href="http://localhost:8080/receipt/' + i.order_id + '" class="w3-button w3-teal">Receipt</a></td><tr>')
                if(i.order_status == 'Pending Payment')
                    $('#order-table').append('<tr><td>' + i.order_id + '</td><td>' + i.order_amount + '</td><td>' + i.order_status + '</td><td>' + i.created_at.slice(0, 10) + '</td><td><button class="w3-aqua w3-button" onclick="payOrder(' + i.order_id + ',' + i.order_amount + ')">Pay</button></td><tr>')
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

function payOrder(id, total) {
    $('#pay-msg').hide()
    $('#no-money').hide()
    $('#pay-fail').hide()
    $('#pay-order-msg').text('')

    $.ajax({
        url: 'http://localhost:8080/Homepage/payOrder/' + id + '/' + total,
        success: function(result) {
            if (result.message == 2) {
                $('#pay-msg').show()
                $('#pay-order-msg').text('Order No. ' + id + ' has been paid!')
                orderHistory()
            } else
                $('#no-money').show()
        },
        error: function() {
            $('#pay-fail').hide()
        }
    })
}

function paymentTypes() {
    $.ajax({
        url: 'http://localhost:8080/Homepage/getPaymentTypes',
        success: function(result) {
            $.each(result, function(x, i) {
                $('#payment-type').append('<option value="' + i.paymenttype_id +'">' + i.paymenttype_name + '</option>')
            })
        }
    })
}