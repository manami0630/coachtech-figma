@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/change.css') }}" />
@endsection

@section('content')
<form class="form" action="{{ route('address.update') }}" method="post">
@csrf
    <div class="form__heading">
        <h2>住所の変更</h2>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="postal_code">郵便番号</label>
        <input class="profile-form__input" type="text" name="postal_code" id="postal_code">
        <div class="form__error">
            @error('postal_code')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="address">住所</label>
        <input class="profile-form__input" type="text" name="address" id="address">
        <div class="form__error">
            @error('address')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="building_name">建物名</label>
        <input class="profile-form__input" type="text" name="building_name" id="building_name">
        <div class="form__error">
            @error('building_name')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <input class="profile-form__btn" type="submit" value="更新する">
    </div>
</form>
@endsection