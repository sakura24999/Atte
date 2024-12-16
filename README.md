# Atte
企業様の勤怠管理アプリ

## 企業様から人事管理をしたいと依頼があったため
（模擬案件を通して実践に近い開発経験をつむ）

## AtteアプリケーションURL

## 機能一覧
・会員登録機能
・登録時のメール認証機能（http://localhost:8025にてメール確認ができます）
・ログイン機能
・ログアウト機能
・勤怠記録（勤務開始/勤務終了/休憩開始/休憩終了）
・日付別勤怠情報取得
・ページネーション（5件ずつ取得）
・ユーザーページ閲覧（/users_list, /users_attendance_list）
・開発環境と本番環境の切り替え（開発環境：php artisan env:switch development, 本番環境：php artisan env:switch production）

## テーブル設計
・usersテーブル（.images/usersテーブル.png）
・attendancesテーブル（.images/attendancesテーブル.png）

## 環境構築
必要な環境
・PHP
・Docker Desktop
・Git

Atte/
├── docker/
│   ├── mysql/
│   │   ├── data/
│   │   └── my.cnf
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       ├── Dockerfile
│       └── php.ini
├── src/
│   └── [Laravelプロジェクトファイル]
├── .env（.env/.env.development/.env.productionの3ファイル）
└── docker-compose.yml

