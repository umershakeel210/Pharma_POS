# 💊 Pharma POS System

A complete **Pharmacy Point of Sale (POS) System** built using **Core PHP + MySQL** with a modern UI, role-based access, reporting, and real-world pharmacy workflow.

---

## 🚀 Features

### 🔐 Authentication & Roles

* Login system with sessions
* Role-based access:

  * **Admin**
  * **Cashier**
* Protected routes using auth middleware

---

### 💊 Medicine Management

* Add / Edit / Delete Medicines
* Track:

  * Batch number
  * Expiry date
  * Purchase price
  * Sale price
  * Stock quantity
  * Barcode
  * Prescription requirement

---

### 🧾 POS (Billing System)

* Live search medicine
* Barcode scanning support
* Add items to cart
* Separate cart page for checkout
* Discount support
* Automatic stock deduction
* Change calculation

---

### 🛒 Cart System

* Session-based cart
* Add / Remove items
* Clear cart
* Complete sale from cart

---

### 🧾 Invoice (Thermal Receipt)

* Auto-print invoice
* Small receipt layout
* Shows:

  * Items
  * Quantity
  * Total
  * Discount
  * Paid & change

---

### 📊 Reports

#### 📈 Profit Report

* Calculates profit using:

  ```
  Profit = Final Sale (after discount) - Purchase Cost
  ```
* Discount is properly distributed across items
* Date filter available

#### 📦 Stock Report

* Current stock
* Expiry status:

  * OK
  * Near Expiry
  * Expired
* Total stock value

---

### 📊 Dashboard

* Total medicines
* Low stock
* Expired medicines
* Total sales
* 📉 Daily sales chart (Chart.js)

---

### 👥 User Management

* Admin can:

  * Create users
  * Assign roles
* Cashier has limited access

---

### 💾 Backup System

* One-click database backup
* Generates `.sql` file
* Download backup files
* Uses `mysqldump` (XAMPP compatible)

---

## 🛠️ Tech Stack

* PHP (Core)
* MySQL
* Bootstrap 5
* Chart.js
* JavaScript

---

## 📁 Project Structure

```
pharma-pos/
│
├── config/
│   ├── database.php
│   ├── auth.php
│   ├── admin_auth.php
│
├── views/
│   ├── layouts/
│   ├── medicines/
│   ├── suppliers/
│   ├── purchases/
│   ├── sales/
│   ├── reports/
│   ├── users/
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── backup.php
│
├── backups/
│
└── README.md
```

---

## 🗄️ Database

Database name:

```text
pharma_pos
```

Main tables:

* users
* medicines
* suppliers
* purchases
* purchase_items
* sales
* sale_items

---

## ⚠️ Important Setup

### Enable mysqldump (XAMPP)

If backup fails, update path in:

```php
views/backup.php
```

```php
$mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";
```

---

## ▶️ How to Run

1. Install **XAMPP**
2. Place project in:

```text
C:\xampp\htdocs\pharma-pos
```

3. Start:

   * Apache
   * MySQL

4. Import database

5. Open in browser:

```text
http://localhost/pharma-pos/public/
```

---

## 🔑 Default Login

```
Admin:
Email: admin@gmail.com
Password: admin123
```

---

## 💡 Future Improvements

* Customer module
* Online hosting
* Mobile responsive UI
* Backup restore feature
* Sales analytics dashboard
* Multi-store support

---

## 🎯 Status

✅ Fully working
✅ Sellable product
✅ Real-world pharmacy workflow

---


