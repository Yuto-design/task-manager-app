# タスク管理アプリ（Laravel × Docker × CI）

## 概要

本リポジトリは、Laravel を用いて作成した**タスク管理 Web アプリケーション**です。
Docker による開発環境の標準化と、GitHub Actions を用いた CI（継続的インテグレーション）を導入し、
**技術更新や環境差異に強い Web アプリ基盤**の構築を目的としています。

本アプリでは、タスクの追加・編集・削除・状態管理（todo / doing / done）を行うことができます。

---

## 使用技術

### バックエンド

* PHP 8.2
* Laravel
* MySQL 8.0

### フロントエンド

* HTML / CSS
* JavaScript（ES Modules）
* Fetch API

### インフラ・開発環境

* Docker / Docker Compose
* Nginx
* phpMyAdmin

### CI

* GitHub Actions

---

## 動作環境について

> [!NOTE]
> 本プロジェクトは **macOS / Linux** 環境を前提としている。  
> Windows 環境では **WSL2 の使用を推奨**する。

---

## 機能一覧

* タスクの追加
* タスクの編集（title / description）
* タスクの削除
* ステータス管理（todo / doing / done）
* データベース（MySQL）への永続化

---

## 画面構成

* タスク一覧（テーブル表示）
* タスク追加フォーム
* 編集・削除ボタン
* ステータス選択（select）

---

## ディレクトリ構成（抜粋）

アプリに必要な主なフォルダ・ファイルです。

<pre><code>
.
├── app/                          # Laravel アプリケーション本体
│   ├── Http/
│   │   ├── Controllers/          # コントローラ
│   │   │   ├── TaskController.php
│   │   │   ├── ProfileController.php
│   │   │   └── Auth/             # 認証（ログイン・登録・パスワード等）
│   │   ├── Requests/             # フォームリクエスト（Auth, ProfileUpdate 等）
│   │   └── Responses/            # ログイン応答など
│   ├── Models/                   # Eloquent モデル
│   │   ├── Task.php
│   │   └── User.php
│   ├── Repositories/             # タスクの永続化（Repository パターン）
│   │   ├── TaskRepositoryInterface.php
│   │   └── EloquentTaskRepository.php
│   ├── Services/                 # ビジネスロジック
│   │   └── TaskService.php
│   ├── View/Components/         # Blade レイアウト用コンポーネント
│   │   ├── AppLayout.php
│   │   └── GuestLayout.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/                    # 起動・キャッシュ（app.php, providers.php 等）
├── config/                       # 設定（app, auth, database, session 等）
├── database/
│   ├── migrations/               # マイグレーション（users, tasks, cache, jobs）
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── factories/
│       └── UserFactory.php
├── public/                       # 公開ディレクトリ（Web ルート）
│   ├── index.php                 # エントリポイント
│   ├── css/
│   │   └── style.css
│   ├── js/                       # タスク UI 用 JavaScript（ES Modules）
│   │   ├── main.js
│   │   ├── api/taskApi.js
│   │   ├── ui/taskView.js, message.js
│   │   └── events/taskEvents.js
│   └── build/                    # Vite ビルド成果物（app.js, app.css）
├── resources/
│   ├── views/                    # Blade テンプレート
│   │   ├── layouts/              # app, guest, navigation
│   │   ├── auth/                 # ログイン・登録・パスワードリセット等
│   │   ├── tasks/                # タスク一覧（index.blade.php）
│   │   ├── profile/              # プロフィール編集
│   │   └── components/          # ボタン・入力・モーダル等の UI 部品
│   ├── css/app.css
│   └── js/app.js, bootstrap.js
├── routes/
│   ├── web.php                   # Web ルート（ダッシュボード・タスク画面）
│   ├── api.php                   # タスク API（GET/POST/PUT/DELETE）
│   ├── auth.php                  # 認証ルート（login, register 等）
│   └── console.php
├── storage/                      # ログ・キャッシュ・セッション・ビューキャッシュ（実行時生成）
├── tests/
│   └── Feature/                  # 機能テスト（TaskApiTest, Auth 等）
├── docker/                       # Docker 設定（php, nginx, db, pma）
├── docker-compose.yml
├── .env, .env.example            # 環境変数
├── composer.json, package.json
├── artisan
├── vite.config.js, tailwind.config.js
├── .github/workflows/ci.yml      # CI 設定
└── README.md
</code></pre>

