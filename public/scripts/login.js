function checkEmail() {
    var email = $('#emailaddress').val();
    var pass = $("#password1").val();

    $("#invalid-msg").hide()
    $("#no-record-msg").hide()
    $("#emailResult").hide();
    $("#passResult").hide();

    if (email == '') {
        $("#emailResult").show().text("* Email field is empty");
        return;
    };

    if (pass == '') {
        $("#passResult").show().text("* Password field is empty");
        return;
    };

    var test_url = 'http://localhost:8080/loginCheck/' + email + '/' + pass;

    $.ajax({
        url: test_url,
        success: function(result) {
            if (result.message == 'Invalid Credentials') {
                $("#invalid-msg").show()
            } else if (result.message == 'Not a valid email') {
                $("#emailResult").show().text("* " + result.message);
            } else if (result.message == 'No such Record') {
                $("#no-record-msg").show()
            } else {
                if (result.role == 2)
                    window.location.href = "http://localhost:8080/homepage";
                else
                    window.location.href = "http://localhost:8080/admin";
            }
            },
        error: function() {
            $("#emailResult").show().text("* Login Authentication failed...");
        }
    });
}