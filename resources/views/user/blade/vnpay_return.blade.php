@extends('user.layout.master')
@section('pageTitle', 'TicketPro')
@push('css')
<link href="/css/library/et-line.css" rel="stylesheet">
<link href="/css/library/ionicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/user/main_styles.css">
<link rel="stylesheet" href="/css/user/responsive.css">
<link href="/css/user/event-detail/payment.css" rel="stylesheet"/>
<link href="/vnpay_php/assets/bootstrap.min.css" rel="stylesheet"/>
<!-- Custom styles for this template -->
<link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">  

<style>
    .jumbotron.text-center {
    margin-top: 120px;
}
</style>

@endpush
@push('scripts')

@endpush

@section('content')
    <?php
    $vnp_TmnCode = "G2XY7630"; //Mã website tại VNPAY 
    $vnp_HashSecret = "MNPSFPIMPREYRRIHARQAOEZYNGTMGLBI"; //Chuỗi bí mật
    $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = "http://localhost/vnpay_php/vnpay_return.php";
    $vnp_SecureHash = $_GET['vnp_SecureHash'];
    $inputData = array();
    foreach ($_GET as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }
    unset($inputData['vnp_SecureHashType']);
    unset($inputData['vnp_SecureHash']);
    ksort($inputData);
    $i = 0;
    $hashData = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashData = $hashData . '&' . $key . "=" . $value;
        } else {
            $hashData = $hashData . $key . "=" . $value;
            $i = 1;
        }
    }

    //$secureHash = md5($vnp_HashSecret . $hashData);
    $secureHash = hash('sha256',$vnp_HashSecret . $hashData);
    ?>
    <!--Begin display -->
    <div class="container" padding-top=100>
        <div class="jumbotron text-center">
            <h1 class="display-3">Cảm ơn bạn đã tin tưởng</h1>
            <p class="lead"><strong>Thông tin đơn hàng của bạn</strong></p>
            <hr>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">Loại vé</th>
                        <th scope="col">Mã vé</th>
                        <th scope="col">Đơn giá</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data["attendee"] as $ticket)
                        <tr>
                            <td>{{$ticket->ticketClass()->get()->first()->type}}</td>
                            <td>{{$ticket->ticketCode}}</td>
                            <td>{{$ticket->ticketClass()->get()->first()->price}} VNĐ</td>
                        </tr>
                    @endforeach
                        <td>Tổng tiền</td>
                        <td></td>
                        <td>{{$_GET['vnp_Amount']}} VNĐ</td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <label >Mã đơn hàng:</label>

                    <label><?php echo $_GET['vnp_TxnRef'] ?></label>
                </div>    
                <div class="form-group">

                    <label >Số tiền:</label>
                    <label><?php echo $_GET['vnp_Amount'] ?></label>
                </div>  
                <div class="form-group">
                    <label >Nội dung thanh toán:</label>
                    <label><?php echo $_GET['vnp_OrderInfo'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Mã phản hồi (vnp_ResponseCode):</label>
                    <label><?php echo $_GET['vnp_ResponseCode'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Mã GD Tại VNPAY:</label>
                    <label><?php echo $_GET['vnp_TransactionNo'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Mã Ngân hàng:</label>
                    <label><?php echo $_GET['vnp_BankCode'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Thời gian thanh toán:</label>
                    <label><?php echo $_GET['vnp_PayDate'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Kết quả:</label>
                    <label>
                        <?php
                        if ($secureHash == $vnp_SecureHash) {
                            if ($_GET['vnp_ResponseCode'] == '00') {
                                echo "GD Thanh cong";
                            } else {
                                echo "GD Khong thanh cong";
                            }
                        } else {
                            echo "Chu ky khong hop le";
                        }
                        ?>
                    </label>
                </div> 
            </div>
            <p class="lead">
                <a class="btn btn-primary btn-sm" href="{{Route('home')}}" role="button">Tiếp tục mua vé.</a>
            </p>
        </div>
    </div>  
@endsection