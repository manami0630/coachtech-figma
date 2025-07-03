# coachtechフリマ

## 環境構築

### Dockerビルド
1. `git clone git@github.com:manami0630/coachtech-figma.git`
2. `docker-compose up -d --build`

* MySQLは、OSによって起動しない場合があるので、それぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`
7. `php artisan storage:link`

* .envに以下の値を入力してください。
- MAIL_FROM_ADDRESS=例: your-email@example.com
- STRIPE_PUBLIC_KEY=例: pk_test_xxxxxxxxxxxxxxxxxxxxx
- STRIPE_SECRET_KEY=例: sk_test_xxxxxxxxxxxxxxxxxxxxx
  
※ 実運用には、適切なメールアドレスやStripeの本番用APIキーにアップデートしてください。

## 使用技術
- PHP 7.4.9
- Laravel 8.83.8
- MySQL 8.0.26

## ER図
![スクリーンショット 2025-06-10 150919](https://github.com/user-attachments/assets/c1ddc759-74d6-41e0-9596-a7e80913a37c)


## URL
- 開発環境: [http://localhost](http://localhost)
- phpMyAdmin: [http://localhost:8080/](http://localhost:8080/)
- mailhog:  [http://localhost:8025/](http://localhost:8025/)
