# 構成の記録（従来構成・提案構成）

研究「技術更新に強いWebアプリ基盤の検討」における構成の説明です。

---

## 従来構成（評価用スナップショット）

**提案構成を導入する前**の状態の記録です。比較評価のベースラインとして参照します。

### 特徴

- **レイヤー**: Controller が Eloquent（Model）を直接利用。Service 層・Repository 層は未導入。
- **責務**: タスクの取得・作成・更新・削除の処理がすべて `TaskController` に集約。
- **依存方向**: Controller → Model（Eloquent）→ DB。

### 主な該当ファイル（過去の状態）

| 層 | ファイル | 役割 |
|----|----------|------|
| Controller | `TaskController.php` | リクエスト受付・Eloquent 直接呼び出し・JSON 返却 |
| Model | `app/Models/Task.php` | Eloquent モデル |

---

## 提案構成（現状）

**Controller → Service → Repository** のレイヤー分離を導入した構成です。

### 特徴

- **レイヤー**: 依存方向を一方向に統一。Controller → Service → Repository → DB。
- **責務**: 入口は Controller、業務ロジックは Service、永続化は Repository に分離。
- **技術変更時の影響**: DB 変更は Repository、業務ロジック変更は Service に主に限定。

### 主なファイル

| 層 | ファイル | 役割 |
|----|----------|------|
| 入口 | `routes/api.php` | API ルート定義 |
| Controller | `app/Http/Controllers/TaskController.php` | リクエスト受付・TaskService 呼び出し・JSON 返却 |
| Service | `app/Services/TaskService.php` | タスクの業務ロジック・Repository の利用 |
| Repository | `app/Repositories/TaskRepositoryInterface.php`, `EloquentTaskRepository.php` | DB アクセス（Eloquent）の集約 |
| Model | `app/Models/Task.php` | Eloquent モデル（Repository 内でのみ利用） |

---

## 評価指標の計測

- **テスト通過率**: `php artisan test` で Feature/Unit を実行。タスク API は `tests/Feature/TaskApiTest.php` で検証。
- **修正工数・エラー発生率**: 技術更新の前後で計測する際の指標として利用する。
