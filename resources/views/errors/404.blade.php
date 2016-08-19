@extends('layout')
@section('content')
	<div class="container-fluid">
		<div class="container">
			<h1 class="teal-text">Page Not Found. <i class="fa fa-thumbs-down"></i></h1>&nbsp;
			<div>
				<h3>
				<p class="text-muted">
					You are currently accessing a page that does not exist or apparently had been recently removed.
				</p>
				<p class="text-muted">
					Just click <a href="{{ url('/') }}">here</a> to go back on home page.
				</p>
				</h3>
			</div>
		</div>
	</div><br/><br/><br/>
@endsection