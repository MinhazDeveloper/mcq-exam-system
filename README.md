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
cd backend
composer install
php artisan migrate
php artisan serve

### Frontend
cd frontend
npm install
npm run dev

## Assumptions
- No negative marking
- One correct answer per question

## Extra Improvements
- Role-based access
- Clean API structure
