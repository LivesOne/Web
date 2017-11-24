$(function() {
    var $account = $(".account");
    // const ACCOUNTTWO = 186660;
    // const ACCOUNTTHO = 481380;
    // const ACCOUNTTFO = 1030380;

    $(".btn-qh").click(function(event) {
        /* Act on the event */
        $(".mask").show();
    });
    $(".mask").on('click', '.dialog-close', function(event) {
        event.preventDefault();
        /* Act on the event */
        $(this).closest('.mask').hide();
    });
    $(".dialog-content").on('click', 'li', function(event) {
        event.preventDefault();
        /* Act on the event */
        $(".btn-qh").text($(this).find(".qh").text());
        $("#area_code").val($(this).find(".qh").text());
        $(this).closest('.mask').hide();
    });
    $("#mobile, .input-code").focus(function(event) {
        /* Act on the event */
        $(this).closest('.input-group').addClass('focus');
    });
    $("#mobile, .input-code").blur(function(event) {
        /* Act on the event */
        $(this).closest('.input-group').removeClass('focus');
    });
    $(".sel-icon").click(function(event) {
        /* Act on the event */
        $(this).toggleClass('active');
        $(this).hasClass('active') ? $(".btn-sure").attr('disabled', false) : $(".btn-sure").attr('disabled', true);
    });
    $(".input-amount").keyup(function(event) {
        /* Act on the event */
        var amountVal = $(this).val() || 0;
        var account = 0;
        if (amountVal >=20 && amountVal <50) {
            //account = ACCOUNTTWO + (amountVal - 20) * 9824;
            account = amountVal *  7368;
        } else if (amountVal >= 50 && amountVal < 100) {
            // account = ACCOUNTTHO + (amountVal - 50) * 10980;
            account = amountVal *  8235;
        } else if (amountVal >= 100 && amountVal <= 500) {
            // account = ACCOUNTTFO + (amountVal - 100) * 12444;
            account = amountVal *  9333;
        }
        $account.text(account);
    });
    $(".rg-email, .input-code").keydown(function(event) {
        /* Act on the event */
        if (event.which === 13) {
            $(".btn-signup, .btn-log").attr('disabled', false);
        }
    });

    $(".btn-signup").click(function(event) {
        /* Act on the event */
        $("#real_name, #rg-email").trigger('blur');
    });
})