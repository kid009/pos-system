# POS System Project

A modern, simple, and efficient Point of Sale (POS) system built with **Laravel 11**, designed to help business owners manage their shops, products, and sales transactions seamlessly.

---

## 🚀 Features

-   **Dashboard:** Overview of sales, transactions, and business status.
-   **Shop Management:** Manage shop profile and settings.
-   **Inventory Management:**
    -   **Categories:** Organize products into clear categories.
    -   **Products:** Add, edit, and manage products with descriptions and pricing.
-   **Customer Management:** Maintain a database of customers for better service.
-   **POS Interface:** A fast and intuitive interface for processing sales and printing invoices.
-   **Transaction History:** Track all sales with detailed invoice views.
-   **Audit Logs:** Built-in auditing for sensitive changes (Auditable trait).

---

## 🛠️ Tech Stack

-   **Backend:** PHP 8.2+, Laravel 11.x
-   **Frontend:** Laravel Blade, Vanilla CSS, JavaScript (via Vite)
-   **Database:** MySQL / PostgreSQL
-   **Auth:** Built-in Laravel Authentication with Roles and Status

---

## 💻 Installation

Follow these steps to set up the project locally:

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/pos-system.git
    cd pos-system
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configure your database settings in the `.env` file.*

4.  **Database Migration & Seeding:**
    ```bash
    php artisan migrate --seed
    ```

5.  **Build Assets:**
    ```bash
    npm run dev
    ```

6.  **Serve the application:**
    ```bash
    php artisan serve
    ```

---

## 📂 Project Structure Highlights

-   **Controllers:** Admin-specific logic is in `app/Http/Controllers/Admin/`.
-   **Models:** Centralized business logic and relationships in `app/Models/`.
-   **Services:** Complex business processes (like Auth) are decoupled into `app/Services/`.
-   **Views:** Organized using Blade templates in `resources/views/`.

---

## 🛡️ License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
