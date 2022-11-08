@extends('partials.layout')
@section('title', '')
@section('content')
<div class="mt-4 mb-3">
    <h4 class="mb-1 d-inline-block mb-3">Notes </h4>
    @auth
    <button class="btn btn-info btn-sm mb-3 float-right createBtn">New</button>
    <div class="w-50 ml-auto mb-3 d-none text-div">
        <select name="categories[]" class="categoriesSelect select-multiple mb-2" multiple="multiple">
            @foreach ($categories as $row)
            <option value="{{$row->id}}">{{$row->title}}</option>
            @endforeach
        </select>
        <input type="date" class="form-control dueDate mb-2" name="due_date">
        <textarea class="form-control mb-2 noteContent" rows="3"></textarea>
        <button class="btn btn-success btn-sm mb-3 float-right saveBtn" data-url="/">Add To List</button>
    </div>
    <div class="row filter-area ">
        <div class="col-md-3 ">
            <select class="categoriesSelect select-multiple mb-2 filterCategories" multiple="multiple">
                @foreach ($categories as $row)
                <option value="{{$row->id}}">{{$row->title}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 ">
            <select class="form-control filterDueDate" >
                <option value="">Due Date Filter</option>
                <option value="expired">Expired</option>
                <option value="oneDay">1 day</option>
                <option value="threeDays">3 day</option>
                <option value="oneWeek">1 week</option>
                <option value="oneMonth">1 month</option>
                <option value="oneYear">1 year</option>
                <option value="notExpired">Not Expired</option>
            </select>
        </div>
        <div class="col-md-3 ">
            <select class="form-control filterStatus" >
                <option value="">Complete Status Filter</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="col-md-3 ">
            <select class="form-control filterSort" >
                <option value="">Sorting Filter</option>
                <option value="dueDateAsc">Due Date ASC</option>
                <option value="dueDateDesc">Due Date DESC</option>
                <option value="createDateAsc">Create Date ASC</option>
                <option value="createDateDesc">Create Date DESC</option>
            </select>
        </div>

    </div>


</div>
<table class="table table-striped ">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Note</th>
            <th scope="col">Categories</th>
            <th scope="col">Due Date</th>
            <th scope="col">Created At</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody class="noteListBody">
        @forelse ($items as $item)
        <tr>
            <td class="{{$item->is_read == 1 ? "note-read" : ""}}">{{$item->note}}</td>
            <td>{{getCategoriesName($item->id)}}</td>
            <td>{{getDateForHumans($item->due_date)}}</td>
            <td>{{getDateForHumans($item->created_at)}} by {{$item->user_id}}</td>
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

    </tbody>
</table>
<ul id="pagination-twbs" data-total="{{$paginationArr["totalPages"]}}" data-current="{{$paginationArr["currentPage"]}}"></ul>
@endauth

@guest
<p>You need to <a href="/login">login</a> to see your notes.</p>
@endguest
</div>
@endsection
