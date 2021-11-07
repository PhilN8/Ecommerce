function registerUser() {
    var fname = $("#first-name").val();
    var lname = $("#last-name").val();
    var email = $('#emailaddress').val();
    var pass1 = $("#password1").val();
    var pass2 = $("#password2").val();
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

    $.ajax({
        url: 'http://localhost:8080/regCheck/' + email + '/' + fname + '/' + lname + '/' + pass1 + '/' + gender,
        success: function(result) {
            console.log(result, result.message)
            if (result.message == 'Registration Failed')
                $('#reg-failed-msg').show();
            else if (result.message == 'Not a valid email')
                $('#emailResult').show().text("* " + result.message)
            else if (result.message == 'Email already exists')
                $('#email-msg').show()
            else {
                window.location.href = "http://localhost:8080/homepage";
            }

        }
    })
}