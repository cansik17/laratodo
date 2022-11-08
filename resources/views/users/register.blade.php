@extends('partials.layout')
@section('title', ' - Register')
@section('content')
<div class="my-5 w-50 mx-auto">
    <h4 class="mb-1 ">Registeration </h4>
    <p class="mb-4 ">Register A New User For Creating Notes</p>
    <form action="/users" method="POST">
        @csrf
        <div class="form-group">
            <label>Name </label>
            <input type="text" name="name" class="form-control" value="{{old("name")}}" placeholder="Enter name">
            @error('name')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Email </label>
            <input type="email" name="email" class="form-control" value="{{old("email")}}" placeholder="Enter email">
            @error('email')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Password </label>
            <input type="password" name="password" class="form-control" value="{{old("password")}}" placeholder="Enter password">
            @error('password')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Password Again</label>
            <input type="password" name="password_confirmation" class="form-control" value="{{old("password_confirmation")}}" placeholder="Enter password">
    
        </div>
     
   
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
