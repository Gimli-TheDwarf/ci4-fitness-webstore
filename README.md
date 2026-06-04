# FitnessCrap Webstore Setup Guide

This guide explains how to set up the whole webstore from zero.

The project is a PHP CodeIgniter 4 webstore with a Svelte/Vite frontend. It uses MySQL for the database and Google OAuth for Google login.

## What You Need Installed

Install these before starting:

- XAMPP, or another Apache + MySQL setup
- PHP 8.1 or newer
- Composer
- Node.js 20 LTS or newer
- npm
- Git, optional but recommended

If you use XAMPP, PHP and MySQL usually come with it. Composer and Node.js still need to be installed separately.

## Project Folder

For XAMPP, put the project here:

```text
C:\xampp\htdocs\FitnessCrap
```

Open PowerShell in that folder:

```powershell
cd C:\xampp\htdocs\FitnessCrap
```

All commands below assume you are inside this folder unless the guide says otherwise.

## Step 1: Install PHP Dependencies

Run:

```powershell
composer install
```

This installs the backend packages into `vendor/`.

Important backend packages:

- `codeigniter4/framework`: the PHP framework
- `google/apiclient`: Google login support

## Step 2: Install JavaScript Dependencies

Install the root frontend icon package:

```powershell
npm install
```

Then install the Svelte frontend packages:

```powershell
cd frontend
npm install
cd ..
```

Important frontend packages:

- `svelte`: reactive frontend components
- `vite`: frontend builder/dev server
- `jquery`: AJAX and DOM helper library
- `select2`: nicer select inputs
- `select2-bootstrap-5-theme`: Select2 Bootstrap styling
- `bootstrap-icons`: icon font package

## Step 3: Create `.env`

The `.env` file stores local settings and secrets.

Create this file in the project root:

```text
C:\xampp\htdocs\FitnessCrap\.env
```

Add this starter content:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:1010/'

database.default.hostname = localhost
database.default.database = fitnesscrap
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306

GOOGLE_CLIENT_ID = ''
GOOGLE_CLIENT_SECRET = ''
```

Do not commit `.env` to Git. It contains secrets.

## Step 4: Generate The CodeIgniter Key

Run:

```powershell
php spark key:generate
```

This adds an `encryption.key` value to `.env`.

This key is required by CodeIgniter for secure encrypted data.

## Step 5: Create The Database

Open phpMyAdmin or MySQL and create a database named:

```text
fitnesscrap
```

SQL version:

```sql
CREATE DATABASE fitnesscrap CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Make sure the database name matches `.env`:

```ini
database.default.database = fitnesscrap
```

## Step 6: Run Database Migrations

Run:

```powershell
php spark migrate
```

This creates the database tables.

Check that migrations ran:

```powershell
php spark migrate:status
```

Expected tables:

- `products`
- `tags`
- `users`
- `product_images`
- `product_tags`
- `users_products`
- `user_wishlist`

If migrations fail, check that MySQL is running and that the `.env` database username, password, and database name are correct.

## Step 7: Set Up Google Login

Google login needs two values:

- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`

Create them like this:

1. Go to Google Cloud Console.
2. Create a project or select an existing project.
3. Go to `APIs & Services`.
4. Open `OAuth consent screen`.
5. Fill in the app name, support email, and developer email.
6. Go to `Credentials`.
7. Click `Create Credentials`.
8. Choose `OAuth client ID`.
9. Choose `Web application`.
10. Add this redirect URI:

```text
http://localhost:1010/auth/google/callback
```

11. Copy the Client ID and Client Secret into `.env`:

```ini
GOOGLE_CLIENT_ID = 'paste-client-id-here'
GOOGLE_CLIENT_SECRET = 'paste-client-secret-here'
```

The redirect URI must exactly match your site URL.

If you change the port from `1010`, change it in both places:

- `.env` `app.baseURL`
- Google Cloud redirect URI

## Step 8: Build The Svelte Frontend

Run:

```powershell
cd frontend
npm run build
cd ..
```

This creates the built frontend files:

```text
frontend/public/assets/index.css
frontend/public/assets/index.js
```

The PHP views load those built files when the app is not in development mode.

## Step 9: Serve The Website

Best practice is to serve CodeIgniter from the `public/` folder.

Do not point Apache directly at the project root.

Example Apache virtual host:

```apache
<VirtualHost *:1010>
    DocumentRoot "C:/xampp/htdocs/FitnessCrap/public"
    ServerName localhost

    <Directory "C:/xampp/htdocs/FitnessCrap/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Also make sure Apache has `mod_rewrite` enabled.

