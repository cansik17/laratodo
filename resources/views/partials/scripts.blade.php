<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="{{asset("assets/js/jquery.twbsPagination.min.js")}}"></script>	
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

</script>


<script>
    $(".logoutBtn").click(function (e) {
        e.preventDefault();
        let action = $(this).data("href")

        $.ajax({
            type: "POST",
            url: action,
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function (response) {
                if (response.type == "success") {
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })
                }
            }
        });

    });


    $(".toggleStatus").click(function (e) {
        //e.preventDefault();
        let val = $(this).prop("checked")
        let action = $(this).data("url")
        let elem = $(this).parents("tr").find("td:first-child")
        console.log(elem)
        if (typeof val !== "undefined" && typeof action !== "undefined") {

            $.ajax({
                type: "PUT",
                url: action,
                data: {
                    "_token": "{{ csrf_token() }}",
                    val: val
                },
                dataType: "json",
                success: function (response) {
                    if (response.type == "success") {
                        // Toast.fire({
                        //     icon: response.type,
                        //     title: response.message
                        // })
                        if (elem.hasClass("note-read")) {
                            elem.removeClass("note-read")
                        } else {
                            elem.addClass("note-read")
                        }

                        renderList()

                    } else {
                        Toast.fire({
                            icon: response.type,
                            title: response.message
                        })
                    }
                }
            });

        }
    });

    $(".createBtn").click(function (e) {
        e.preventDefault();
        $(".text-div").toggleClass("d-none")
    });

    $(".saveBtn").click(function (e) {
        e.preventDefault();
        let categories = $(".categoriesSelect").val()
        let due_date = $(".dueDate").val()
        let note = $(".noteContent").val()
        let action = $(this).data("url")
        console.log(categories)
        $.ajax({
            type: "POST",
            url: action,
            data: {
                "_token": "{{ csrf_token() }}",
                note: note,
                due_date: due_date,
                categories: categories
            },
            dataType: "json",
            success: function (response) {
                if (response.type == "success") {
                    renderList()
                    Toast.fire({
                        icon: response.type,
                        title: response.message
                    })
                    $(".text-div").toggleClass("d-none")

                }
            },
            error: function (err) {
                if (err.status == 422) { // when status code is 422, it's a validation issue
                    //console.log(err.responseJSON.message);
                    Toast.fire({
                        icon: "error",
                        title: err.responseJSON.message
                    })
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "An unexpected error occurred"
                    })
                }
            }

        });
    });

    $(".removeBtn").click(function (e) {
        e.preventDefault();
        let action = $(this).data("href")
        let element = $(this).parents("tr")
        let text = "Are you sure?";

        if (confirm(text) == true) {
            $.ajax({
                type: "DELETE",
                url: action,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (response) {
                    if (response.type == "success") {
                        //alert(response.message)
                        $(element).remove()
                    } else {
                        alert(response.message)
                    }
                }
            });
        }
    });

    function renderList() {
        // $.ajax({
        //     type: "GET",
        //     url: "/render-list",
        //     data: {
        //             "_token": "{{ csrf_token() }}"
        //         },
        //     success: function (response) {

        //          $("tbody.noteListBody").html(response)
        //     }
        // });

        filterList(params)
    }

    function filterList(params) {
        let filterDueDate = params.filterDueDate
        let filterCategories = params.filterCategories
        let filterStatus = params.filterStatus
        let filterSort = params.filterSort
        let filterPage = params.filterPage


        $.ajax({
            type: "GET",
            url: "/render-list",
            data: {
                "_token": "{{ csrf_token() }}",
                filterDueDate: filterDueDate,
                filterCategories: filterCategories,
                filterStatus: filterStatus,
                filterSort: filterSort,
                filterPage: filterPage
            },
            success: function (response) {

                $("tbody.noteListBody").html(response)
            }
        });
    }

    let params = []
    $('.filterStatus').change(function () {
        params["filterStatus"] = $(this).val()
        filterList(params)
    });
    $('.filterCategories').change(function () {
        params["filterCategories"] = $(this).val()
        filterList(params)
    });
    $('.filterDueDate').change(function () {
        params["filterDueDate"] = $(this).val()
        filterList(params)
    });
    $('.filterSort').change(function () {
        params["filterSort"] = $(this).val()
        filterList(params)
    });

    // $(document).on('click', '.pagination a', function (event) {
    //     event.preventDefault();
    //     var page = $(this).attr('href').split('page=')[1];
    //     params["filterPage"] = page
    //     filterList(params)
    // });
    var totalPagesx = $('#pagination-twbs').data("total")
    var startPagex = $('#pagination-twbs').data("current")
   
    $('#pagination-twbs').twbsPagination({
        totalPages: totalPagesx,
        startPage: startPagex,
        prev: '<span aria-hidden="true">&laquo;</span>',
        next: '<span aria-hidden="true">&raquo;</span>',
        firstClass: 'd-none',
        lastClass: 'd-none',
        activeClass: 'active',
  
        onPageClick: function(evt, page) {
             params["filterPage"] = page
             filterList(params)
        }
    });
	

</script>

<script>
    $(document).ready(function () {
        $('.select-multiple').select2({
            placeholder: "Select Categories",
        });
    });

</script>
