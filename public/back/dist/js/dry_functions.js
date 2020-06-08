function deleteEl(data, url, titleQuestion,
                  redirectUrl='',
                  confirmText="Təsdiq et",
                  cancelText= "Imtina et",
                  successMessage = "",
                  )
{
    Swal.fire({
        title: titleQuestion,
        showCancelButton: true,
        confirmButtonColor: "#dd6b55",
        confirmButtonText: "Təsdiq et",
        cancelButtonText: 'Imtina et',
    }).then((confirmed)=>{
        if(!confirmed.value) return
        $.ajax({
            type: 'DELETE',
            url:  url,
            data: data,
            success: function (result) {
                window.location.href = redirectUrl
            },
            error: function (result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: result.message,
                })
            }
        })
    })
}


function getHalls(restaurant_id, hall_id=null){
    $.ajax({
        type: 'GET',
        url: '/getHallsByRestId/' + restaurant_id,
        dataType: "json",
        success: function (result) {
            if(result.data){
                $('#halls').empty().focus();
                $('#halls').append('<option disabled selected value> -- Zal seçin -- </option>');
                $.each(result.data, function(key, val){
                    let selected = parseInt(hall_id) === val.id ? "selected" : ""
                    $('#halls').append(
                        '<option value="'+ val.id +'" '+ selected +' > ' + val.name + '</option>'
                    );
                });
            }else{
                $('#halls').empty();
            }
        }
    })
}

function displayMessage(message, type='success'){
    toastr.options = {
        "preventDuplicates": true,
        "positionClass": "toast-top-center",
    }
    if(type === 'success'){
        toastr.success(message);
    }else if (type === 'error'){
        toastr.error(message)
    }
}
