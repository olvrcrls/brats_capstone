@extends('layout')
@section('head')
    <link rel="stylesheet" href="/materialize/css/datepicker.css">
    <script type='text/javascript' src='/js/datepicker.js'></script>
    <script src="/js/index/app.js"></script>
@endsection
@section('content')
    <div class="container">
            <br><br>
            <div class = "row">
                <div class = "col s6 offset-s6">
                    @if (isset($no_date))
                    <div class="card red lighten-1 z-depth-3">
                        <div class="card-content white-text">
                            <b><i class="fa fa-btn fa-remove"></i></b> <span>There is no available trip(s) on that date.</span>
                        </div>
                    </div>
                    @endif
                    <div class="card grey lighten-3 accent-1 z-depth-3">
                        <div class="card-content black-text">
                            <form method = "POST" action = "{{ url('/travel_schedules') }}" id = "find_travel_form">
                                {{ csrf_field() }}
                                <div class = "row">
                                    <div class="input-field col s12" id="divTravelDate" 
                                    >
                                        <input type="date" id="travel_date" class="datepicker" name = "travel_date">
                                        <label for="travel_date" >Travel Date <i class="fa fa-btn fa-calendar"></i>: </label>
                                    </div>
                                </div>
                                <div class= "row">
                                    <div class="input-field col s12">
                                        <div class="select-wrapper">
                                            <select id = "from" name = "origin" onchange="fetch_select(this.value);">
                                                <option value="" disabled="" selected="">Choose Terminal Origin</option>
                                                @if ( isset( $terminals ) )
                                                    @foreach( $terminals as $terminal )
                                                        <option value="{{ $terminal->Terminal_Id }}">{{ $terminal->Terminal_Name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="#!" disabled> No available terminal. </option>
                                                @endif
                                            </select>
                                        </div>
                                        <label>Terminal Origin <i class="fa fa-map-marker"></i>: </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <div class="select-wrapper">
                                            <select id="destination" name="destination" onchange="fetch_select(this.value);">
                                                <option value="" disabled="" selected="">Choose Terminal Destination</option>
                                                @if ( isset( $terminals ) )
                                                    @foreach( $terminals as $terminal )
                                                        <option value="{{ $terminal->Terminal_Id }}">{{ $terminal->Terminal_Name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="#!" disabled> No available terminal. </option>
                                                @endif
                                            </select>
                                        </div>
                                        <label>Terminal Destination <i class="fa fa-map-marker"></i>:</label>
                                    </div>
                                </div>
                                <div class= "row">
                                    <div class="col s6">
                                        <button class="btn waves-effect waves-light amber accent-3" type="submit" onclick = "format_date();">
                                            <b>Search</b> <i class="fa fa-btn fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <!--   <div class="fixed-action-btn horizontal" style="bottom: 45px; right: 24px;">
            <a class="btn-floating btn-large red">
                <i class="large mdi-navigation-menu"></i>
            </a>
            <ul>
                <li><a class="btn-floating red"><i class="material-icons">insert_chart</i></a></li>
                <li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
                <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
                <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li>
            </ul>
        </div>  
    </div> -->
@endsection
@section('footer')
    <script type='text/javascript' src='/js/site.js'></script>
@endsection