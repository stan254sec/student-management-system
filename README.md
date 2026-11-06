# Student Management System

A PHP/MySQL web app for managing student records, with login, role-based access, search, activity logs and a REST-style API.

## Setup (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\student_system`
2. Start Apache and MySQL in XAMPP
3. In phpMyAdmin, create a database named `college_db` and import `sql/college_db.sql`
4. Open `http://localhost/student_system/register.php` and create your first account
5. Visit `http://localhost/student_system/login.php`

## Notes
- Database credentials are in `db.php` (XAMPP default: `root` / no password)
- The database is empty on import, so register a user first
