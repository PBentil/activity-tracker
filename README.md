# Activity Tracker 🚀

A Laravel-based web application for tracking activities, managing users, and handling organizational workflows.

---

## 📌 Features

* User authentication (login system)
* Activity tracking system
* Admin-controlled data management
* Database-driven sessions, cache, and queues
* Modern frontend powered by Vite

---

## 🛠️ Tech Stack

* **Backend:** Laravel 13
* **Frontend:** Blade + Vite (CSS & JS)
* **Database:** PostgreSQL
* **Server:** Ubuntu + Nginx
* **Runtime:** PHP 8+

---

## ⚙️ Installation Guide

### 1. Clone the repository

```bash
git clone https://github.com/PBentil/activity-tracker.git
cd activity-tracker
```

---

### 2. Install dependencies

```bash
composer install
npm install
```

---

### 3. Setup environment file

```bash
cp .env.example .env
```

Then update `.env` with your database credentials.

---

### 4. Generate app key

```bash
php artisan key:generate
```

---

### 5. Run migrations

```bash
php artisan migrate
```

---

### 6. Build frontend assets

```bash
npm run build
```

---

### 7. Start the server (local)

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```


