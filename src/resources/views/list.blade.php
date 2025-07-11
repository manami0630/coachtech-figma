@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}" />
@endsection

@section('content')
<div class="list__button">
    <a href="{{ route('items.index') }}" class="list__btn {{ request()->query('page') ? '' : 'active' }}">おすすめ</a>
    <a href="{{ route('items.index') }}?page=mylist" class="list__btn {{ request()->query('page') == 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>
<div class="list__image">
    @foreach ($items as $item)
    <div class="product-content">
        <a href="/item/{{$item->id}}" class="product-link">
            @if($item->status == 'sold')
            <div class="sold-label">
                Sold
            </div>
            @endif
            <img id="image-preview" src="{{ asset('storage/' . $item->image) }}">
            <div class="item-name">
                <p>{{$item->name}}</p>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection