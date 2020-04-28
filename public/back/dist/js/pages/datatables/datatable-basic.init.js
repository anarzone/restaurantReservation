/****************************************
 *       Basic Table                   *
 ****************************************/
$('#zero_config').DataTable({
    language:
        {
            "sEmptyTable":     "Cədvəldə heç bir məlumat yoxdur",
            "sInfo":           " _TOTAL_ Nəticədən _START_ - _END_ Arası Nəticələr",
            "sInfoEmpty":      "Nəticə Yoxdur",
            "sInfoFiltered":   "( _MAX_ Nəticə İçindən Tapılanlar)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Səhifədə _MENU_ Nəticə Göstər",
            "sLoadingRecords": "Yüklənir...",
            "sProcessing":     "Gözləyin...",
            "sSearch":         "Axtarış:",
            "sZeroRecords":    "Nəticə Tapılmadı.",
            "oPaginate": {
                "sFirst":    "İlk",
                "sLast":     "Axırıncı",
                "sNext":     "Sonraki",
                "sPrevious": "Öncəki"
            },
            "oAria": {
                "sSortAscending":  ": sütunu artma sırası üzərə aktiv etmək",
                "sSortDescending": ": sütunu azalma sırası üzərə aktiv etmək"
            }
        }
});

/****************************************
 *       Default Order Table           *
 ****************************************/
$('#default_order').DataTable({
    "order": [
        [3, "desc"]
    ]
});

/****************************************
 *       Multi-column Order Table      *
 ****************************************/
$('#multi_col_order').DataTable({
    columnDefs: [{
        targets: [0],
        orderData: [0, 1]
    }, {
        targets: [1],
        orderData: [1, 0]
    }, {
        targets: [4],
        orderData: [4, 0]
    }]
});
