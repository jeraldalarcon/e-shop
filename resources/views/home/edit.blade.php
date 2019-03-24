@extends('layouts.app')
@section('content')
<div class="container">

        <h1>Edit</h1>
            <form method="post" action="{{ url('home/edit/'.$data->id) }}">
                {{ csrf_field() }}
                <input type="text" name="name" class="form-control" value="{{ $data->name }}">
                <input type="text" name="address" class="form-control" value="{{ $data->address }}">
                <input type="text" name="age" class="form-control" value="{{ $data->age }}">
                <button type="submit" class="btn btn-primary">Edit data</button>
            </form>
</div>
@endsection