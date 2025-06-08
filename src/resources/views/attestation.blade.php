<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figma</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/attestation.css') }}" />
</head>
<body>
    <header>
        <img src="{{ asset('storage/image/logo.svg') }}" alt="coachtech">
    </header>
    <main>
        <form class="form">
            <p>登録していただいたメールアドレスに認証メールを送付しました。</br>メール認証を完了してください。</p>
            <input type="submit" value="認証はこちらから">
            <div>
                <a href="">認証メールを再送する</a>
            </div>
        </form>
    </main>
</body>
</html>