$('#author_details_form').on('submit', function(e){
    e.preventDefault();
    var form = this;
    var formdata = new FormData(form);
    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: formdata,
        processData: false,
        dataType: 'json',
        contentType: false,
        success: function(response){
            if(response.status === 1){
                toastr.success(response.msg);
                // update name/bio di widget
                var name = $(form).find('input[name="name"]').val();
                var bio = $(form).find('textarea[name="bio"]').val();
                $('#author-name-preview').text(name ? name : '-');
                $('#author-bio-preview').text(bio ? bio : '-');
            }else{
                toastr.error(response.msg);
            }
        }
    });
});

$('#author_profile_file').ijaboCropTool({
    preview: '#author-photo-preview',
    setRatio: 16 / 9,
    allowedExtensions: ['jpg', 'jpeg', 'png'],
    processUrl: '/admin/update-author-photo',
    // Jika pakai CSRF global, bisa gunakan window.CSRF_TOKEN_NAME dan window.CSRF_HASH
    withCSRF: [typeof CSRF_TOKEN_NAME !== 'undefined' ? CSRF_TOKEN_NAME : '', typeof CSRF_HASH !== 'undefined' ? CSRF_HASH : ''],
    onSuccess: function (message, element, status) {
        // Coba parse jika message adalah JSON
        var response = {};
        try { response = JSON.parse(message); } catch(e) {}
        if (response.photo) {
            $('#author-photo-preview').attr('src', '/uploads/author/' + response.photo);
        }
        if (status == 1) {
            toastr.success(response.msg || message);
        } else {
            toastr.error(response.msg || message);
        }
    },
    onError: function (message, element, status) {
        alert(message);
    }
});

$('#delete-author-photo').on('click', function(){
    var id = $('input[name="id"]').val();
    // Ganti dengan URL langsung dan CSRF global jika ada
    $.post('/admin/delete-author-photo', {id: id}, function(response){
        if(response.status === 1){
            $('#author-photo-preview').attr('src', '/frontend/images/author.jpg');
            toastr.success(response.msg);
        }else{
            toastr.error(response.msg);
        }
    }, 'json');
});