@extends('layouts.simple.master')

@section('title', 'Setting')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Setting</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">Setting</li>
@endsection

@section('content')
<form action="{{ route('setting') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="app_url" value="@isset($setting){{ $setting->app_url }}@endisset">
    <input type="file" name="app_icon">
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
<br>
@foreach(App\Models\Setting::all() as $setting)
<table class="table table-bordered">
    <thead style="background-color: #F8F8F8;">
        <tr>
            <th width="25%">App Url</th>
            <th width="25%">App Icon</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $setting->app_url }}</td>
            <td>
                @if($setting->app_icon == '' || $setting->app_icon == 'No image found')
                <img src="/assets/admin/dist/img/no-img.png" width="100" height="100" class="img-thumbnail" alt="No image found">
                @else
                <img src="{{ asset('uploads/' . $setting->app_icon) }}" class="img-thumbnail"  width="100"  height="100" alt="Setting Image">
                @endif 
            </td>
        </tr>
    </tbody>
</table>
@endforeach
@endsection




@section('script')
<script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
<script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
