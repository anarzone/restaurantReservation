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
