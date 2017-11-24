$(function() {
    document.onreadystatechange = function() {
        if (document.readyState == 'complete') {
            $("#loadingdiv").hide();
        }
    }
    var $account = $(".ys_account");
    var $raisingMoney = $(".raising_money");
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
        $("#area_code").val($(this).find(".qh").text().replace(/\+/g,''));
        $(this).closest('.mask').hide();
    });
    $("#mobile, .input-code, .vcode-input, .input-pass, .input-pass-again").focus(function(event) {
        /* Act on the event */
        $(this).closest('.input-group').addClass('focus');
    });
    $("#mobile, .input-code, .vcode-input, .input-pass, .input-pass-again").blur(function(event) {
        /* Act on the event */
        $(this).closest('.input-group').removeClass('focus');
    });

    $(".vcode-input").blur(function(event) {
        /* Act on the event */
        var e = $(this);
        if ($.trim(e.val()) == "") {
            e.closest(".form-group").removeClass("check-success");
            e.closest(".form-group").addClass("check-error");
            e.closest(".field").find(".input-help").remove();
            e.closest(".field").append('<div class="input-help"><ul>' + e.attr('errortxt') + "</ul></div>")
        } else if($.trim(e.val()).length != '4') {
            e.closest(".form-group").removeClass("check-success");
            e.closest(".form-group").addClass("check-error");
            e.closest(".field").find(".input-help").remove();
            e.closest(".field").append('<div class="input-help"><ul>' + e.attr('errormsg') + "</ul></div>")
        } else {
            e.closest(".form-group").removeClass("check-error");
            e.closest(".field").find(".input-help").remove();
            e.closest(".form-group").addClass("check-success")
        }
    });

    function browser() {
        var u = navigator.userAgent.toLowerCase();
        var app = navigator.appVersion.toLowerCase();
        return {
            ios: !!u.match(/\(i[^;]+;( u;)? cpu.+mac os x/), //ios终端
            android: u.indexOf('android') > -1, //android终端
            iPad: u.indexOf('ipad') > -1, //是否iPad
            weixin: /MicroMessenger/gi.test(u) //微信
        };
    }

    var bver = browser();
    if (bver.ios || bver.android || bver.iPad) {
        // var leftHeight = $(".layout_left").height() + 50;
        // if (location.pathname.toLocaleLowerCase() != '/usercenter/index') {
        //     $('html,body').animate({scrollTop: leftHeight}, 500);
        //     $("body").append('<div class="p-back-top"><img src="/Public/static/assets/images/down.png" alt="" />Top</div>');
        //     $("body").on('click', '.p-back-top', function(event) {
        //         event.preventDefault();
        //          Act on the event 
        //         $('html,body').animate({scrollTop: 0}, 500);
        //         //$(this).hide();
        //     });
        //     $(window).on('scroll', function(e) {
        //         var scrollTop = $(this).scrollTop();
        //         if (scrollTop > $(".layout_left").height()) {
        //             $('.p-back-top').fadeIn();
        //         } else {
        //             $('.p-back-top').fadeOut();
        //         }
        //     })
        // }
        $(".layout_left_menu").show();
        $(".layout_left_con").hide();
        $(".layout_left").css({
            position: 'absolute',
            top: '0px',
            left: '0px',
            zIndex: '99',
            color: '#333',
            borderRight: '0 none',
            borderBottom: '1px solid #ebebeb',
            width: '100%',
            background: '#fff',
            height: 'auto',
            minHeight: 'auto'
        });
        $(".layout_left h2").css('marginTop', '20px');
        $(".layout_left_menu").click(function(event) {
            /* Act on the event */
            $(".layout_left_con").slideToggle(400);
        });
        // $(".help").css({
        //     position: 'relative',
        //     top: '0px',
        //     left: '20px',
        //     display: 'inline-block'
        // });
    } else {
        var rightHeight = $(".layout_right").height() + 30;
        rightHeight = parseInt(rightHeight) > 400 ? rightHeight : 400;
        $(".layout_left").height(rightHeight);
    }

    //帮助
    $(".help").on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        $(".tips-con").toggle();
    });

    //帮助中心
    $(".menu").on('click', '.menu_list', function(event) {
        event.preventDefault();
        /* Act on the event */
        $(this).parent('li').siblings('li').find('i').removeClass('upward').addClass('downward');
        $(this).children('i').removeClass('downward').addClass('upward');
        $(this).parent('li').siblings('li').find('.ul_menu').slideUp();
        $(this).siblings('.ul_menu').slideDown();
    });

    $(".sel-icon").click(function(event) {
        /* Act on the event */
        $(this).toggleClass('active');
        $(this).hasClass('active') ? $(".btn-sure").attr('disabled', false) : $(".btn-sure").attr('disabled', true);
    });
    $(".input-acount").keyup(function(event) {
        /* Act on the event */
        var amountVal = $(this).val() || 0;
        var len = amountVal.length;
        if (len > 2) {
            $(".unit").css('left', len * 14);
        }
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
    //提币选择钱包，显示钱包金额
    (function() {
        if($("#sel").length > 0) {
            $raisingMoney.text($("#sel").find("option:selected").data('extract'));
        }
    })()
    $("#sel").change(function(event) {
        /* Act on the event */
        if ($(this).find("option:selected").data('extract')) {
            $raisingMoney.text($(this).find("option:selected").data('extract'));
        } else {
            $raisingMoney.text('0');
        }
    });

    // var myDate=new Date();
    // myDate.setFullYear(2017,08,24);
    // myDate.setHours(00,00,00);
    // var ts = myDate.getTime();
    // //var ts = (new Date()).getTime() + 10*24*60*60*1000;
    // if((new Date()) > ts){
    //     // The new year is here! Count towards something else.
    //     // Notice the *1000 at the end - time must be in milliseconds
    //     // ts = (new Date()).getTime() + 10*24*60*60*1000;
    //     // newYear = false;
    //     window.location.href = "/subscription/finish.html";
    // }
        
    // $('#countdown').countdown({
    //     timestamp   : ts
    // });
    //密码是否相同
    
    $("form").on('blur', '.input-pass-again', function(event) {
        event.preventDefault();
        /* Act on the event */
        var e = $(this);
        var pval = $.trim($(".input-newpass").val());
        if ($.trim(e.val()) !== '') {
            if ($.trim(e.val()) == pval) {
                e.closest(".form-group").removeClass("check-error");
                e.parent().find(".input-help").remove();
                e.closest(".form-group").addClass("check-success")
            } else {
                e.closest(".form-group").removeClass("check-success");
                e.closest(".form-group").addClass("check-error");
                e.closest(".field").append('<div class="input-help"><ul>' + e.data('checktext') + "</ul></div>")
            }
        }
    });
    //tab切换
    $(".detail_tab_header").on('click', 'a', function(event) {
        event.preventDefault();
        /* Act on the event */
        var index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $(".detail_tab_con").hide().eq(index).show();
    });
    //获取短信验证码
    var flag = true;
    var timeouttime = 60;
    var timeinterval = null;
    clearInterval(timeinterval);
    if ($.cookie('recordtime')) {
        var nowtime = new Date().getTime();
        var time = 60 - Math.round((nowtime - $.cookie('recordtime'))/1000);
        if (time >= 0) {
            timeouttime = time;
            timeinterval = setInterval(function() {
                timeouttime --;
                $(".btn-code").html(timeouttime + later);
                if (timeouttime == 0) {
                    $(".btn-code").html(resend);
                    timeouttime = 60;
                    clearInterval(timeinterval);
                    $.cookie('recordtime', null); 
                    flag = true;
                }
            }, 1000);
        } else {
            timeouttime = 60;
            $.cookie('recordtime', null);
        }
    }
    $(".btn-code").on('click',function(event) {
        /* Act on the event */
        event.preventDefault();
        var $that = $(this);
        var later = $that.data('later');
        var resend = $that.data('resend');

        var area_code = $("#area_code").val() || $(".code").text();
        var mobile = $.trim($("#mobile").val()) || $.trim($(".telphone").data('telphone'));
        if (mobile == undefined || mobile === '') {
            $(".gs-error").text($that.data('errortxt'));
            return false;
        } else if (!$("#mobile").closest('.form-group').hasClass('check-error')) {
            if (flag) {
                flag = false;
                var nowtime = new Date().getTime();
                if (!$.cookie('recordtime')) {
                    $.cookie('recordtime', nowtime);
                }
                 
                $.ajax({
                    url: "/home/user/valicode",
                    method: 'post',
                    dataType: 'json',
                    data: {
                        mobile: mobile,
                        area_code: area_code
                    },
                    success: function(response) {
                        switch (response.code) {
                            case 1: 
                                $.cookie('recordtime', null);
                                break;
                            case 102: 
                                $(".gs-error").text(response.msg);
                                $that.html(resend);
                                timeouttime = 60;
                                clearInterval(timeinterval);
                                $.cookie('recordtime', null); 
                                flag = true;
                                break;
                            case 103: 
                                $(".gs-error").text(response.msg);
                                $that.html(resend);
                                timeouttime = 60;
                                clearInterval(timeinterval);
                                $.cookie('recordtime', null); 
                                flag = true;
                                break;
                            case 104: 
                                $(".gs-error").text(response.msg);
                                $that.html(resend);
                                timeouttime = 60;
                                clearInterval(timeinterval);
                                $.cookie('recordtime', null); 
                                flag = true;
                                break;
                        }
                    },
                    error: function(error) {

                    }
                });
                timeinterval = setInterval(function() {
                    timeouttime --;
                    $that.html(timeouttime + later);
                    if (timeouttime == 0) {
                        $that.html(resend);
                        timeouttime = 60;
                        clearInterval(timeinterval);
                        $.cookie('recordtime', null); 
                        flag = true;
                    }
                }, 1000);
            }
        }
    });
})