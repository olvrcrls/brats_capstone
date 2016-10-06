@extends('layout')
@section('content')
<form action="/test/image/store" method="POST" enctype="multipart/form-data">
	{{ csrf_field() }}
    <label>File: </label>
    <br><input type="file" name="image" accept="image/*"/>
    <br>
    <input type="submit" />
</form>
@stop