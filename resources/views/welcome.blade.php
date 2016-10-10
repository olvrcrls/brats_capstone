@extends('layout')
@section('head')
    <link rel="stylesheet" href="/materialize/css/datepicker.css">
    <script type='text/javascript' src='/js/datepicker.js'></script>
    <script src="/js/index/app.js"></script>
@endsection
@section('content')
    <?php 
        use App\utilities_company as Utilities;
        try
        {
          $color = Utilities::select('UtilitiesCompanyInfo_PrimaryColor')->orderBy('UtilitiesCompanyInfo_Id', 'desc')->get();
          if ($color->count())
          {
            $color = $color[0]->UtilitiesCompanyInfo_PrimaryColor;
          }
          else
          {
            $color = 'yellow';
          }
        }
        catch (Exception $e)
        {
          $color = 'yellow';
        }
    ?>
    <div class="container">
            <br><br>
            <div class = "row">
                <div class = "col s6 offset-s6">
                    @if (isset($no_date))
                    <div class="card red lighten-1 z-depth-3">
                        <div class="card-content white-text">
                            <b><i class="fa fa-btn fa-remove"></i></b> <span>There are no available trip(s) on that selection.</span>
                        </div>
                    </div>
                    @endif
                    <div class="card grey lighten-3 accent-1 z-depth-3 hoverable">
                        <br>
                        <span class="flow-text" style="margin-left: 20px;">SCHEDULE YOUR BUS TRIP <i class="fa fa-briefcase"></i></span>
                        <div class="card-content black-text">
                            <form method = "POST" action = "{{ url('/travel_schedules') }}" id = "find_travel_form">
                                {{ csrf_field() }}
                                <div class = "row">
                                    <div class="input-field col s12" id="divTravelDate">
                                        <input type="date" id="travel_date" class="datepicker" name = "travel_date">
                                        <label for="travel_date" >Date of Your Trip Is <i class="fa fa-btn fa-calendar"></i>: </label>
                                    </div>
                                </div>
                                <div class= "row">
                                    <div class="input-field col s12">
                                        <div class="select-wrapper">
                                            <select id = "from" name = "origin" onchange="fetch_select(this.value);">
                                                <option value="" disabled="" selected="">Select Your Starting Point</option>
                                                @if (isset( $terminals ))
                                                    @foreach( $terminals as $terminal )
                                                        <option value="{{ $terminal->Terminal_Id }}">{{ $terminal->Terminal_Name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="#!" disabled> No available terminals, yet. </option>
                                                @endif
                                            </select>
                                        </div>
                                        <label>Your Bus Ride Will Be From <i class="fa fa-map-marker"></i>: </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <div class="select-wrapper">
                                            <select id="destination" name="destination" onchange="fetch_select(this.value);">
                                                <option value="" disabled="" selected="">Select Your End Point</option>
                                                @if (isset( $terminals ))
                                                    @foreach( $terminals as $terminal )
                                                        <option value="{{ $terminal->Terminal_Id }}">{{ $terminal->Terminal_Name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="#!" disabled> No available terminals, yet. </option>
                                                @endif
                                            </select>
                                        </div>
                                        <label>Your Bus Ride Will Be Travelling To <i class="fa fa-map-marker"></i>:</label>
                                    </div>
                                </div>
                                <div class= "row">
                                    <div class="col s6">
                                        <button class="btn waves-effect waves-light {{ strtolower($color) }} darken-1" type="submit" onclick = "format_date();">
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
@endsection
@section('footer')
    <script type='text/javascript' src='/js/site_es6.js'></script>
@endsection