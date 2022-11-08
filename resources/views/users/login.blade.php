@extends('partials.layout')
@section('title', ' - Login')
@section('content')
<div class="my-5 w-50 mx-auto">
    <h4 class="mb-1">Login </h4>
    <p class="mb-4 ">Enter your credentials for logged in. Dont have an account? Please <a href="/register">register</a>.</p>
    <form action="/users/authenticate" method="POST">
        @csrf

        <div class="form-group">
            <label>Email </label>
            <input type="email" name="email" class="form-control" value="{{old("email")}}" placeholder="Enter email">
            @error('email')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Password </label>
            <input type="password" name="password" class="form-control" placeholder="Enter password">
            @error('password')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

     
   
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
