@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
<div>
    <form class="purchase__content" action="{{ route('orders.store') }}" method="post" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <div class="purchase">
            <div class="group">
                <div class="purchase__img">
                    <img id="image-preview" src="{{ asset('storage/' . $item->image) }}">
                </div>
                <div class="purchase__flex">
                    <input class="brand_name" type="text" name="brand_name"  value="{{ $item->name }}" readonly>
                    <input class="price" type="text" name="price"  value="¥{{ $item->price }}" readonly>
                </div>
            </div>
            <div class="purchase__group">
                <div class="purchase__label">支払い方法</div>
                <div>
                    <select class="method" name="payment_method">
                        <option value="">選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード支払い">カード支払い</option>
                    </select>
                </div>
                <div class="form__error">
                    @error('payment_method')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="purchase__group">
                <div class="flex">
                    <div class="purchase__label">配送先</div>
                    <div>
                        <a class="change" href="/purchase/address/{{$user->id}}">変更する</a>
                    </div>
                </div>
                <div class="information">
                    <div>
                        <input class="profile-form__input" type="text" name="postal_code" id="postal_code" value="〒{{$address->postal_code}}" readonly>
                    </div>
                    <div>
                        <input class="profile-form__input" type="text" name="address" id="address" value="{{$address->address}}" readonly>
                    </div>
                    <div>
                        <input class="profile-form__input" type="text" name="building_name" id="building_name" value="{{$address->building_name}}" readonly>
                    </div>
                    <div class="form__error">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                    @error('address')
                    {{ $message }}
                    @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form__purchase">
            <div class="group__flex">
                <label class="label">商品代金</label>
                <input type="text" name="price"  value="¥ {{ $item->price }}" readonly>
            </div>
            <div class="group__flex">
                <label class="label">支払い方法</label>
                <input type="text" name="payment_method_display" id="payment-method-input" readonly>
            </div>
            <input class="purchase-form__btn" type="submit" value="購入する">
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var methodSelect = document.querySelector('.method');
            var paymentMethodInput = document.getElementById('payment-method-input');

            methodSelect.addEventListener('change', function() {
            var selectedOption = methodSelect.options[methodSelect.selectedIndex];
            paymentMethodInput.value = selectedOption.text;
            });
        });
    </script>
</div>
@endsection