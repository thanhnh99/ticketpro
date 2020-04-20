@extends('admin.layout.master')
@section('pageTitle', 'Admin: Trang chủ')
@push('css')
    <link href="/css/library/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="/css/library/dataTables.bootstrap4.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="/css/library/responsive.bootstrap4.min.css" rel="stylesheet" id="bootstrap-css">
@endpush

@push('scripts')
<script type="text/javascript" src="/js/library/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/js/library/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/library/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="/js/library/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="/js/library/responsive.bootstrap4.min.js"></script>
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
                <th>Người tạo</th>
                <th>Nhà tổ chức</th>
                <th>Tạo lúc</th>
                <th>Thời gian bắt đầu</th>
                <th>Địa điểm</th>
                <th>Trạng thái</th>
                <th>Danh sách vé đã bán</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $event)
            <tr>
                <td>{{substr($event->name, 0,20)."..."}}</td>
                <td>{{$event->user()->get()->first()->name}}</td>
                <td>{{$event->organizer()->get()->first()->name}}</td>
                <td>{{$event->created_at}}</td>
                <td>{{$event->startTime}}</td>
                <td>{{$event->location()->get()->first()->city}}</td>
                <td>Hoàn thành</td>
                <td>
                    <table>
                        <thead>
                            <tr>
                                <th>Loại vé</th>
                                <th>Code vé</th>
                                <th>Mua lúc</th>
                            </tr>    
                        </thead>
                        <tbody>
                            @foreach($event->attendees()->get() as $attendee)
                            <tr>
                                <td>{{$attendee->ticketClass()->get()->first()->type}}</td>
                                <td>{{$attendee->ticketCode}}</td>
                                <td>{{$attendee->created_at}}</td>
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



