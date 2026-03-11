---
name: 研究用アプリ基盤の整理
overview: 「技術更新に強いWebアプリ基盤の検討」の研究対象として、task-manager-app を研究テーマ・レジュメで想定している構成（レイヤー分離・CI/CD・評価基盤）に合わせるための現状整理と今後の方針です。
todos: []
isProject: false
---

# 研究用タスク管理アプリの現状整理と方針

## 研究テーマとの対応

研究テーマ「技術更新に強いWebアプリ基盤の検討」では、次の2点を目的としています。

- **設計**: 技術更新時の影響範囲を局所化する（Controller / Service / Repository のレイヤー分離、依存方向の一方向化）
- **検証**: 自動テストと CI/CD による継続的検証と、**評価指標**（テスト通過率・修正工数・エラー発生率）による定量的評価

task-manager-app は、この検討のための**実験用システム**として位置づけられています。

---

  
現状と研究で想定している構成の差分

### バックエンド（Laravel）


| 項目     | 研究での想定                                 | 現状                                                                              |
| ------ | -------------------------------------- | ------------------------------------------------------------------------------- |
| レイヤー構成 | Controller → Service → Repository → DB | Controller が Eloquent を直接利用。Service / Repository は未実装（`TaskService` の use のみ残存） |
| 責務の分離  | 業務ロジックは Service、DB アクセスは Repository    | タスクの取得・更新・削除がすべて `TaskController` 内に集約                                          |


**主な該当ファイル**: [app/Http/Controllers/TaskController.php](app/Http/Controllers/TaskController.php)（Eloquent を直接使用）

### テスト・静的解析


| 項目      | 研究での想定          | 現状                                           |
| ------- | --------------- | -------------------------------------------- |
| PHPUnit | バックエンドの自動テスト    | あり（Auth 系 Feature テストのみ）。**タスク API のテストはなし** |
| PHPStan | PHP の静的解析       | **未導入**                                      |
| Jest    | フロントエンドのテスト     | **未導入**                                      |
| ESLint  | JS の品質・スタイルチェック | **未導入**                                      |


### CI/CD


| 項目           | 研究での想定              | 現状                                         |
| ------------ | ------------------- | ------------------------------------------ |
| CI           | ビルド・テストの自動実行        | GitHub Actions で `php artisan test` まで実施済み |
| フロントビルド/チェック | npm ビルド・Jest・ESLint | **未実行**                                    |
| CD           | デプロイの自動化            | **未導入**（README の「今後の課題」にも記載）               |


### フロントエンド

- **良い点**: `public/js` が api / ui / events に分離され、Fetch API は `taskApi.js` に集約されている。研究で想定する「役割ごとの分離」に近い。

---

## 研究を進めるうえでの整理の流れ（案）

研究では「提案構成」と「従来構成」を比較するため、次の2パターンが考えられます。

1. **現状を「従来構成」として固定**し、別ブランチで「提案構成」（Service/Repository 導入）を実装して比較する。
2. **いったんアプリを「提案構成」に揃え**、そのうえで技術更新（例: PHP/Laravel のバージョンアップ、JS ライブラリ追加・更新）を実施し、テスト通過率・修正工数・エラー率を計測する。

いずれにしても、**評価の土台**として以下があると有利です。

- **タスク API のテスト**（PHPUnit Feature テスト）  
→ 更新前後の「テスト通過率」を測るため
- **レイヤー分離の導入**（Service / Repository）  
→ 研究の「提案する設計」を実装し、影響範囲の局所化を検証するため
- **PHPStan / Jest / ESLint の導入**（任意だが推奨）  
→ 研究資料に記載の技術スタックと評価の説得力を揃えるため
- **CI に PHPStan とフロントチェックを追加**（任意）  
→ 更新のたびに同一条件で検証するため

---

## 次のステップの候補

このあと、次のような計画を具体化できます。

- **A. 評価用の土台を作る**  
タスク API の PHPUnit テストを追加し、CI で必ず通す。あわせて「従来構成」のスナップショット（テスト結果・ファイル構成）を README やドキュメントに残す。
- **B. 提案構成に揃える**  
Task まわりを Controller → Service → Repository にリファクタし、研究で想定しているレイヤー図と一致させる。
- **C. テスト・静的解析を研究資料どおりにする**  
PHPStan、Jest、ESLint を導入し、CI に組み込む。
- **D. 上記の組み合わせ**  
例: まず A → B で「従来」と「提案」の両方の状態を用意し、そのあと C で評価環境を整える。

「まずは A だけ」「B と C を一緒に進めたい」など、希望の優先順位があれば教えてください。それに合わせて、具体的な作業手順（どのファイルをどう変更するか）まで落とした実行プランを作成します。

---

## 正解の基準：全10テストが緑（PASS）になること

php artisan test --testsuite=Feature --filter=TaskApiTest を実行して、10件すべてが成功

### 各テストで「正解」とみなされる内容
|#|テスト名|正解になる条件|
|-|-------|-----------|
|1|test_guest_cannot_access_task_api|未ログインで GET /api/tasks を叩くと 401 が返る|
|2|test_authenticated_user_can_list_own_tasks|ログインユーザーが GET /api/tasks を叩くと 200、JSON が 1件 で、id / title / status が期待おり|
|3|test_authenticated_user_does_not_see_other_users_tasks|他人のタスクは含まれず、200 で JSON が 0件|
|4|test_authenticated_user_can_create_task|POST /api/tasks で 201、レスポンスに title / description / status / user_id が期待どおりで、DB にそのタスクが 1件保存されている|
|5|test_create_task_requires_title_and_status|title なし or status なしで作成すると 422（バリデーションエラー）|
|6|test_create_task_accepts_only_valid_status|status が invalid のとき 422（todo / doing / done 以外は拒否）|
|7|test_authenticated_user_can_update_own_task|自分のタスクを PUT /api/tasks/{id} で更新すると 200、レスポンスとDBの title / status が更新後の値になっている|
|8|test_authenticated_user_cannot_update_other_users_task|他人のタスクを更新しようとすると 403、DB の title は 変更されていない|
|9|test_authenticated_user_can_delete_own_task|自分のタスクを DELETE /api/tasks/{id} で削除すると 204、そのタスクが DB から消えている|
|10|test_authenticated_user_cannot_delete_other_users_task|他人のタスクを削除しようとすると 403、そのタスクは DB に残っている|

### 実行方法と結果の見方
* ローカル（Docker + MySQL 前提）
* docker compose up -d のあと、
* php artisan test --testsuite=Feature --filter=TaskApiTestで「10 passed」なら正解

* CI（GitHub Actions）
  * push 後に laravel-tests ジョブの「Run tests」で同じテストが走り、ここも全て成功すれば正解

まとめると、「正解」= 上記10テストがすべて PASS することです。1つでも FAIL やエラーになれば、その時点で不正解として修正が必要です。