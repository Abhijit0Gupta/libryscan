# LibryScan - システム要件定義書 (System Requirements Definition)

## 1. プロジェクト概要 (Project Overview)
本システム「LibryScan」は、RidgeWorks Co., Ltd. 内部の技術書籍、開発端末（PC、モニター、検証機等）を一元管理し、貸出・返却業務を効率化するためのWebアプリケーションです。

## 2. システム構成・技術スタック (Tech Stack)
- **Backend Framework:** Laravel 11 (PHP 8.3)
- **Database:** MySQL 8.0
- **Container Environment:** Docker / Docker Compose (Nginx, PHP-FPM, MySQL)
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Version Control:** Git / GitHub

## 3. 機能要件 (Functional Requirements)
1. **資産一覧・検索機能 (Asset Catalog & Search)**
   - 登録済み資産の一覧表示（カテゴリ、ステータス、保管場所）
   - タイトル・資産管理番号（Asset Tag / ISBN）によるリアルタイム検索
   - ステータス（貸出可能 / 貸出中）での絞り込みフィルター

2. **新規資産登録機能 (Asset Registration)**
   - モーダルUIによる新しい機器・書籍の登録
   - カテゴリ選択、管理番号重複チェック

3. **貸出・返却管理機能 (Checkout & Return Management)**
   - 貸出ボタン押下によるステータス自動更新（`available` → `borrowed`）
   - 返却ボタン押下によるステータス復帰（`borrowed` → `available`）
   - トランザクション履歴のデータベース記録 (`checkouts` テーブル)

## 4. データベース設計 (Database Schema)
- `categories` (カテゴリ情報: 書籍、ハードウェア等)
- `items` (個別資産情報: 管理番号、タイトル、状態)
- `checkouts` (貸出履歴: 借用者ID、借用日時、返却日時)
- `users` (利用ユーザー情報)