@extends('layout')
@section('content')
	<div class="container">
		<h2>List of Routes <i class="fa fa-btn fa-road"></i></h2>
		<!-- <div class="row">
			<div class="input-field col m6 col s6">
				<input type="text" name="searchBar" id="searchBar" placeholder="Search a specific Route" />
			</div>
		</div> -->
		<table class="highlight bordered">
			<tbody>
				<routes></routes>
			</tbody>
		</table>
	</div><br/><br/><br/><br/>
@stop
@section('footer')
	<script type="text/javascript" src="/js/compiled/routes_es6.js"></script>
@stop