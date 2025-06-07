@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}" />
@endsection

@section('content')
<div class="list__button">
    <a href="{{ route('items.index') }}" class="list__btn">おすすめ</a>
    <a href="{{ route('items.index') }}?tab=mylist" class="list__btn">マイリスト</a>
</div>
<div class="list__image">
    @foreach ($items as $item)
    <div class="product-content">
        <a href="/item/{{$item->id}}" class="product-link">
            <img id="image-preview" src="{{ asset('storage/' . $item->image) }}">
            <div class="item-name">
                <p>{{$item->name}}</p>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection