@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section('content')
<div class="user">
    <div class="user__img">
        <img id="user_image-preview" src="{{ asset('storage/' . $user->profile_image ) }}">
    </div>
    <div class="user__name">
        {{$user->name}}
        @php
            $full = isset($fullStars) ? (int) $fullStars : 0;
            $half = isset($hasHalf) ? (bool) $hasHalf : false;
        @endphp
        <div class="rating mb-3" id="ratingStars" aria-label="評価" data-average="{{ number_format($averageRating ?? 0, 2) }}">
            @for ($i = 1; $i <= 5; $i++)
                @php
                    $state = 'empty';
                    if ($i <= $full) {
                        $state = 'full';
                    } elseif ($half && $i == $full + 1) {
                        $state = 'half';
                    }
                @endphp
                <span class="star {{ $state }}" data-value="{{ $i }}">★</span>
            @endfor
        </div>
    </div>
    <div class="change">
        <a href="/mypage/profile" class="change__button">プロフィールを編集</a>
    </div>
</div>
<div class="profile__button">
    <a href="{{ route('profile.items') }}?page=sell" class="profile__btn {{ request()->query('page') == 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('profile.items') }}?page=buy" class="profile__btn {{ request()->query('page') == 'buy' ? 'active' : '' }}">購入した商品</a>
    <a href="{{ route('profile.items') }}?page=transaction" class="profile__btn {{ request()->query('page') == 'transaction' ? 'active' : '' }}">取引中の商品
    @if($totalUnreadCount > 0)
        <span class="badge">{{ $totalUnreadCount }}</span>
    @endif</a>
</div>
<div class="profile__image">
    @foreach ($items as $item)
        @php
            $order = \App\Models\Order::where('item_id', $item->id)->first();
        @endphp
        <div class="product-content">
            @if(request()->query('page') == 'transaction')
                <a href="{{ route('chat.show', ['id' => $order->id]) }}" class="product-link">
                    @if($item->status == 'sold')
                        <div class="sold-label">
                            Sold
                        </div>
                    @endif
                    <img id="image-preview" src="{{ asset('storage/' . $item->image) }}">
                    @php
                        $unread = $unreadCounts[$item->id] ?? 0;
                    @endphp
                    @if($unread > 0)
                        <span class="badge">{{ $unread }}</span>
                    @endif
                    <div class="product-name">
                        <p>{{ $item->name }}</p>
                    </div>
                </a>
            @else
                <a href="/item/{{ $item->id }}" class="product-link">
                    @if($item->status == 'sold')
                        <div class="sold-label">
                            Sold
                        </div>
                    @endif
                    <img id="image-preview" src="{{ asset('storage/' . $item->image) }}">
                    <div class="product-name">
                        <p>{{ $item->name }}</p>
                    </div>
                </a>
            @endif
        </div>
    @endforeach
</div>
@endsection