@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibit.css') }}" />
@endsection

@section('content')
<form class="form" action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
@csrf
    <div class="heading">
        <h2>商品の出品</h2>
    </div>
    <div class="form__group">
        <label class="form__label">商品画像</label>
        <div class="form__img">
            <img id="image-preview" src="">
            <input type="file" id="upload-image" class="image" name="image" accept="image/*" style="display: none;">
            <button type="button" id="upload-button" class="file-btn">画像を選択する</button>
        </div>
        <div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__heading">
        <h3>商品の詳細</h3>
    </div>
    <div class="form__group">
        <label class="form__label">カテゴリー</label>
        <input type="hidden" id="category-input" name="category" value="{{ old('category') }}">
        <div class="categories">
            @foreach ($categories as $category)
            <div class="category-item">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category_{{ $category->id }}" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'checked' : '' }}>
                <label for="category_{{ $category->id }}" class="category-label">
                    {{ $category->name }}
                </label>
            </div>
            @endforeach
        </div>
        <div class="form__error">
            @error('categories')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="form__label">商品の状態</label>
        <select class="condition" name="condition">
            <option value="">選択してください</option>
            <option value="良好" {{ old('condition')=='良好' ? 'selected' : '' }}>良好</option>
            <option value="目立った傷や汚れなし" {{ old('condition')=='目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり" {{ old('condition')=='やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
            <option value="状態が悪い" {{ old('condition')=='状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
        </select>
        <div class="form__error">
            @error('condition')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__heading">
        <h3>商品名と説明</h3>
    </div>
    <div class="form__group">
        <label class="form__label">商品名</label>
        <input class="form__text" type="text" name="name" value="{{ old('name') }}">
        <div class="form__error">
            @error('name')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="form__label">ブランド名</label>
        <input class="form__text" type="text" name="brand_name" value="{{ old('brand_name') }}">
        <div class="form__error">
            <!-- error -->
        </div>
    </div>
    <div class="form__group">
        <label class="form__label">商品の説明</label>
        <input class="form__text" type="text" name="description" value="{{ old('description') }}">
        <div class="form__error">
            @error('description')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <label class="form__label">販売価格</label>
        <input class="form__text" type="text" name="price" value="{{ old('price') }}">
        <div class="form__error">
            @error('price')
            {{ $message }}
            @enderror
        </div>
    </div>
    <div class="form__group">
        <input class="exhibit-form__btn" type="submit" value="出品する">
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