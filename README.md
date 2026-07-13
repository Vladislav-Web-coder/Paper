# Paper 📝

A clean, monolithic note-taking application built with PHP and Laravel, utilizing Blade templates for a seamless server-rendered user experience.

## 🚀 Key Features

*   **Note Management:** Create, read, update, and delete rich-text or markdown notes.
*   **Monolithic Architecture:** High performance with standard server-side rendering (SSR) via Laravel Blade.
*   **Intuitive UI:** Clean and responsive user interface built using modern CSS/Tailwind alongside Blade components.

## 🛠️ Tech Stack

*   **Backend:** PHP 8.5+ / Laravel 13+
*   **Frontend:** Laravel Blade, Tailwind CSS, Vite
*   **Database:** SQLite / MySQL / PostgreSQL

## ⚙️ Requirements

*   PHP >= 8.5
*   Composer
*   Node.js & NPM (for frontend asset compilation)

## 💻 Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com
   cd Paper
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend dependencies:**
   ```bash
   npm install && npm run build
   ```

4. **Environment Configuration:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database settings inside the `.env` file.*

5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Start the local development server:**
   ```bash
   php artisan serve
   ```
   Visit the app at `http://127.0.0.1:8000`.
