@extends('layouts.app')
@section('content')
<div class="container">

        <h1>add</h1><a href="{{ url('/home')}}">back</a>
            <form method="post" action="{{ url('home/add') }}">
                {{ csrf_field() }}
                <input type="text" name="name" class="form-control" placeholder="name">
                <input type="text" name="address" class="form-control" placeholder="address">
                <input type="text" name="age" class="form-control" placeholder="age">
                <button type="submit" class="btn btn-primary">add data</button>
            </form>
</div>
@endsection