<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail</title>
</head>
<body>
    @component('mail::message')
        # 取引が完了しました

        商品名：{{ $order->item->name }}
        購入者：{{ $order->user->name }}
        評価：{{ $evaluation->rating }} ★

        詳細はマイページをご確認ください。
    @endcomponent
</body>
</html>