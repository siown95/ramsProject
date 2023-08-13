$(document).ready(function () {
    loadCurriculum(); //커리큘럼 지정된 도서 로드
});

function loadCurriculum() {
    $.ajax({
        url: '/center/ajax/activityPaperControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'activityListLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#paperTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    displayLength: 5,
                    columns: [
                        {
                            data: 'code_name'
                        },
                        {
                            data: 'months'
                        },
                        {
                            data: 'img_link'
                        },
                        {
                            data: 'book_name'
                        },
                        {
                            data: 'book_writer'
                        },
                        {
                            data: 'book_publisher'
                        },
                        {
                            data: 'detail'
                        },
                        {
                            data: 'view'
                        },
                        {
                            data: 'no'
                        }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('align-middle');
                        $("th").addClass('text-center align-middle');
                        $("td:eq(8)",row).addClass('d-none');

                        if (!data.download) {
                            $(row).addClass('bg-nodata');
                        }
                    },
                    order: [[8, 'desc']],
                    lengthChange: false,
                    info: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#paperTable').DataTable().destroy();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}