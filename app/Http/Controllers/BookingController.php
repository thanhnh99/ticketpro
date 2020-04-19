<?php

namespace App\Http\Controllers;

use App\Attendee;
use App\Booking;
use App\BookingDetail;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use App\Services\PaymentGateway\Payment;
use Carbon\Carbon;
use App\Event;
use App\Events\OrderCompletedEvent;
use App\ReservedTicket;
use App\TicketClass;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
class BookingController extends Controller
{
    public function validateTicket(Request $request, $eventId)
    {
        if(!Auth::user())
        {
            return response()->json([
                'status' => 'auth',
                'message' => 'Bạn cần đăng nhập để tiếp tục. Đăng nhập ngay!!!!',
                'new_token' => csrf_token(),
                'redirectURL' => route('login',['provider'=>'google'])
            ]);
        }
        if(count($request->tickets)==0){            
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần chọn vé để tiếp tục',
                'new_token' => csrf_token(),
            ], 400);
        }

        $tickets = $request->get("tickets");  
        $order_total = 0;
        $qty_total = 0;

        foreach($tickets as $ticket_ordered){
            $current_quantity = $ticket_ordered["quantity"];
            if($current_quantity < 1){
                continue;
            }
            $ticket = TicketClass::where('eventId', $eventId)->where('id', $ticket_ordered["ticket-class"])->first();
            if(!$ticket){
                // return Redirect::back()->withErrors(['msg_alert', 'Selected invalid ticket class']);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Selected invalid ticket class ',
                    'new_token' => csrf_token(),
                ], 400);
            }
            $max_per_person = $ticket->max_ticket;
            $quantity_available_validation_rules[$ticket_ordered["ticket-class"]] = [
                'numeric',
                'min:'. $ticket->minPerPerson,
                'max:'. $max_per_person
            ];
            $quantity_available_validation_messages = [
                $ticket_ordered["ticket-class"]. '.max' => "Số lượng vé tối đa bạn có thể mua là: ". $max_per_person,
                $ticket_ordered["ticket-class"]. '.min' => 'Bạn phải mua it nhất: '. $ticket->minPerPerson,
            ];
            $validator = Validator::make([$ticket_ordered["ticket-class"] => $ticket_ordered["quantity"]], 
                        $quantity_available_validation_rules, $quantity_available_validation_messages);
            if($validator->fails()){
                $validator_fail = true;
                $validator_fail_messages[] = $validator->messages()->toArray();
            }
            $order_total = $order_total + ($current_quantity * $ticket->price);
            $qty_total = $qty_total + $current_quantity;
            $ticket_details[] = [
                'ticket_id' => $ticket->id,
                'type' => $ticket->type,
                'quantity' => $current_quantity,
                'total_price' => $current_quantity * $ticket->price
            ];  
        }
        Session::put('eventId', $eventId);
        Session::put('ticket_order_'. $eventId, [
            'event_id' => $eventId,
            'tickets' => $ticket_details,
            'order_total' => $order_total,
            'quantity_total' => $qty_total,
        ]);

        return response()->json([
            'status' => 'success',
            'redirectURL' => route('create_payment',['eventId'=>$eventId]),
        ]);
    }

}

