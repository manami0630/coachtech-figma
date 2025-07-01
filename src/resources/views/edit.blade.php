@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}" />
@endsection

@section('content')
<form class="form" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
@csrf
@method('PUT')
    <div class="form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <div class="form__group">
        <div class="form__img">
            @if ($address)
            <img id="image-preview" src="{{ asset('storage/' . $user->profile_image) }}" style="max-width:200px;">
            @else
            <img id="image-preview" src="{{ asset('storage/default-image.jpg') }}">
            @endif
            <input type="file" id="upload-image" class="image" name="profile_image" accept="image/*" style="display: none;">
            <button type="button" id="upload-button" class="file-btn">画像を選択する</button>
            <div class="form__error">
                @error('image')
                {{ $message }}
                @enderror
            </div>
        </div>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="name">ユーザー名</label>
        <input class="profile-form__input" type="text" name="name" id="name" value="{{$user->name ??''}}">
        <div class="form__error">
            @error('name')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="postal_code">郵便番号</label>
        <input class="profile-form__input" type="text" name="postal_code" id="postal_code" value="{{$address->postal_code ??''}}">
        <div class="form__error">
            @error('postal_code')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="address">住所</label>
        <input class="profile-form__input" type="text" name="address" id="address" value="{{$address->address ??''}}">
        <div class="form__error">
            @error('address')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="profile-form__label" for="building_name">建物名</label>
        <input class="profile-form__input" type="text" name="building_name" id="building_name" value="{{$address->building_name ??''}}">
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
<script>
    document.getElementById('upload-button').addEventListener('click', function() {
        document.getElementById('upload-image').click();
    });

    document.getElementById('upload-image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection