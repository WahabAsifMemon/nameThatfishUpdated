@extends('layouts.simple.master')
@section('title', 'User Management')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
@endsection
@section('style')
@endsection
@section('breadcrumb-title')
<h3>User Management</h3>
@endsection
@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">User Management</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row second-chart-list third-news-update">
        <h2>All User</h2>
        <div class="col-sm-12">
            <table id="example" class="table table-striped dataTable" style="width: 100%;" aria-describedby="example_info">
                <thead>
                    <tr>
                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 138px;">Name</th>
                        <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 221px;">Email</th>
                        <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 78px;">Phone</th>
                        <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 78px;">Role</th>
                        <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 78px;">Status</th>
                        <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" style="width: 78px;">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(App\Models\User::all() as $user)
                    @if($user->role_id == 2)
                    <tr class="odd">
                        <td class="sorting_1">{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{ $user->role_id }}</td>
                        <td class="status-column">
                            @if($user->status == 1)
                                <button class="btn btn-success status-btn" data-user-id="{{ $user->id }}" data-status="0">
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                            @else
                                <button class="btn btn-danger status-btn" data-user-id="{{ $user->id }}" data-status="1">
                                    <i class="fas fa-thumbs-down"></i>
                                </button>
                            @endif
                        </td>

                        <td>{{$user->created_at}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/dashboard/default.js')}}"></script>
<script src="{{asset('assets/js/notify/index.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<script src="{{asset('assets/js/vendors/datatables.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>'
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="{{asset('assets/js/vendors/datatables.js')}}"></script>
<script>
$(document).ready(function() {
  $('#example').DataTable();
  
  $('.status-btn').on('click', function() {
    var statusBtn = $(this);
    var userId = statusBtn.data('user-id');
    var status = statusBtn.data('status');
    $.ajax({
      url: '/users/' + userId + '/status',
      method: 'PUT',
      data: {
        status: status,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if (response.status === 'success') {
          var updatedStatus = response.data.status;
          var statusText = (updatedStatus === 1) ? 'Active' : 'Disabled';
          var statusClass = (updatedStatus === 1) ? 'text-success' : 'text-danger';

          var statusColumn = statusBtn.closest('tr').find('.status-column');
          statusColumn.html('');
          
          if (updatedStatus === 1) {
            statusColumn.append('<i class="fas fa-thumbs-up status-btn" data-user-id="' + userId + '" data-status="0"></i>');
          } else {
            statusColumn.append('<i class="fas fa-thumbs-down status-btn" data-user-id="' + userId + '" data-status="1"></i>');
          }
          
          statusColumn.removeClass('text-success text-danger').addClass(statusClass);
        }
      },
      error: function(error) {
        console.log(error);
      }
    });
  });
});
</script>


@endsection

