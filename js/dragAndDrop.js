var fileobj;

function upload_file(e) {
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    ajax_file_upload(fileobj);
}

function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function () {
        fileobj = document.getElementById('selectfile').files[0];
        ajax_file_upload(fileobj);
    };
}

function ajax_file_upload(file_obj) {
    var form_data = new FormData();
    form_data.append('file', file_obj);
    $.ajax({
        type: 'POST',
        url: 'php/allcourses_download.php',
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            alert(response);
            // document.getElementById('selectfile').val(response);
        }
    });
}
