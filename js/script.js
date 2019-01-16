$(document).ready(function () {

    $(".disFile").click(function (e) {
        e.stopPropagation();
    });

    $(".clickable-row").click(function () {
        window.location = $(this).data("href");
    });

    $('#removeBtn').click(function () {

        var courseId = $(this).attr("courseId");
        var studentMail = $(this).attr("email");

        $.post("../student/remove_course.php",
            {
                id: courseId,
                mail: studentMail
            },
            function (data) {
                alert(data + "\n");
                window.location.href = '../student/panel.php';
            });
    });

    $('#submitBtn').click(function () {
        var courseId = $(this).attr("courseId");
        var studentMail = $(this).attr("email");
        var form_data = new FormData();
        if(document.getElementsByClassName("free").length){
            form_data.append('id', courseId);
            form_data.append('mail', studentMail);
            $.ajax({
                url: '../student/add_course.php', // point to server-side PHP script
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (php_script_response) {
                    alert(php_script_response); // display response from the PHP script, if any
                    window.location.href = '../student/panel.php';
                }
            });
        }
        else {
            var c = "";
            if ($('input[name=payType]:checked').val() == 'online') {
                alert("این بخش در حال حاضر در دسترس نمی باشد");
                return;
            }
            if (document.getElementById('discount').value) {
                if (document.getElementById('discount') != null)
                    c = document.getElementById('discount').value;
            }
            else {
                c = $(this).attr("code");
            }

            var file_dis = "";
            var file_res = "";

            if ($('#receipt').prop('files')) {
                if ($('#receipt').val() == "") {
                    alert("لطفا فیش واریز را آپلود کنید");
                    return;
                }
                else {
                    file_res = $('#receipt').prop('files')[0];
                }
            }

            if ($('#neededFile').prop('files')) {
                if ($('#needFile').val() == "") {
                    file_dis = "";
                }
                else {
                    file_dis = $('#neededFile').prop('files')[0];
                }
            }

            form_data.append('disFile', file_dis);
            form_data.append('resFile', file_res);
            form_data.append('code', c);
            form_data.append('id', courseId);
            form_data.append('mail', studentMail);
            $.ajax({
                url: '../student/add_course.php', // point to server-side PHP script
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (php_script_response) {
                    alert(php_script_response); // display response from the PHP script, if any
                    window.location.href = '../student/panel.php';
                }
            });
        }
    });

    $('#discountCodeBtn').click(function () {

        $.post("../student/pay.php",
            {
                code: document.getElementById('discount').value,
                id: $(this).attr("courseId"),
                cost: $(this).attr("cost")
            },
            function (data) {
                if (parseInt(data)) {
                    document.getElementById('finalCost').style.display = "block";
                    document.getElementById('finalCost').innerText =  data.trim() ;
                    document.getElementById('realCost').className = "cost invalid";
                }
                else {
                    alert(data);
                }
            });

    });

    $('#discountFileBtn').click(function () {

        var c = '';

        if ($("#neededFile").val() != '')
            c = 'FILE';

        $.post("../student/pay.php",
            {
                code: c,
                id: $(this).attr("courseId"),
                cost: $(this).attr("cost")
            },
            function (data) {
                if (parseInt(data)) {
                    document.getElementById('finalCost').style.display = "block";
                    document.getElementById('finalCost').innerText =  data.trim() ;
                    document.getElementById('realCost').className = "cost invalid";
                }
                else {
                    alert('فایل ارسال شده معتبر نمی باشد');
                }
            });

    });

    $('#submitOrder').click(function (e) {

        e.preventDefault();

        $.post("../submitOrder.php",
            {
                id: $(this).attr("orderId"),
            },
            function (data) {
                if (data == 'ok') {
                    alert("سفارش با موفقیت تایید شد");
                    $('#submitOrder').hide();
                }
                else
                    alert("خطا در ثبت سفارش");
            });

    });

    $('input[type=radio][name=needFile]').change(function () {
        if (this.value == 'true') {
            $('.havecode').hide();
            $("#discountCode").val("FILE");
        }
        else if (this.value == 'false') {
            $('.havecode').show();
            $("#discountCode").val("");
        }
    });

    $('#reagentCheck').change(function () {
        if (this.checked) {
            $(".noReagentType").hide();
            $(".reagentType").show();
            $("#discountType").val("معرف");
            $("#discountCode").val($("#reagent").val());
            $("#link").text("http://localhost/edu/student/course.php" + "?id=" + $("#course").val() + "&reagent=" + btoa($("#reagent").val()));
        }
        else {
            $(".noReagentType").show();
            $(".reagentType").hide();
            $("#discountType").val("");
            $("#discountCode").val("");
        }
    });

    $('#reagent').change(function () {
        $("#discountCode").val($("#reagent").val());
        $("#link").text("http://localhost/edu/student/course.php" + "?id=" + $("#course").val() + "&reagent=" + btoa($("#reagent").val()));
    });
    $('#course').change(function () {
        $("#link").text("http://localhost/edu/student/course.php" + "?id=" + $("#course").val() + "&reagent=" + btoa($("#reagent").val()));
    });

    if ($('#reagentCheck').is(":checked")) {
        $(".noReagentType").hide();
        $('.havecode').hide();
        $(".reagentType").show();
        $("#discountType").val("معرف");
        $("#discountCode").val($("#reagent").val());
        $("#link").text("http://localhost/edu/student/course.php" + "?id=" + $("#course").val() + "&reagent=" + btoa($("#reagent").val()));
    }
    else {
        if ($('#codeId').is(':checked')) {
            $('.havecode').show();
        }

        if ($('#fileId').is(':checked')) {
            $('.havecode').hide();
        }
    }

    $('.needFile').hide();
    $('.needCode').hide();

    $('input[type=radio][name=disType]').change(function () {
        if (this.value == 'none') {
            $('.needFile').hide();
            $('.needCode').hide();
            $('#discount').val("");
        }
        else if (this.value == 'code') {
            $('.needFile').hide();
            $('.needCode').show();
            $('#discount').val("");
        }
        else if (this.value == 'file') {
            $('.needFile').show();
            $('.needCode').hide();
            $('#discount').val("FILE");
        }
    });


});