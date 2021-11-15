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