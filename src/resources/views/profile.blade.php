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
    </div>
    <div class="change">
        <a href="/mypage/profile" class="change__button">プロフィールを編集</a>
    </div>
</div>
<div class="profile__button">
    <a href="{{ route('profile.items') }}?page=sell" class="profile__btn {{ request()->query('page') == 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('profile.items') }}?page=buy" class="profile__btn {{ request()->query('page') == 'buy' ? 'active' : '' }}">購入した商品</a>
</div>
<div class="profile__image">
    @foreach ($items as $item)
    <div class="product-content">
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
    </div>
    @endforeach
</div>
@endsection