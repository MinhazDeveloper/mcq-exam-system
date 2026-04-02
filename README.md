# Advanced MCQ & Quiz Management System 🚀

![Banner](https://via.placeholder.com/800x400?text=Intellecta+-+Advanced+MCQ+Management+System)

High-performance, modern, and scalable Online Examination System. Built with the cutting-edge **Vue 3 (Vite 7)**, **Tailwind CSS 4**, and **Laravel 12**, it provides a seamless and real-time exam experience for educational institutions and corporate training centers.

---

## ✨ Key Features

### 🔐 Multi-Role System
- **Admin Dashboard:** Manage users, monitor system-wide exams, and view overall analytics.
- **Instructor Dashboard:** Create exams, manage question banks, and track student performance.
- **Student Dashboard:** Enroll in exams, track history, and review detailed results.

### ⏱️ Advanced Exam Engine
- **Real-time Countdown:** Live timer with automatic submission on time expiry.
- **State Persistence:** Progress is saved in LocalStorage, preventing data loss on page refresh.
- **Question Navigator:** Easy navigation between questions with status indicators (Answered, Current, Not Answered).

### 📊 Results & Review
- **Detailed Analytics:** Visual breakdown of correct, wrong, and skipped answers.
- **Answer Review:** Question-by-question review with correct answer highlights.

---

## 🛠️ Technical Stack

- **Frontend:** [Vue.js 3.5+](https://vuejs.org/), [Vite 7](https://vitejs.dev/), [Pinia](https://pinia.vuejs.org/)
- **Styling:** [Tailwind CSS 4.0+](https://tailwindcss.com/)
- **Backend:** [Laravel 12](https://laravel.com/) (REST API)
- **Database:** MySQL / MariaDB
- **Auth:** Laravel Sanctum

---

## 🚀 Installation & Setup

### Prerequisites
- PHP >= 8.2
- Node.js >= 18.x
- Composer & NPM

### 1. Backend Setup
```bash
cd backend
composer install
cp .env.example .env
# Configure your database in .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
