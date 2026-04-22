# Library Management System (LMS)

A complete full-stack Library Management System built with PHP, MySQL, and Bootstrap 5.

## Features
- **Admin Dashboard**: Statistical overview of books, students, and issues.
- **Book Management**: Add, Edit, Delete, and Search books.
- **Student Management**: Register and manage students.
- **Issue System**: Issue books to students with stock management.
- **Return System**: Mark books as returned and update inventory.
- **Reports**: View all transactions, overdue books, and filter by status.
- **Responsive UI**: Built with Bootstrap 5 for mobile and desktop use.

## Setup Instructions (XAMPP)

Follow these steps to run the project on your local machine:

1.  **Extract the Project**:
    Place the project folder (e.g., `libaray`) inside the `C:\xampp\htdocs\` directory.

2.  **Start XAMPP**:
    Open the XAMPP Control Panel and start **Apache** and **MySQL**.

3.  **Setup Database**:
    - Open your browser and go to `http://localhost/phpmyadmin/`.
    - Click on **New** and create a database named `library_db`.
    - Select the `library_db` database, go to the **Import** tab.
    - Click **Choose File** and select the SQL file located at:
      `C:\xampp\htdocs\libaray\database\library_db.sql`
    - Click **Go** to import the tables.

4.  **Access the Application**:
    - Open your browser and go to `http://localhost/libaray/`.
    - You will be redirected to the login page.

5.  **Admin Login Credentials**:
    - **Email**: `admin@library.com`
    - **Password**: `admin123`

## Folder Structure
- `/admin`: PHP files for administrative pages.
- `/assets`: CSS, JS, and image files.
- `/database`: Contains the `library_db.sql` file.
- `/includes`: Reusable components (header, footer, sidebar) and database config.

## Security
- Uses PHP PDO with **Prepared Statements** to prevent SQL Injection.
- Uses `password_hash` and `password_verify` for secure admin authentication.
- session-based protection on all admin pages.
