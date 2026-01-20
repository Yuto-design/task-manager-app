# タスク管理アプリ（Laravel × Docker × CI）

## 概要

本リポジトリは、Laravel を用いて作成したタスク管理 Web アプリケーションです。
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

```
.
├── app/                # Laravel アプリケーション
├── database/
│   ├── migrations/     # マイグレーション
│   └── seeders/
├── public/             # フロントエンド
│   ├── index.html
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── api/
│       │   └── taskApi.js
│       ├── ui/
│       │   └── taskView.js
│       ├── events/
│       │   └── taskEvents.js
│       └── main.js
├── routes/
│   ├── web.php
│   └── api.php
├── docker/             # Docker 設定
├── docker-compose.yml
├── .github/workflows/  # CI 設定
└── README.md
```

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

* Web アプリ: [http://localhost:8080](http://localhost:8080)
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

## `welcome.blade.php`について
> [!NOTE]
> Laravel 標準の welcome.blade.php は、
> ルート / にアクセスした際の表示確認用ファイルである。
> 本プロジェクトでは API 中心構成のため、
> 内容は最小限または未使用としている。

## 研究テーマとの関連

本アプリケーションは、以下の研究テーマに基づいて設計されています。

> 技術更新に強い Web アプリ基盤の検討

* Docker により開発環境をコード化
* CI により品質を自動担保
* 環境差異を最小化した構成

これにより、将来的な `PHP` / `Laravel` / `MySQL` のバージョン更新にも対応しやすい基盤を実現しています。

---

## 今後の課題（発展）

* ユーザー認証機能の追加
* API テストの拡充
* CD（自動デプロイ）の導入
* UI / UX の改善

---

## 備考

本リポジトリは学習・研究目的で作成されたものです。
