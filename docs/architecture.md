# タスク管理アプリ 構成図

本ドキュメントでは、タスク管理アプリのシステム全体・アプリケーション層・データフロー・CI の構成を図で示します。

---

## 1. システム全体構成図

クライアントから Docker コンテナ群までの構成です。ブラウザはポート 8080 で Nginx に接続し、Nginx が PHP リクエストを app コンテナ（PHP-FPM）に渡します。Laravel アプリは MySQL（db）に接続してデータを永続化します。

```mermaid
flowchart LR
    subgraph client [クライアント]
        Browser[ブラウザ]
        Cookie["Cookie（セッション）"]
        CSRF["CSRF Token"]
    end

    subgraph docker [Docker Compose]
        subgraph web [web - Nginx]
            Nginx[Nginx :80]
        end
        subgraph app [app - PHP-FPM]
            Laravel[Laravel]
        end
        subgraph db [db]
            MySQL[MySQL 8.0 :3306]
        end
        subgraph node [node]
            Node[Node 20]
        end
        subgraph pma [phpmyadmin]
            PhpMyAdmin[phpMyAdmin :80]
        end
    end

    Browser -->|"HTTP :8080"| Nginx
    Nginx -->|"FastCGI :9000"| Laravel
    Laravel -->|"PDO"| MySQL
    Browser -.-> Cookie
    Browser -.-> CSRF
    PhpMyAdmin -->|"HTTP"| MySQL
```

| コンテナ | 役割 | ポート（ホスト） |
|----------|------|------------------|
| web | Nginx。`/` を `public/` にマッピングし、`.php` を app:9000 に転送 | 8080 → 80 |
| app | PHP-FPM。Laravel（`public/index.php` → `bootstrap/app.php`） | - |
| db | MySQL 8.0。DB: task_manager | 3306 |
| node | Node 20（npm / Vite 等の開発用） | - |
| phpmyadmin | phpMyAdmin（DB 管理） | 8081 → 80 |

---

## 2. アプリケーション層の詳細図

### 2.1 フロントエンド

Blade で描画したタスク画面に、ES Modules の JavaScript が読み込まれ、Fetch API で Web ルート（`/tasks/list`, POST/PUT/DELETE `/tasks`）を呼び出します。

```mermaid
flowchart TB
    subgraph view [Blade]
        Index["tasks/index.blade.php"]
        Layout["layouts/app.blade.php"]
        Nav["layouts/navigation"]
    end

    subgraph js [JavaScript ES Modules]
        Main["main.js"]
        TaskApi["api/taskApi.js"]
        TaskView["ui/taskView.js"]
        TaskEvents["events/taskEvents.js"]
    end

    subgraph apiCalls [呼び出し URL]
        GetList["GET /tasks/list"]
        PostTask["POST /tasks"]
        PutTask["PUT /tasks/{id}"]
        DeleteTask["DELETE /tasks/{id}"]
    end

    Layout --> Nav
    Layout --> Index
    Index --> Main
    Main --> TaskApi
    Main --> TaskView
    Main --> TaskEvents
    TaskApi --> GetList
    TaskApi --> PostTask
    TaskApi --> PutTask
    TaskApi --> DeleteTask
    TaskEvents --> TaskApi
    TaskView -->|"renderTasks"| Index
```

- **main.js**: 起動時に `TaskApi.getAll()` → `renderTasks(tasks)`、`initTaskEvents()` で追加・保存・削除のイベントを登録。
- **taskApi.js**: `BASE_URL = "/tasks"`。一覧は `/tasks/list`、CRUD は `/tasks`。`X-CSRF-TOKEN` と `credentials: "same-origin"` でセッション認証。
- **taskView.js**: `renderTasks(tasks)` で `#task-list` にテーブル行を描画。
- **taskEvents.js**: 追加ボタン → `TaskApi.create` → 一覧再取得・再描画。保存/削除ボタン → `TaskApi.update` / `TaskApi.delete`。

### 2.2 バックエンド

ルートは `web.php`（タスク画面・タスク API 的エンドポイント）、`api.php`（`/api/tasks` 系）、`auth.php`（ログイン・登録・ログアウト等）に分かれます。タスク機能は Repository パターンで TaskController → TaskService → TaskRepository → Model → MySQL の流れです。

