# EduControl — School Management System

A PHP and MySQL school administration application for organizing academic and fee records.

## Features

- Departments, classes, subjects and enrollment
- Assignments and schedules
- Fee vouchers and payment records
- Printable receipts and user management

## Technology

PHP, MySQL, HTML, CSS and JavaScript.

## Local setup

Clone the repository:

```bash
git clone https://github.com/nasirabbas827/School-final.git
cd School-final
```

1. Install a local PHP web server and MySQL, such as XAMPP.
2. Place the project in the server's document root, or configure a virtual host pointing to this folder.
3. Import `Database/school_db.sql` using phpMyAdmin or your MySQL client.
4. Update the database connection in `config.php` and in role-specific copies of `config.php` to match your local database name and credentials.
5. Start Apache and MySQL, then open `http://localhost/School-final/`.

## Repository layout

| Path | Purpose |
|---|---|
| `Database/` | SQL database export |
| `admin/` | Role-specific pages |
| `config.php` | Database connection configuration |
| `index.php` | Web entry page |

## Development notes

Setup steps describe local development. Configure your own database, mail and API credentials where applicable. Review debug settings and replace development secrets before deploying. The application was not executed as part of this documentation update.

Maintained by [Nasir Abbas](https://github.com/nasirabbas827).
