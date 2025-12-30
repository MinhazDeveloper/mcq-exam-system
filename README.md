# MCQ Exam & Evaluation System

## Tech Stack
- Laravel (API)
- Vue 3 (SPA)
- Sanctum Authentication
- Google Login (Socialite)

## Features
### Admin
- Create, edit, delete MCQ
- Set correct answer & marks

### Student
- View MCQs
- Submit answers
- Auto score calculation

## Project Setup
### Backend
cd mcq-backend
composer install
php artisan migrate
php artisan serve

### Frontend
cd mcq-frontend
npm install
npm run dev
