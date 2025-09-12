@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}" />
@endsection

@section('content')
<div class="chat_content">
    <div class="left-contents">
        <p class="other-transactions">その他の取引</p>
        @foreach ($transactions as $order)
            @if ($order->item && $order->item->user_id == auth()->id() && $order->status === '取引中')
                <a class="item-link" href="/item/chat/{{ $order->id }}">{{ $order->item->name }}</a>
            @endif
        @endforeach
    </div>
    <div class="right-contents">
        <div class="heading">
            <div>
                <img src="{{ Auth::id() === $item->user_id ? asset('storage/' . $order->user->profile_image ) : asset('storage/' . $item->user->profile_image ) }}">「{{ Auth::id() === $item->user_id ? $order->user->name : $item->user->name ?? '' }}」さんとの取引画面
            </div>
            @php
                $order = \App\Models\Order::where('item_id', $item->id)
                ->where(function ($q) {
                    $q->where('user_id', auth()->id())
                    ->orWhereHas('item', function ($q2) {
                        $q2->where('user_id', auth()->id());
                    });
                })
                ->first();
            @endphp
            @php
                $isSeller = $order->item->user_id === auth()->id();
                $buyerId = $order->user_id;
                $sellerId = $order->item->user_id;

                $buyerHasEvaluated = \App\Models\Evaluation::where('order_id', $order->id)
                ->where('user_id', $buyerId)
                ->exists();

                $sellerHasEvaluated = \App\Models\Evaluation::where('order_id', $order->id)
                ->where('user_id', $sellerId)
                ->exists();

                $shouldShowModal = (!$isSeller && !$buyerHasEvaluated) || ($isSeller && $buyerHasEvaluated && !$sellerHasEvaluated);
            @endphp
            @if ($order)
                @if ($shouldShowModal)
                    @if (!$isSeller)
                        <a class="completion_button" href="#{{$order->id}}">取引を完了する</a>
                    @endif
                    <div class="modal" id="{{$order->id}}">
                        <a href="#!" class="modal-overlay"></a>
                        <div class="modal__inner">
                            <div class="modal__content">
                                <form class="modal__form" action="{{ route('evaluations.store') }}" method="post">
                                @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <div class="modal-form__group">
                                        <p class="modal_heading">取引が完了しました。</p>
                                    </div>
                                    <div class="modal-form__group">
                                        <p class="modal-question">今回の取引相手はどうでしたか？</p>
                                        <div class="rating mb-3" id="ratingStars" aria-label="評価">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star" data-value="{{ $i }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="0">
                                    <div class="form_send-btn">
                                        <input class="send-btn" type="submit" value="送信する" name="send" id="submitReviewBtn">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="flex">
            <div class="item_image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
            </div>
            <div class="item_details">
                <p class="item_name">{{ $item->name }}</p>
                <p class="item_price">¥{{ $item->price }} (税込)</p>
            </div>
        </div>
        <div class="chat">
            <div class="chat-wrapper">
                <div class="text">
                    @if(isset($chats) && $chats->count())
                        @foreach($chats as $chat)
                            <div class="chat-row" id="chat-{{ $chat->id }}">
                                @if(Auth::check() && Auth::id() === $chat->user->id)
                                    <div class="display-mode" id="display-{{ $chat->id }}">
                                        <strong class="user_name">{{ $chat->user->name ?? '' }}</strong>
                                        <img class="profile_image" src="{{ asset('storage/' . $chat->user->profile_image) }}" width="150">
                                        @if ($chat->image)
                                            <img class="chat_image" src="{{ Storage::url('public/chats/' . $chat->image) }}" width="150">
                                        @endif
                                        @php
                                            $isMine = optional($chat->user)->id === optional(auth()->user())->id;
                                        @endphp
                                        <div class="chat-content {{ $isMine ? 'mine' : '' }}">{{ $chat->content }}</div>
                                            <div class="form-button">
                                                <button class="button-edit" type="button" data-id="{{ $chat->id }}">編集</button>
                                                <form action="{{ route('chats.destroy', $chat) }}" method="POST" style="display:inline;">
                                                @csrf
                                                    @method('DELETE')
                                                    <button class="button-delete" type="submit">削除</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="display_mode" id="display-{{ $chat->id }}">
                                        <img class="profile_image" src="{{ asset('storage/' . $chat->user->profile_image) }}" width="150">
                                        <strong class="user_name">{{ $chat->user->name ?? '' }}</strong>
                                        @if ($chat->image)
                                            <img class="chat-image" src="{{ Storage::url('public/chats/' . $chat->image) }}" alt="chat image" width="150">
                                        @endif
                                        @php
                                            $isMine = optional($chat->user)->id === optional(auth()->user())->id;
                                        @endphp
                                        <div class="chat-content {{ $isMine ? 'mine' : '' }}">{{ $chat->content }}</div>
                                    </div>
                                @endif
                                <div class="edit-mode" id="edit-{{ $chat->id }}" style="display: none;">
                                    <form action="{{ route('chats.update', $chat) }}" method="POST" class="inline-edit-form">
                                    @csrf
                                        @method('PUT')
                                        <strong class="user-name">{{ $chat->user->name ?? '' }}</strong>
                                        <img class="profile_image" src="{{ asset('storage/' . $chat->user->profile_image ) }}" width="150">
                                        @if (!empty($chat->image))
                                            <img class="chat_image" src="{{ Storage::url('public/chats/' . $chat->image) }}" width="150">
                                        @endif
                                        <div>
                                            <button type="button" class="cancel-edit" data-id="{{ $chat->id }}">キャンセル</button>
                                            <button type="submit">更新</button>
                                            <input class="chat-content" type="text" name="content" value="{{ $chat->content }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div>
                <form class="form" action="{{ route('chats.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <div class="form__group">
                        <div class="form__error">
                            @error('content')
                            {{ $message }}
                            @enderror
                        </div>
                        <input class="content" type="text" name="content" id="chatInput" placeholder="取引メッセージを記入してください">
                    </div>
                    <div class="form__img">
                        <div class="form__error" id="image-error"></div>
                        <img id="image-preview" src="">
                        <input type="file" id="upload-image" class="image" name="image" accept="image/*" style="display: none;">
                        <button type="button" id="upload-button" class="file-btn">画像を追加</button>
                    </div>
                    <div class="form_group">
                        <input class="form__btn" type="image" src="{{ asset('storage/image/e99395e98ea663a8400f40e836a71b8c4e773b01.jpg') }}" alt="送信">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // === チャット入力の保存と送信 ===
        const chatInput = document.getElementById('chatInput');
        const chatForm = document.querySelector('.form');
        const imagePreview = document.getElementById('image-preview');

        const savedContent = localStorage.getItem('chatContent');
        if (savedContent) chatInput.value = savedContent;

        chatInput.addEventListener('input', function () {
            localStorage.setItem('chatContent', chatInput.value);
        });

    /*chatForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(chatForm);

        fetch(chatForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (response.ok) {
                chatInput.value = '';
                localStorage.removeItem('chatContent');
                chatForm.reset();
                imagePreview.src = '';
                location.reload();
            } else {
                alert('保存に失敗しました');
            }
        })
        .catch(error => {
            console.error('送信エラー:', error);
            alert('通信エラーが発生しました');
        });
    });*/

        // === 画像アップロードとプレビュー ===
        const uploadButton = document.getElementById('upload-button');
        const uploadImage = document.getElementById('upload-image');

        uploadButton.addEventListener('click', () => uploadImage.click());

        uploadImage.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // === チャット編集表示切り替え ===
        document.querySelectorAll('.button-edit').forEach(button => {
            button.addEventListener('click', function () {
                const chatId = this.dataset.id;
                document.getElementById(`display-${chatId}`).style.display = 'none';
                document.getElementById(`edit-${chatId}`).style.display = 'block';
            });
        });

        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', function () {
                const chatId = this.dataset.id;
                document.getElementById(`display-${chatId}`).style.display = 'block';
                document.getElementById(`edit-${chatId}`).style.display = 'none';
            });
        });

        // === 星評価の処理 ===
        const ratingContainer = document.getElementById('ratingStars');
        const stars = ratingContainer ? ratingContainer.querySelectorAll('.star') : [];
        const ratingInput = document.getElementById('ratingInput');
        const ratingForm = document.querySelector('.modal__form');
        let currentRating = 0;

        function updateStars(rating) {
            stars.forEach(star => {
                const val = Number(star.dataset.value);
                star.style.color = (val <= rating) ? '#ffd700' : '#e4e4e4';
            });
            if (ratingInput) ratingInput.value = rating;
        }

        stars.forEach(star => {
            star.addEventListener('click', () => {
                currentRating = Number(star.dataset.value);
                updateStars(currentRating);
            });
            star.addEventListener('mouseover', () => updateStars(Number(star.dataset.value)));
            star.addEventListener('mouseout', () => updateStars(currentRating));
        });

        updateStars(0);

        if (ratingForm) {
            ratingForm.addEventListener('submit', function (e) {
                if (Number(ratingInput.value) < 1) {
                    e.preventDefault();
                    alert('星評価を選択してください。');
                }
            });
        }
    });

    // === jQueryによるチャット送信と新着件数更新 ===
    $(function () {
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    updateNewMessageCount();
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        function updateNewMessageCount() {
            $.ajax({
                url: '/new-message-count',
                method: 'GET',
                success: function (count) {
                    $('#new-message-count').text(count);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isSeller = {{ $isSeller ? 'true' : 'false' }};
        const hasEvaluated = {{ $hasEvaluated ? 'true' : 'false' }};
        const modalId = '{{ $order->id }}';

        if (isSeller && !hasEvaluated) {
            location.hash = modalId;
        }
    });
</script>
<script>
    fetch('{{ route("chat.read", ["item_id" => $item->id]) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('{{ $order->id }}');
        if (modal) modal.style.display = 'block';
    });
</script>
@endsection