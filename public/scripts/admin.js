$(function() {
    // $('#category-dropdown').empty()
    $.ajax({
        url: "http://localhost:8080/Admin/getCategories",
        success: function(result) {
            $.each(result, function(x, i) {
                console.log(i.category_id + " " + i.category_name)
                $('#categories-dropdown').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
                $('#category-dropdown').append('<option value="' + i.category_id + '" >' + i.category_name + '</option>');
            })
        }
    });

});

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
          console.log(result, result.message)
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
            console.log(result, result.message)
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

function loadTable(role) {
    $('#users').hide()
    $('#users-table').hide().empty()
    $(function() {
        $.ajax({
            url: "http://localhost:8080/Admin/viewUsers" + "/" + role,
            success: function(result) {
                $.each(result, function(x, i) {
                    $('#users').fadeIn()
                    $('#users-table').fadeIn().append('<tr><td>' + i.user_id + '</td><td>' + i.first_name + '</td><td>' + i.last_name + '</td><td>' + i.email + '</td></tr>');
                })
            }
        });
    });
}

function loadSubs() {
    var cat = $('#category-dropdown').val()
    $('#subcategory-dropdown').empty()

    $.ajax({
        url: "http://localhost:8080/Admin/getSubs/" + cat,
        success: function(result) {
            $.each(result, function(x, i) {
                $('#subcategory-dropdown').append('<option value="' + i.subcategory_id + '" >' + i.subcategory_name + '</option>');
            })
        }
    });
}