$(document).ready(function () {

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
                window.location.href = '../student/mycourses.php';
            });
    });

    $('#submitBtn').click(function () {

        var courseId = $(this).attr("courseId");
        var studentMail = $(this).attr("email");
        var c = "";


        if($(this).attr("code")==""){
            if (document.getElementById('discount') != null)
                c = document.getElementById('discount').value;
        }
        else{
            c = $(this).attr("code");
        }

        $.post("../student/add_course.php",
            {
                code: c,
                id: courseId,
                mail: studentMail
            },
            function (data) {
                alert(data + "\n");
                window.location.href = '../student/mycourses.php';
            });

    });

    $('#discountBtn').click(function () {

        $.post("../student/pay.php",
            {
                code: document.getElementById('discount').value,
                id: $(this).attr("courseId"),
                cost: $(this).attr("cost")
            },
            function (data) {
                if (parseInt(data)) {
                    document.getElementById('finalCost').innerText = "هزینه با محاسبه تخفیف " + data.trim() + " ریال";
                    document.getElementById('realCost').className = "invalid";
                }
                else {
                    alert('کد وارد شده معتبر نمی باشد');
                }
            });

    });

        if($('#reagentCheck').is(":checked")){
            $(".noReagentType").hide();
            $(".reagentType").show();
            $("#discountType").val("معرف");
            $("#discountCode").val($("#reagent").val());
            $("#link").text("http://localhost/edu/php/student/course.php"+"?id="+$("#course").val()+"&reagent="+btoa($("#reagent").val()));
        }
        else if($('#reagentCheck').is(":not(:checked)")){
            $(".noReagentType").show();
            $(".reagentType").hide();
        }

    $('#reagentCheck').change(function () {
        if (this.checked) {
            $(".noReagentType").hide();
            $(".reagentType").show();
            $("#discountType").val("معرف");
            $("#discountCode").val($("#reagent").val());
            $("#link").text("http://localhost/edu/php/student/course.php"+"?id="+$("#course").val()+"&reagent="+btoa($("#reagent").val()));
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
        $("#link").text("http://localhost/edu/php/student/course.php"+"?id="+$("#course").val()+"&reagent="+btoa($("#reagent").val()));
    });
    $('#course').change(function () {
        $("#link").text("http://localhost/edu/php/student/course.php"+"?id="+$("#course").val()+"&reagent="+btoa($("#reagent").val()));
    });

});