<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figma</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header-logo">
            <img src="{{ asset('storage/image/logo.svg') }}" alt="coachtech">
        </div>
        <form class="search-box" action="{{ route('items.search') }}" method="GET">
            <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}"/>
            <div id="results"></div>
        </form>
        <form class="nav-links" action="/logout" method="post">
        @csrf
            @if (Auth::check())
            <button class="btn" type="submit">ログアウト</button>
            @else
            <a href="/login" class="btn">ログイン</a>
            @endif
            <a href="/mypage" class="btn">マイページ</a>
            <a href="/sell" class="button">出品</a>
        </form>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.querySelector('.search-box input[name="keyword"]');
        var resultsDiv = document.getElementById('results');

        input.addEventListener('input', function() {
            var keyword = this.value;
            if (keyword.length >= 2) {
                fetch('{{ route("search-autocomplete") }}?keyword=' + encodeURIComponent(keyword))
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        data.forEach(item => {
                            var itemElement = document.createElement('div');
                            itemElement.textContent = item.name;
                            itemElement.addEventListener('click', function() {
                                input.value = this.textContent;
                                resultsDiv.innerHTML = '';
                            });
                            resultsDiv.appendChild(itemElement);
                        });
                    });
            } else {
                resultsDiv.innerHTML = '';
            }
        });
    });
</script>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>