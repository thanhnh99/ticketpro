@extends('user.blade.user-detail.layout.master')
@section('pageTitle', 'TicketPro:Ticket bought')
@push('css')
<link href="/css/user/user-detail/ticket-bought.css" rel="stylesheet">
@endpush

@push('scripts')

@endpush

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-ticket icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Vé đã mua cho các sự kiện sắp tới
                @if(count($data)<1)

                    <div class="page-title-subheading">Hiện bạn chưa đặt vé của sự kiện nào, bạn có thể bắt đầu đặt vé của các sự kiện dưới đây.
                    </div>
                @endif
                </div>
            </div>   
        </div>
    </div> 
    <div class="main-events-bought">
    @foreach($data as $attendee)
        <div class="ticket-detail">
            <span class= 'r-date'>{{$attendee->event()->get()->first()->startTime}}</span>
            <span class= 'r-line'></span>
            <div class="event-detail">
                <div class="logo-left">
                    <a href="{{$attendee->event()->get()->first()->organizer->get()->first()->website}}">
                        <img src="{{$attendee->event()->get()->first()->organizer->get()->first()->profileImage }}" alt="" class="logo">
                    </a>
                </div>
                <div class="event-info">
                    <div class="event-title">
                        {{$attendee->event()->get()->first()->name}}
                    </div>
                    <div class="event-time">
                        <i class="fas fa-clock"></i>
                        {{$attendee->event()->get()->first()->startTime}}
                    </div>
                    <div class="event-location">
                        <i class="fas fa-map-marker-alt"></i>
                        Cầu Đất Farm - Truong Tho Village, Tram Hanh Commune, Da Lat City, Lam Dong Province., Thành Phố Đà Lạt, Tỉnh Lâm Đồng
                    </div>
                    <div class="event-location">
                        <i class="fas fa-ticket-alt"></i>
                        Code vé: {{$attendee->ticketCode}}
                    </div>
                    <div class="event-location">
                        <i class="fas fa-ticket-alt"></i>                        
                        Loại vé: {{$attendee->ticketClass()->get()->first()->type}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>

@endsection