##This repository contains the backend for the Developer Test project.


## Prerequisites

- **PHP:** 8.1 or higher  
- **Composer**  
- **Database:** MySQL (or another supported database)  
- **Node.js:**

## Installation and Setup

1. **Clone the Repository:**
   ```bash
   git clone <https://github.com/GilSEO/BB-Developer-Test/>
   cd <developer-test>
2. Install Composer Dependencies:
   ```bash
   composer install
3. Copy the Environment File:
   ```bash
   cp .env.example .env
4. Configure Environment Variables:
    Open the .env file and update settings such as:
      Database credentials
      Sanctum and Session settings:
   ```env
    SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:5173,127.0.0.1:5173
    SESSION_DRIVER=cookie
    SESSION_DOMAIN=127.0.0.1
    SESSION_SECURE_COOKIE=false
6. Generate the Application Key:
    ```bash
    php artisan key:generate

7. Run Migrations (and Seed if necessary):
   ```bash
   php artisan migrate

Running the Application
Start the Laravel development server:
```bash
  composer run dev  
```
Running Tests
Run your PHPUnit tests (both Feature and Unit tests) with:
```bash
php artisan test
```
