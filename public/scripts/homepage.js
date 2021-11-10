function addToWallet() {
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

}