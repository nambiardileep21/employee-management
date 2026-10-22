# Employee Management System

A modern, production-grade Employee Management CRUD application built with Laravel 13.x and PHP 8.5. Designed with clean architecture, enterprise patterns, robust validation, queued email notifications, and dynamic cascading location selectors.

---

## Tech Stack & Architecture

* **Backend Framework:** Laravel 13.x
* **Runtime:** PHP 8.5+
* **Database:** MySQL 8.x
* **Frontend / Styling:** Blade, Tailwind CSS, SweetAlert2
* **Asset Bundler:** Vite
* **Queue & Mailer:** Database Queue Driver, SMTP / Mailtrap

---

## Key Features

* **Full Employee CRUD:** Create, read, update, and soft-delete employee profiles.
* **Enterprise Soft Deletes:** Safe data retention for both employees and users with conditional email uniqueness validation.
* **Rich Form Controls:**
* Text, email, and phone inputs with strict regex format validation.
* Radio buttons for gender selection.
* Multi-select checkboxes for technical skills backed by PHP Model constants / Enums.
* Native HTML5 date pickers for Date of Birth and Date of Joining.


* **Dynamic Cascading Dropdowns:** Asynchronous Country -> State -> City dependent dropdowns loaded via lightweight AJAX/JSON endpoints.
* **Secure File Uploads:** Employee avatar upload handling file-type restrictions (`jpeg`, `png`, `webp`) and size constraints (2MB max), accompanied by fallback UI avatars.
* **Search, Filter & Pagination:** Server-side search across names, emails, and phone numbers, with status and country filters that persist through paginated links.
* **Asynchronous Notifications:** Queued mailables (`ShouldQueue`) dispatched to background workers upon employee creation and updates.
* **Authentication & Security:** Laravel Breeze authentication scaffolding, CSRF protection, input sanitization, and Form Request authorization.

---

## Installation & Setup Guide

### 1. Prerequisites

Ensure you have the following installed on your machine:

* PHP >= 8.5
* Composer
* Node.js & NPM
* MySQL

### 2. Clone the Repository

```bash
git clone https://github.com/nambiardileep21/employee-management.git
cd employee-management

```

### 3. Install Backend Dependencies

```bash
composer install

```

### 4. Environment Configuration

Copy the `.env.example` file to create your `.env`:

```bash
cp .env.example .env

```

Generate the application encryption key:

```bash
php artisan key:generate

```

Open `.env` and verify your local database credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_management
DB_USERNAME=root
DB_PASSWORD=

```

> **Note on APP_URL:**
> - If running via `php artisan serve`, set `APP_URL=http://localhost:8000`
> - If serving via Apache/WAMP/XAMPP subfolder, set `APP_URL=http://localhost/employee-management/public`


### 5. Storage Symlink Setup

Create the symbolic link to serve uploaded avatar photos:

```bash
php artisan storage:link

```

### 6. Run Migrations & Database Seeders

Run migrations along with seeders to populate initial geographical data (Countries, States, Cities) and create the default admin account:

```bash
php artisan migrate --seed

```

---

## Default Admin Credentials

Use these credentials to sign in to the administrative panel:

* **Login URL:** `http://localhost/employee-management/public/login` *(or `[http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)`)*
* **Email:** `admin@example.com`
* **Password:** `password123`

---

## Frontend Assets Compilation

Install NPM dependencies and build the assets via Vite:

```bash
npm install
npm run build

```

For local development with hot-module reloading:

```bash
npm run dev

```

---

## Email & Queue Worker Configuration

Employee creation and update emails are offloaded to Laravel's database queue to ensure zero request-blocking overhead.

### 1. Mailtrap / SMTP Settings

Add your sandbox credentials into `.env`:

```dotenv
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@employeemanagement.test"
MAIL_FROM_NAME="${APP_NAME}"

```

### 2. Start the Queue Worker

In a dedicated terminal, run the queue processor:

```bash
php artisan queue:work

```

All dispatched `EmployeeSavedNotification` jobs will process here and deliver directly to your Mailtrap inbox.

---

## Project Structure Highlights

* `app/Http/Requests/`: Strict validation separation via `StoreEmployeeRequest` and `UpdateEmployeeRequest`.
* `app/Models/Employee.php`: Eloquent relationships, soft deletes, skill constants, and dynamic avatar URL accessors.
* `app/Notifications/EmployeeSavedNotification.php`: Queued transactional notification.
* `public/js/dependent-dropdowns.js`: Vanilla JS implementation fetching states and cities without external UI library dependencies.
* `resources/views/employees/`: Modular Blade views utilizing Tailwind UI components.