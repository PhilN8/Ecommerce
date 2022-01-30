$(function() {
    $.ajax({
        url: "http://localhost:8080/Admin/getCategories",
        success: function(result) {
            $.each(result, function(x, i) {
                $('#categories-dropdown').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
                $('#category-dropdown').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
                $('#all-categories').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
            })
        }
    });

    orders()
});

function getCategories() {
    $('#categories-dropdown').empty()
    $('#category-dropdown').empty()
    $('#all-categories').empty()


    $.ajax({
        url: "http://localhost:8080/Admin/getCategories",
        success: function(result) {
            $.each(result, function(x, i) {
                $('#categories-dropdown').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
                $('#category-dropdown').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
                $('#all-categories').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
            })
        }
    });
}

function showSection(event, section) {
    var i, x, tablinks
    x = document.getElementsByClassName('admin-section')
    for (i = 0; i < x.length; i++)
        x[i].style.display = 'none'

    tablinks = document.getElementsByClassName('tablinks')
    for (i = 0; i < tablinks.length; i++)
        tablinks[i].className = tablinks[i].className.replace(' w3-blue', "");

    document.getElementById(section).style.display = "block"
    event.currentTarget.className += " w3-blue"
}

function activeButton(event) {
    var x = document.getElementsByClassName('view-users')
    for (i = 0; i < x.length; i++)
        x[i].className = x[i].className.replace(' btn-active', '')

    event.currentTarget.className += ' btn-active'
}

function registerAdmin() {
  var fname = $("#first-name").val();
  var lname = $("#last-name").val();
  var email = $('#emailaddress').val();
  var pass1 = $("#password1").val();
  var pass2 = $("#password2").val();
  var gender = $('#genders').val();
  var role = 1;

  $("#fNameResult").hide();
  $("#lNameResult").hide();
  $("#emailResult").hide();
  $("#passResult").hide();
  $("#pass2Result").hide();
  $("#reg-failed-msg").hide();
  $("#email-msg").hide();
  $("#admin-msg").hide();

  if (fname == '' || lname == '' || email == '' || pass1 == '' || pass2 == '') {
      if (fname == '')
          $('#fNameResult').show().text("* First Name field is empty")
      if (lname == '')
          $('#lNameResult').show().text("* Last Name field is empty")
      if (email == '')
          $('#emailResult').show().text("* Email field is empty")
      if (pass1 == '')
          $('#passResult').show().text("* First Password field is empty")
      if (pass2 == '')
          $('#pass2Result').show().text("* Second Password field is empty")

      return;
  }

  if (pass1 != pass2) {
      $('#passResult').show().text("*")
      $('#pass2Result').show().text("* Passwords Don't Match")
      return;
  }

  $.ajax({
      url: 'http://localhost:8080/regCheck/' + email + '/' + fname + '/' + lname + '/' + pass1 + '/' + gender + '/' + role,
      success: function(result) {
          if (result.message == 'Not a valid email')
              $('#emailResult').show().text("* " + result.message)
          else if (result.message == 'Email already exists')
              $('#email-msg').show()
          else {
              $("#admin-msg").show();
          }

      },
      error: function() {
        $('#reg-failed-msg').show();

      }
  })
}

function newCategory() {
    var cat = $('#category-val').val().trim()
    cat = cat.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase()
    });
    $('#categoryResult').hide()
    $('#category-msg').hide()

    $.ajax({
        url: 'http://localhost:8080/newCategory/' + cat,
        success: function(result) {
            if (result.message == 1)
                $('#categoryResult').show().text("* Category already exists");
            else if (result.message == 2)
                $('#categoryResult').show().text("* Error: Addition failed...");
            else
                $('#category-msg').show();
           
        },
        error: function() {
            $('#categoryResult').show().text("* Error: Addition failed...");
        }
    });
}

function newSub() {
    var cat_id = $('#categories-dropdown').val()
    var sub = $('#subcategory').val().trim()
    sub = sub.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase()
    });

    $('#subcategoryResult').hide()
    $('#subcategory-msg').hide();


    $.ajax({
        url: 'http://localhost:8080/subcategory/' + sub + '/' + cat_id,
        success: function(result) {
            console.log(result, result.message)
            if (result.message == 1)
                $('#subcategoryResult').show().text("* Category already exists");
            else if (result.message == 2)
                $('#subcategoryResult').show().text("* Error: Addition failed...");
            else
                $('#subcategory-msg').show();
        },
        error: function() {
            $('#subcategoryResult').show().text("* Error: Addition failed...");
        }
    })
}

function loadTable(role, place) {
    $('#all-users').empty()
    $('#users').hide()
    $('#users-table').hide().empty()

    $(function() {
        $.ajax({
            url: `http://localhost:8080/Admin/viewUsers/${role}`,
            success: function(result) {
                $.each(result, function(x, i) {
                    if (place == 0){
                        $('#users').fadeIn()
                        $('#users-table').fadeIn().append('<tr><td>' + i.user_id + '</td><td>' + i.first_name + '</td><td>' + i.last_name + '</td><td>' + i.email + '</td></tr>');
                    } else {
                        $('#all-users').append('<option value="'+ i.user_id +'">' + i.user_id + " - " + i.first_name + " " + i.last_name + "</option>")
                    }
                })
            }
        });
    });
}

function loadSubs() {
    var cat = $('#category-dropdown').val()
    $('#subcategory-dropdown').empty()

    if (cat == '') return;

    $.ajax({
        url: "http://localhost:8080/Admin/getSubs/" + cat,
        success: function(result) {
            $.each(result, function(x, i) {
                $('#subcategory-dropdown').append('<option value="' + i.subcategory_id + '">' + i.subcategory_name + '</option>');
            })
        }
    });
}

