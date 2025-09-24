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

## ダミーデータ概要
初期データは `php artisan db:seed` 実行時に登録されます。以下は主なダミーデータの内容です。

### ユーザー（users）
| 名前 | メールアドレス | パスワード |
|------|----------------|------------|
| 山田太郎 | yamada@example.com | password123 |
| 田中太郎 | tanaka@example.com | password456 |
| 佐藤太郎 | satou@example.com | password789 |

※ パスワードはすべて `bcrypt()` でハッシュ化されています。

---

### 住所（addresses）
| 名前 | 郵便番号 | 住所 |
|--------|--------|------|
| 山田太郎 | 〒838-0816 | 福岡県朝倉郡筑前町新町 |
| 田中太郎 | 〒813-0008 | 福岡県粕屋郡粕屋町内橋 |
| 佐藤太郎 | 〒807-0111 | 福岡県遠賀郡芦屋町白浜町 |

### 商品（items）
| 出品者 | 商品名 | 価格 | 説明 | 写真URL | 状態 |
|--------|--------|------|------|------------| ------ |
| 山田太郎 | 腕時計 | ¥15,000 | スタイリッシュなデザインのメンズ腕時計 | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg) | 良好 |
| 山田太郎 | HDD | ¥5,000 | 高速で信頼性の高いハードディスク | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg) | 目立った傷や汚れなし |
| 山田太郎 | 玉ねぎ3束 | ¥300 | 新鮮な玉ねぎ3束のセット | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg) | やや傷や汚れあり |
| 山田太郎 | 革靴 | ¥4,000 | クラシックなデザインの革靴 | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg) | 状態が悪い |
| 山田太郎 | ノートPC | ¥45,000 | 高性能なノートパソコン | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg) | 良好 |
| 田中太郎 | マイク | ¥8,000 | 高音質のレコーディング用マイク | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg) | 目立った傷や汚れなし |
| 田中太郎 | ショルダーバッグ | ¥3,500 | おしゃれなショルダーバッグ | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg) | やや傷や汚れあり |
| 田中太郎 | タンブラー | ¥500 | 使いやすいタンブラー | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg) | 状態が悪い |
| 田中太郎 | コーヒーミル | ¥4,000 | 手動のコーヒーミル | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg) | 良好 |
| 田中太郎 | メイクセット | ¥2,500 | 便利なメイクアップセット | [出品中](https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg) | 目立った傷や汚れなし |

---

### 商品カテゴリ（item_category）
| 商品名 | カテゴリ名 |
|--------|------------|
| 腕時計 | 家電 |
| HDD | 家電 |
| 玉ねぎ3束 | キッチン |
| 革靴 | ファッション, メンズ |
| ノートPC | 家電 |
| マイク | 家電 |
| ショルダーバッグ | ファッション, レディース |
| タンブラー | キッチン |
| コーヒーミル | キッチン |
| メイクセット | コスメ |

※ 商品は複数カテゴリに属することができます（多対多リレーション）

---

## テストアカウント
- name:山田太郎
- email:yamada@example.com
- password:password123

### PHPUnitを利用したテストに関して
以下のコマンド:
1. `docker-compose exec mysql bash`
2. `mysql -u root -p` - パスワードはrootと入力
3. `CREATE DATABASE demo_test;`
4. `php artisan key:generate --env=testing`
5. `php artisan migrate --env=testing`

## 使用技術
- PHP 8.4.4
- Laravel 8.83.8
- MySQL 8.0.26

## ER図
<img width="1009" height="693" alt="スクリーンショット 2025-09-12 000707" src="https://github.com/user-attachments/assets/3c08b892-e2ca-4f94-aa0e-3cda3add9888" />

## URL
- 開発環境: [http://localhost](http://localhost)
- phpMyAdmin: [http://localhost:8080/](http://localhost:8080/)
- mailhog:  [http://localhost:8025/](http://localhost:8025/)
