$('#regForm').submit(function(event) {
    event.preventDefault();

    var fname = $("#first-name").val().trim();
    var lname = $("#last-name").val().trim();
    var email = $('#emailaddress').val().trim();
    var pass1 = $("#password1").val().trim();
    var pass2 = $("#password2").val().trim();
    var gender = $('#genders').val();

    $("#fNameResult").hide();
    $("#lNameResult").hide();
    $("#emailResult").hide();
    $("#passResult").hide();
    $("#pass2Result").hide();
    $("#reg-failed-msg").hide();
    $("#email-msg").hide();

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

    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/

    if (!email.match(mailformat)) {
        $("#emailResult").show().text("* Enter a valid email address: example@gmail.com");
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'http://localhost:8080/Registration/register',
        data: {
            'first-name': fname,
            'last-name': lname,
            'email': email,
            'password1': pass1,
            'gender': gender
        },
        success: function(result) {
            if (result.message == 'Email already exists')
                $('#email-msg').show()
            else if (result.message == 'Registration Failed')
                $('#reg-failed-msg').show()
            else
                window.location.href = "http://localhost:8080/homepage"
        },
        error: function() {
            $('#reg-failed-msg').show()

        }
    })
})