function changeOption() {
    var option = $('#edit-option').val()

    if (option == 'gender') {
        $('#gender-option').prop('disabled', false);
        $('#new-val').prop('disabled', true);
    } else {
        $('#gender-option').prop('disabled', true);
        $('#new-val').prop('disabled', false);
    }
}

function newProduct() {
    var product = $('#product-name').val();
    var subcategory_id = $('#subcategory-dropdown').val()
    var desc = $('#product-desc').val()
    var price = parseFloat($('#price').val()).toFixed(2)

    $('#product-msg').hide()
    $('#prodResult').hide()

    $.ajax({
        url: 'http://localhost:8080/newProduct/' + product + '/' + desc + '/' + subcategory_id + '/' + price,
        success: function(result) {
            if (result.message == 1)
                $('#prodResult').show().text("* Product already exists");
            else if (result.message == 2)
                $('#prodResult').show().text("* Error: Addition failed...");
            else
                $('#product-msg').show();
        },
        error: function() {
            $('#prodResult').show().text("* Error: Addition failed...");
        }
    });
}

function editUser() {
    var id = $('#edit-user').val()
    var option = $('#edit-option').val()
    var new_value = ''

    $('#edit-fail').hide()
    $('#edit-msg').hide()
    $('#valResult').hide()

    if (option == 'gender')
        new_value = $('#gender-option').val()
    else
        new_value = $('#new-val').val()

    $.ajax({
        url: 'http://localhost:8080/Admin/editUser/' + option + '/' + id + '/' + new_value,
        success: function(result) {
            if (result.message == 2)
                $('#edit-fail').show()
            else if (result.message == 3)
                $('#valResult').show().text("* Not a valid email")
            else if (result.message == 4)
                $('#valResult').show().text("* Email already in use...")
            else
                $('#edit-msg').show()
        },
        error: function() {
            $('#edit-fail').show()
        }
    })
}

function dynamicSearch(){
    var searchTerm = $('#search').val();

    if (searchTerm == '') return;

    var xhttp = new XMLHttpRequest();
    var url = 'ajaxdynamic.php?search_val='+searchTerm;

    // Get response from server, and process it
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var result = this.responseText;
            var resultText = JSON.parse(result);
            $("#searchresult").text(resultText);
        }
    };

    xhttp.open('GET', url, true);
    xhttp.send();

}

function checkname() {
    var searchTerm = $("#edit-user").val()
    $('#user-result').hide()

    if (searchTerm == '') {
        loadTable(0, 1)
        return;
    }

    $.ajax({
        url: 'http://localhost:8080/Admin/dynamicSearch/' + searchTerm,
        success: function(result) {
            $('#all-users').empty()
            if (result.message.length == 0) {
                $('#user-result').show().text("User not Found!")
                loadTable(0, 1)
                return;
            }

            $.each(result.message, function(x, i) {
                $('#all-users').append('<option value="' + i.user_id + '">' + i.first_name + " " + i.last_name + "</option>")
            })
        },
        error: function(result) {
            console.error(result);
        }
    })
}

function newPayment() {
    var payment = $('#payment').val().trim()
    payment = payment.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase()
    });
    var description = $('#payment-description').val()

    $('#payment-result').hide()
    $('#payment-success').hide()

    $.ajax({
        url: 'http://localhost:8080/newPayment/' + payment + '/' + description,
        success: function(result) {
            console.log(result, result.message)
            if (result.message == 1)
                $('#payment-success').show()
            else
                $('#payment-result').show().text('* Payment Type already exists')
        },
        error: function() {
            $('#payment-result').show().text('* Addition failed')
        }
    })
}

function orders() {
    $('#order-table').empty()

    $.ajax({
        url: 'http://localhost:8080/Admin/orders',
        success: function(result) {
            $.each(result, function(x, i) {
                i.order_status = i.order_status.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase()
                });
                if(i.order_status == 'Pending')
                    $('#order-table').append('<tr><td>' + i.order_id + '</td><td>' + i.order_amount + '</td><td>' + i.order_status + '</td><td>' + i.created_at.slice(0, 10) + '</td><td><button class="w3-button w3-amber" onclick="updateOrder(' + i.order_id + ')">Update</button></td><tr>')
                else
                    $('#order-table').append('<tr><td>' + i.order_id + '</td><td>' + i.order_amount + '</td><td>' + i.order_status + '</td><td>' + i.created_at.slice(0, 10) + '</td><td></td><tr>')
            })
        }
    })
}

function updateOrder(id) {
    $('#order-update').hide()
    $('#order-fail').hide()

    $.ajax({
        url: 'http://localhost:8080/Admin/updateOrder/' + id,
        success: function(result){
            $('#order-update').show()
            $('#order-msg').val('Order of ID' + id + ' Updated')
            orders()
        },
        error: function() {
            $('#order-fail').show()
            $('#order-fail-msg').val('Order of ID' + id + ' Not Updated')
        }
    })
}

function editCategory() {
    var name = $('#new-category').val().trim()
    var id = $('#all-categories').val()

    name = name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase()
    });

    $('#new-category-result').hide()
    $('#category-update').hide()
    $('#category-fail').hide()

    if (name == '') return;

    $.ajax({
        url: `http://localhost:8080/Admin/updateCategory/${id}/${name}`,
        success: function(result){
            $('#category-update').show()
            getCategories()
            $('#new-category').val('')
        },
        error: function(result) {
            console.log(result)
            $('#category-fail').show()
        }
    })

}