Restart Apache.

Then open:

```text
http://localhost:1010/
```

Quick local alternative:

```powershell
php spark serve --port 1010
```

## Step 10: Frontend Development Mode

Only use this while editing Svelte files.

Terminal 1:

```powershell
php spark serve --port 1010
```

Terminal 2:

```powershell
cd frontend
npm run dev
```

In development mode, CodeIgniter loads Svelte from:

```text
http://localhost:3000
```

If port `3000` is busy, stop the other process or change `frontend/vite.config.js`.

## Step 11: Create An Admin Account

First, register normally through the website.

Then open MySQL/phpMyAdmin and run:

```sql
UPDATE users
SET role = 'administrator'
WHERE email = 'your-email@example.com';
```

Log out and log back in.

Admin users go to:

```text
/adminPanel
```

## Step 12: Product Images

Uploaded product images are stored in:

```text
public/images/productsImages/
```

The folder must exist.

The web server must be allowed to write to it.

The fallback product image is:

```text
public/images/defaultImage.png
```

## Common Commands

Install PHP packages:

```powershell
composer install
```

Install frontend packages:

```powershell
cd frontend
npm install
```

Build frontend:

```powershell
cd frontend
npm run build
```

Run migrations:

```powershell
php spark migrate
```

Check migrations:

```powershell
php spark migrate:status
```

List routes:

```powershell
php spark routes
```

Check Composer security issues:

```powershell
composer audit
```

## Loaded Browser Libraries

The app loads these from CDNs:

- Bootstrap 5 CSS and JS
- jQuery
- Popper
- Select2
- Font Awesome
- Google Fonts

The app loads Bootstrap Icons locally from:

```text
public/bootstrapAssets/
```

## Troubleshooting

Problem: `Unknown column 'email_verified'` or `Unknown column 'is_active'`

Your database is missing columns expected by the code. Run migrations first:

```powershell
php spark migrate
```

If migrations are already marked as run, add the columns manually:

```sql
ALTER TABLE users ADD COLUMN email_verified TINYINT(1) NULL DEFAULT 0;
ALTER TABLE users ADD COLUMN is_active TINYINT(1) NULL DEFAULT 1;
```

Problem: Google login fails

Check:

- `.env` has `GOOGLE_CLIENT_ID`.
- `.env` has `GOOGLE_CLIENT_SECRET`.
- Google Cloud has `http://localhost:1010/auth/google/callback`.
- `.env` `app.baseURL` is `http://localhost:1010/`.

Problem: CSS or Svelte behavior looks old

Rebuild the frontend:

```powershell
cd frontend
npm run build
```

Problem: Normal routes show 404

Check:

- Apache is serving `public/`.
- Apache has `mod_rewrite` enabled.
- Apache directory config has `AllowOverride All`.

Problem: Uploads fail

Check:

- `public/images/productsImages/` exists.
- Apache/PHP can write to that folder.
- Uploaded images match the app's validation rules.

## Security Rules

- Never commit `.env`.
- Never commit Google secrets.
- Never commit database passwords.
- Serve the app from `public/`.
- Keep `vendor/`, `node_modules/`, and `writable/` out of Git.
- Use `CI_ENVIRONMENT = production` on a real server.
- Use HTTPS on a real server.
- Use separate Google OAuth credentials for production.

## Final Setup Checklist

- Composer packages installed.
- npm packages installed in root.
- npm packages installed in `frontend/`.
- `.env` created.
- CodeIgniter key generated.
- Database created.
- Migrations run.
- Google OAuth keys added.
- Frontend built.
- Apache points to `public/`.
- Admin user promoted.

If every item is done, the webstore should be ready to run.