---

## 開発環境の起動方法

```bash
# コンテナ起動
docker compose up -d

# マイグレーション実行
docker compose exec app php artisan migrate
```

### 環境変数（.env）について
> [!WARNING]
> .env の DB_CONNECTION が sqlite のままだと、
> MySQL コンテナを起動しても データベースが使用されない。
> 必ず mysql に変更すること。
> ```bash
> DB_CONNECTION=mysql
> DB_HOST=db
> DB_PORT=3306
> DB_DATABASE=task_manager
> DB_USERNAME=laravel
> DB_PASSWORD=secret
> ```

### アクセス方法

* Web アプリ: [http://localhost:8080/login](http://localhost:8080/login)
* phpMyAdmin: [http://localhost:8081](http://localhost:8081)

  * サーバー: db
  * ユーザー名: root
  * パスワード: root

---

## データベース構成
tasksテーブル
|カラム名|型|説明|
|----|----|----|
|id|bigint|タスクID|
|title|string|タスク名|
|description|text|詳細|
|status|string|todo / doing / done|
|created_at|timestamp|作成日時|
|updated_at|timestamp|更新日時|

---

## API概要
|Method|Endpoint|説明|
|----|----|----|
|GET|`/api/tasks`|タスク一覧取得|
|POST|`/api/tasks`|タスク追加|
|PUT|`/api/tasks/{id}`|タスク更新|
|DELETE|`/api/tasks/{id}`|タスク削除|

---

## CI（GitHub Actions）について

本リポジトリでは GitHub Actions を用いた CI を構築しています。

### CI の処理内容

* コードのチェックアウト
* PHP 環境構築
* Composer 依存関係のインストール
* MySQL サービス起動
* `.env` の CI 用設定
* キャッシュ・セッションの無効化
* マイグレーション実行
* テスト実行

### CI フロー

```
Push / Pull Request
        ↓
GitHub Actions 起動
        ↓
MySQL コンテナ起動
        ↓
Migration
        ↓
Test 実行
        ↓
成功（✔） / 失敗（✖）
```

> [!WARNING]
> CI 環境で CACHE_STORE=database を使用すると、
> cache テーブル未作成によりエラーが発生する。
> ```bash
> CACHE_STORE=array
> SESSION_DRIVER=array
> QUEUE_CONNECTION=sync
> ```

> [!TIP]
> CI では DB キャッシュ・セッションを無効化し、
> テストの安定性と再現性を優先している。

---

## 研究テーマとの関連

本アプリケーションは、以下の研究テーマに基づいて設計されています。

> 技術更新に強い Web アプリ基盤の検討

* Docker により開発環境をコード化
* CI により品質を自動担保
* 環境差異を最小化した構成

これにより、将来的な `PHP` / `Laravel` / `MySQL` のバージョン更新にも対応しやすい基盤を実現しています。

評価用の構成スナップショット（従来構成の説明）は [docs/legacy-architecture.md](docs/legacy-architecture.md) を参照してください。

---

## テスト・静的解析

* **PHPUnit**: `composer test`（`php artisan test`）で Feature/Unit テストを実行。タスク API は `tests/Feature/TaskApiTest.php` で検証。
* **PHPStan**: `composer phpstan` で PHP の静的解析を実行（`app/` を対象、レベル 5）。初回または `composer.json` 変更後は `composer update` で `composer.lock` を更新すること。
* **ESLint**: `npm run lint` で `public/js` の JavaScript をチェック。
* **Jest**: `npm test` でフロントエンドのユニットテストを実行（`public/js/__tests__/`）。

CI（GitHub Actions）では上記に加え、PHPStan・ESLint・Jest を自動実行します。

## 今後の課題（発展）

* ユーザー認証機能の追加
* API テストの拡充
* CD（自動デプロイ）の導入
* UI / UX の改善

---

## 備考

本リポジトリは学習・研究目的で作成されたものです。
