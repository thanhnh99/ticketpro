<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Event;
use App\Booking;
use App\BookingDetail;
use App\Attendee;
use Auth;
class PaymentController extends Controller
{
    //
    public $vnp_TmnCode = "G2XY7630"; //Mã website tại VNPAY 
    private $vnp_HashSecret = "MNPSFPIMPREYRRIHARQAOEZYNGTMGLBI"; //Chuỗi bí mật
    private $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_Returnurl = "http://localhost/vnpay_php/vnpay_return.php";

    public function createPayment(Request $request, $eventId)
    {
        $data=[];
        $order_session = Session::get('ticket_order_' . $eventId);
        // dd($order_session["tickets"][1]["quantity"]);
        if($order_session){
            $event = Event::find($eventId);

            return view('user.blade.payment', compact('event', 'order_session'));
        }
        return view('vnpay.create_payment');
    }

    public function postPayment(Request $request, $eventId)
    {
        $vnp_TmnCode = "G2XY7630"; //Mã website tại VNPAY 
        $vnp_HashSecret = "MNPSFPIMPREYRRIHARQAOEZYNGTMGLBI"; //Chuỗi bí mật
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost/vnpay-return";
        
        $event = Event::where("id",$eventId)->first()->get();

        $order = session()->get('ticket_order_' . $eventId);

    
        $booking = new Booking;
        $booking->userId = Auth::user()->id;
        $booking->eventId = $eventId;
        $booking->status = 0;
        $booking->email = 'default';
        $booking->phone = '4994328543064';
        $booking->totalQuantity = $order["quantity_total"];
        $booking->totalPrice= $order["order_total"];
        $booking->discountPrice = 0;
        $booking->firstname= "Thanh";
        $booking->lastname= "Thanh";
        $booking->pdfTicketPath="không có";
        $booking->save();
        
        

        $vnp_TxnRef = $booking->id; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Mua vé cho sự kiện ";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = (int)$order["order_total"]* 100;
        $vnp_Locale = "vn";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];


        
        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
        // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        // return redirect($vnp_Url);
        // echo json_encode($returnData);
        return response()->json([
            'status' => 'success',
            'redirectURL' => $vnp_Url,
        ]);
    } 
    
    public function vnpayIPN()
    {
        $inputData = array();
        $returnData = array();
        $data = $_REQUEST;
        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
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
        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        //$secureHash = md5($vnp_HashSecret . $hashData);
        $secureHash = hash('sha256',$vnp_HashSecret . $hashData);
        $Status = 0;
        $orderId = $inputData['vnp_TxnRef'];

        try {
            //Check Orderid    
            //Kiểm tra checksum của dữ liệu
            if ($secureHash == $vnp_SecureHash) {
                //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId            
                //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
                //Giả sử: $order = mysqli_fetch_assoc($result);   
                $order = NULL;
                if ($order != NULL) {
                    if ($order["Status"] != NULL && $order["Status"] == 0) {
                        if ($inputData['vnp_ResponseCode'] == '00') {
                            $Status = 1;
                        } else {
                            $Status = 2;
                        }
                        //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
                        //
                        //
                        //
                        //Trả kết quả về cho VNPAY: Website TMĐT ghi nhận yêu cầu thành công                
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success';
                    } else {
                        $returnData['RspCode'] = '02';
                        $returnData['Message'] = 'Order already confirmed';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
                    } else {
                        $returnData['RspCode'] = '97';
                        $returnData['Message'] = 'Chu ky khong hop le';
                    }
            } catch (Exception $e) {
                $returnData['RspCode'] = '99';
                $returnData['Message'] = 'Unknow error';
            }
            //Trả lại VNPAY theo định dạng JSON
            echo json_encode($returnData);
            }

    public function vnpayReturn()
    {
        $booking = Booking::find($_GET['vnp_TxnRef']);
        if($_GET['vnp_ResponseCode']=="00")
        {
            $booking->status=1;
            $booking->save();

        }
        $eventId =Session::get('eventId');
        $order = session()->get('ticket_order_' . $eventId);

        foreach ($order['tickets'] as $ticket) {
            $bookingDetail = new BookingDetail;
            $bookingDetail->bookingId=$_GET['vnp_TxnRef'];
            $bookingDetail->ticketClassId=$ticket["ticket_id"];
            $bookingDetail->quantity=$ticket["quantity"];
            $bookingDetail->save();
            for($i=0;$i<$ticket["quantity"];$i++)
            {
                $attendee = new Attendee;
                $attendee->ticketCode = md5(rand()); 
                $attendee->bookingId = $_GET['vnp_TxnRef']; 
                $attendee->eventId = $booking->eventId; 
                $attendee->ticketClassId = $ticket["ticket_id"]; 
                $attendee->firstName = "Null";
                $attendee->lastName = "Null";
                $attendee->email = "Null";
                $attendee->pdfTicketPath = "Null";
                $attendee->save();
            }
        }
        return view("vnpay.vnpay_return");
    }

}
