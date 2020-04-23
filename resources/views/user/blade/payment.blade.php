@extends('user.layout.master')
@section('pageTitle', 'Payment')
@push('metadata')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
@endpush
@push('css')
    <link href="/css/user/event-detail/payment.css" rel="stylesheet"/>
    <link href="/vnpay_php/assets/bootstrap.min.css" rel="stylesheet"/>
    <!-- Custom styles for this template -->
    <link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">  
@endpush
@push('scripts')
    <script src="/vnpay_php/assets/jquery-1.11.3.min.js"></script>
    <script src="/js/user/event-detail/booking.js"></script>
    <script src="/js/user/event-detail/main.js"></script>
@endpush
@section('content')
<div class="container mgt140 mh673">
    <div class="booking-infor">
        <h3>Thông tin đơn hàng</h3>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">Loại vé</th>
                <th scope="col">Số lượng</th>
                <th scope="col">Đơn giá</th>
                <th scope="col">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order_session["tickets"] as $ticket)
                <tr>
                    <td>{{$ticket["type"]}}</td>
                    <td>{{$ticket["quantity"]}}</td>
                    <td>{{$ticket["total_price"]/$ticket["quantity"]}}</td>
                    <td>{{$ticket["total_price"]}}</td>
                </tr>
            @endforeach
                <td>Tổng tiền</td>
                <td></td>
                <td></td>
                <td>{{$order_session["order_total"]}} VNĐ</td>
                </tr>
            </tbody>
        </table>
    </div>
    <h3>Thông tin thanh toán và nhận vé</h3>
    <div class="table-responsive">
            <div class="form-group">
                <label for="InputTen">Họ Tên</label>
                <input id="user_booking" type="text" class="form-control" placeholder="Nhập tên" require>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Địa chỉ Email</label>
                <input id= "mail_booking" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                    placeholder="Vé sẽ được chuyển về mail này. Vui lòng viết đúng Email" name="booking_email" require>
            </div>
            <div class="form-group">
                <label for="InputTen">Số điện thoại</label>
                <input id = "phone_booking" type="text" class="form-control" placeholder="Nhập số điện thoại" name="booking_phone" require>
            </div>
            <button id = "btnVnpay" type="button" class="btn btn-primary" onclick="validateOrder()">VNpay</button>
    </div>
    <p>
        &nbsp;
    </p>
</div>  
<link href="https://sandbox.vnpayment.vn/paymentv2/lib/vnpay/vnpay.css" rel="stylesheet"/>
<script src="https://sandbox.vnpayment.vn/paymentv2/lib/vnpay/vnpay.js"></script>
@endsection
