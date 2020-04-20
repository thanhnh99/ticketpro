@extends('admin.layout.master')
@section('pageTitle', 'Admin: Đặt vé')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="/css/library/jquery-ui.css" rel="stylesheet" id="bootstrap-css">
    
@endpush

@push('scripts')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/library/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );

</script>

@endpush

@section('content')
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Sự kiện</th>
                <th>Tạo lúc</th>
                <th>Số lượng</th>
                <th>Giá trị</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $booking)
                <tr id="{{'booking'.$booking->id}}" onclick="openDialog()">
                    <td class="{{$booking->id}}">{{substr($booking->event()->get()->first()->name,0,20)."..."}}</td>
                    <td class="{{$booking->id}}">{{$booking->created_at}}</td>
                    <td class="{{$booking->id}}">{{$booking->totalQuantity}}</td>
                    <td class="{{$booking->id}}">{{$booking->totalPrice}}</td>
                    <td class="{{$booking->id}}">{{$booking->email}}</td>
                    <td class="{{$booking->id}}">@if($booking->status) Thành công @else Không thành công @endif</td>
                    <td align="center">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sự kiện</th>
                                    <th>Loại vé</th>
                                    <th>Code vé</th>
                                    <th>Mua lúc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->attendees()->get() as $attendees)
                                <tr>
                                    <td>{{substr($booking->event()->get()->first()->name,0,20)."..."}}</td>
                                    <td>{{$attendees->ticketClass()->get()->first()->type}}</td>
                                    <td>{{$attendees->ticketCode}}</td>
                                    <td>{{$attendees->created_at}}</td>                                 
                                </tr>
                                @endforeach                            
                                
                            </tbody>
                        </table>
                    </td>
                </tr>
        @endforeach            
        </tbody>
    </table>
@endsection



