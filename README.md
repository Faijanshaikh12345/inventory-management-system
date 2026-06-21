📦 Inventory Management System (Laravel)
A complete Inventory Management System built with Laravel that helps users manage products, categories, suppliers, customers, purchases, sales, and stock reports through a clean Admin Dashboard.

🚀 Features

Dashboard
* Total Products
* Total Sales
* Total Purchases
* Stock Overview

Category Management
* Add Category
* Edit Category
* Delete Category

Unit Management
* Add Unit
* Edit Unit
* Delete Unit

Supplier Management
* Add Supplier
* Edit Supplier
* Delete Supplier

Customer Management
* Add Customer
* Edit Customer
* Delete Customer

Product Management
* Add Product
* Edit Product
* Delete Product
* Stock Quantity Tracking

Purchase Management
* Add Purchase
* Update Purchase
* Delete Purchase
* Auto Stock Increase

Sales Management
* Add Sale
* Update Sale
* Delete Sale
* Auto Stock Decrease
* Paid & Due Amount Tracking

Reports
* Sales Report
* Purchase Report
* Stock Report

Authentication
* User Registration
* User Login
* Secure Logout

🛠️ Tech Stack
* Laravel
* PHP
* MySQL
* Blade Template
* AdminLTE
* Bootstrap

⚙️ Project Setup (After Downloading from GitHub)
Follow these steps in order after downloading or cloning the project.

✅ Step 1 — Open Project in VS Code
Open the project folder and open the terminal inside it.

✅ Step 2 — Create .env File
Run:

```
cp .env.example .env
```

✅ Step 3 — Install Vendor Packages

```
composer install --ignore-platform-reqs
```

✅ Step 4 — Generate Application Key

```
php artisan key:generate
```

✅ Step 5 — Configure Database
Open the `.env` file and update:

```
DB_DATABASE=inventory_management
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```

✅ Step 6 — Run Migrations

```
php artisan migrate
```

✅ Step 7 — Start Laravel Server

```
php artisan serve
```

✅ Step 8 — Open in Browser

```
http://127.0.0.1:8000/login
```

🔐 Demo User Login
Register a new account and start using the system.

📌 Modules Included
* Dashboard
* Categories
* Units
* Suppliers
* Customers
* Products
* Purchases
* Sales
* Reports
* Authentication

👨‍💻 Author
Faijan Shaikh

📌 Note
This project was developed for learning and portfolio purposes using Laravel and AdminLTE.
If you find any issues or have suggestions for improvement, feel free to create an issue or contact me.
