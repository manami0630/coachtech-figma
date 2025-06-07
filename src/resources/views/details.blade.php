@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/details.css') }}" />
@endsection

@section('content')
<div class="details__content">
    <div class="details__image">
        <img id="image-preview" src="{{ asset('storage/' . $item->image) }}" style="max-width: 100%;">
    </div>
    <div class="details">
        <form class="form" action="/purchase/{{$item->id}}" method="post">
        @csrf
            <div class="form__group">
                <input class="name" type="text" name="name"  value="{{ $item->name }}" readonly>
                <input class="brand_name" type="text" name="brand_name"  value="{{ $item->brand_name }}" readonly>
                <input class="price" type="text" name="price"  value="¥{{ $item->price }} (税込)" readonly>
                <div class="form__group">
                    <img class="like-btn" data-item-id="{{ $item->id }}" src="{{ asset('storage/image/icons8-star-50.png') }}" alt="star">
                    <img src="{{ asset('storage/image/icons8-topic-50.png') }}" alt="topic">
                </div>
                <div class="count">
                    <div class="count__star">{{ $likesCount }}</div>
                    <div class="count__topic">{{ $comments->count() }}</div>
                </div>
                <div class="form__group">
                    <input class="form__btn" type="submit" value="購入手続きへ">
                </div>
            </div>
        </form>
        <div class="form__group">
            <label class="form__label">商品説明</label>
            <input class="description" type="text" name="description"  value="{{ $item->description }}" readonly>
        </div>
        <div class="form__group">
            <label class="form__label">商品の情報</label>
            <div class="group__flex">
                <label class="label">カテゴリー</label>
                @foreach ($item->categories as $category)
                <div class="category">
                    {{ $category->name }}
                </div>
                @endforeach
            </div>
            <div class="group__flex">
                <label class="label">商品の状態</label>
                <input type="text" name="condition"  value="{{ $item->condition }}" readonly>
            </div>
        </div>
        <div class="comments">
            @if(isset($comments) && $comments->count())
            <div class="comments__count">コメント({{ $comments->count() }})</div>
                @foreach($comments as $comment)
                <div class="comment">
                    <img src="{{ $comment->address->profile_image ?? 'default-profile.png' }}" style="width:40px; height:40px; border-radius:50%;">
                    <strong>{{ $comment->user->name }}</strong>
                    <p>{{ $comment->content }}</p>
                </div>
                @endforeach
            @else
                <p>まだコメントはありません。</p>
            @endif
        </div>
        @if(auth()->check())
        <form class="form" action="{{ route('comments.store') }}" method="post">
        @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <div class="form__group">
                <label class="comment_label">商品へのコメント</label>
                <textarea name="comment" id="" cols="30" rows="10">{{ old('comment') }}</textarea>
            </div>
            <div class="form__error">
            @error('comment')
            {{ $message }}
            @enderror
            </div>
            <div class="form__group">
                <input class="form__btn" type="submit" value="コメントを送信する">
            </div>
        </form>
        @else
        <div class="form__group">
            <label class="comment_label">商品へのコメント</label>
            <textarea name="comment" id="" cols="30" rows="10">{{ old('comment') }}</textarea>
        </div>
        <div class="form__group">
            <form action="{{ route('login') }}" method="get">
                <input class="form__btn" type="submit" value="コメントを送信する" />
            </form>
        </div>
        @endif
        <script>
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.dataset.itemId;

                    fetch('/likes', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ item_id: itemId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success){
                            alert('いいねしました！');
                        } else {
                            alert('エラーが発生しました。');
                        }
                    });
                });
            });
        </script>
    </div>
</div>
@endsection