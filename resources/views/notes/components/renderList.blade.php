@forelse ($items as $item)
<tr>
    <td class="{{$item->is_read == 1 ? "note-read" : ""}}">{{$item->note}}</td>
    <td>{{getCategoriesName($item->id)}}</td>
    <td>{{getDateForHumans($item->due_date)}}</td>
    <td>{{$item->created_at}} by {{$item->user_id}}</td>
    <td>
        <label class="switch">
            <input type="checkbox" class="toggleStatus" data-url="/toggle-status/{{$item->id}}"
                {{$item->is_read == 1 ? "checked" : ""}} />
            <span class="slider round"></span>
        </label>
    </td>
    <td>
        <a href="#" data-href="/{{$item->id}}" class="btn btn-sm btn-danger removeBtn"><i
                class="fa-solid fa-times"></i></a>
    </td>
</tr>
@empty
<tr>
    No Data
</tr>
@endforelse

<script>
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

    $('#pagination-twbs').attr("data-total",{{$paginationArr["totalPages"]}})
    $('#pagination-twbs').attr("data-current",{{$paginationArr["currentPage"]}})


</script>
