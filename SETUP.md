# FanikClean MVC PostgreSQL Setup Guide

Follow these strictly to spin up your new backend integration:

### 1. Database Setup
1. Ensure **PostgreSQL** is running on your machine (default port `5432`).
2. Open pgAdmin or `psql` shell:
   ```sql
   CREATE DATABASE fanikclean;
   ```
3. Run the enclosed `database.sql` script into the raw `fanikclean` database. This will create all relations, constraints, default user roles, and worker categories.

### 2. Configure PHP Framework
1. Go to `config/Database.php`.
2. Update the credentials if your postgres username/password is different from the default (`postgres`/`postgres`).
3. Ensure the **PDO_PGSQL** extension is enabled in your `php.ini` file:
   - Find `extension=pdo_pgsql` and uncomment it (remove the `;`).
   - Find `extension=pgsql` and uncomment it.
   - Restart Apache/Nginx.

### 3. Start the Server
Since this uses standard `.htaccess` URL rewriting for clean MVC routing, use Apache with `mod_rewrite` enabled. 
OR use PHP's built-in webserver from the root directory:
```bash
cd fanikclean_development
php -S localhost:8000
```
Then navigate to `http://localhost:8000/login`.

### 4. Create an Admin User (Using raw SQL for first user)
Before logging in, create your first Admin account securely hashed:
```php
<?php
// Run this script once or run plain SQL if you possess the hash
echo password_hash('password123', PASSWORD_BCRYPT);
// Assuming result is $2y$10$.....
```
```sql
INSERT INTO users (full_name, email, password_hash, role_id) 
VALUES ('Super Admin', 'admin@fanikclean.com', '$2y$10$....', 1);
```
Or simply use the `/signup` route you now have!

### 5. Architectural Map
*   **Routing**: Defined in `routes/web.php`
*   **Controllers**: Manage HTTP Input/Output (`controllers/`)
*   **Models**: Manage raw PDO execution & Queries (`models/`)
*   **Views**: The HTML you wrote, now containing valid `<form>` logic and `name=""` attributes passing data across `POST` payloads (`views/`).
