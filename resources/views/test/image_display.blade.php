@extends('layout')
@section('content')
<img src="data:image/{{ $extension }};base64,{{ base64_encode($image) }}"/>
	
@stop