```mermaid
flowchart TB
    subgraph routes [ルート]
        Web["web.php"]
        Api["api.php"]
        Auth["auth.php"]
    end

    subgraph controllers [コントローラ]
        TaskCtrl["TaskController"]
        AuthCtrl["Auth 系"]
    end

    subgraph service [サービス層]
        TaskSvc["TaskService"]
    end

    subgraph repo [Repository]
        TaskRepoIf["TaskRepositoryInterface"]
        EloquentRepo["EloquentTaskRepository"]
    end

    subgraph models [モデル]
        TaskModel["Task"]
        UserModel["User"]
    end

    subgraph storage [永続化]
        MySQL[(MySQL)]
    end

    Web --> TaskCtrl
    Web --> Auth
    Api --> TaskCtrl
    Auth --> AuthCtrl
    TaskCtrl --> TaskSvc
    TaskSvc --> TaskRepoIf
    TaskRepoIf --> EloquentRepo
    EloquentRepo --> TaskModel
    EloquentRepo --> UserModel
    TaskModel --> MySQL
    UserModel --> MySQL
```

| レイヤー | 主な役割 |
|----------|----------|
| **web.php** | `GET /tasks`（HTML）, `GET /tasks/list`（JSON）, `POST/PUT/DELETE /tasks`。いずれも `auth` ミドルウェア。 |
| **api.php** | `GET/POST/PUT/DELETE /api/tasks`。同じ TaskController メソッドを `/api` プレフィックスで提供。 |
| **auth.php** | ゲスト: register, login, forgot-password, reset-password。認証済: verify-email, logout, password 更新等。 |
| **TaskController** | index（ビュー返却）, apiIndex（JSON）, store, update, destroy。Request 検証・TaskService 呼び出し。 |
| **TaskService** | getTasksForUser, createTask, updateTask, deleteTask。ユーザー紐づけと Repository 呼び出し。 |
| **EloquentTaskRepository** | getByUserId, createForUser, findForUser, update, delete。Task モデル経由で DB アクセス。 |

---

## 3. データフロー図（タスク一覧取得の例）

ログイン済みユーザーがタスク一覧画面を開いたときの流れです。

```mermaid
sequenceDiagram
    participant Browser as ブラウザ
    participant Nginx as Nginx
    participant Laravel as Laravel
    participant TaskCtrl as TaskController
    participant TaskSvc as TaskService
    participant Repo as EloquentTaskRepository
    participant DB as MySQL

    Browser->>Nginx: GET /tasks
    Nginx->>Laravel: index.php
    Laravel->>TaskCtrl: index
    TaskCtrl->>Browser: HTML (tasks.index)

    Browser->>Nginx: GET /tasks/list (Cookie, Accept: application/json)
    Nginx->>Laravel: index.php
    Laravel->>TaskCtrl: apiIndex
    TaskCtrl->>TaskSvc: getTasksForUser(user)
    TaskSvc->>Repo: getByUserId(userId)
    Repo->>DB: SELECT * FROM tasks WHERE user_id = ?
    DB-->>Repo: rows
    Repo-->>TaskSvc: Collection
    TaskSvc-->>TaskCtrl: Collection
    TaskCtrl->>Browser: JSON

    Browser->>Browser: renderTasks(tasks)
```

1. **GET /tasks**: Blade の `tasks.index` が返り、その中で `main.js` が読み込まれる。
2. **GET /tasks/list**: 同一オリジンで Cookie（セッション）が送られ、認証済みユーザーのタスク一覧が JSON で返る。
3. **renderTasks(tasks)**: 受け取った配列で `#task-list` のテーブルを描画する。

---

## 4. CI パイプライン図

GitHub Actions では、Push/PR 時に `laravel-tests` と `frontend-check` が並列で実行されます。

```mermaid
flowchart LR
    Trigger[Push / Pull Request]
    Trigger --> LaravelJob[laravel-tests]
    Trigger --> FrontJob[frontend-check]

    subgraph LaravelJob [laravel-tests]
        L1[Checkout]
        L2[Setup PHP 8.2]
        L3[Composer install]
        L4[Prepare .env]
        L5[MySQL サービス待機]
        L6[migrate]
        L7[php artisan test]
        L8[PHPStan]
        L1 --> L2 --> L3 --> L4 --> L5 --> L6 --> L7 --> L8
    end

    subgraph FrontJob [frontend-check]
        F1[Checkout]
        F2[Setup Node 20]
        F3[npm ci]
        F4[ESLint]
        F5[Jest]
        F1 --> F2 --> F3 --> F4 --> F5
    end
```

| ジョブ | 内容 |
|--------|------|
| **laravel-tests** | PHP 8.2、Composer、MySQL サービス、`.env`（CACHE_STORE=array, SESSION_DRIVER=array 等）、マイグレーション、Feature/Unit テスト、PHPStan。 |
| **frontend-check** | Node 20、`npm ci`、ESLint（`public/js`）、Jest（`public/js/__tests__/`）。 |

CI 用の詳細は [../.github/workflows/ci.yml](../.github/workflows/ci.yml) を参照してください。
