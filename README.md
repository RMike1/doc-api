[![Tests](https://github.com/RMike1/doc-api/actions/workflows/test.yml/badge.svg)](https://github.com/RMike1/doc-api/actions/workflows/test.yml)

### Steps to Clone and Set Up this Project
Follow these steps to clone the repo and set up the project:

1. Clone the Repository
Run the following command to clone the project from GitHub:
```shell
git clone https://github.com/RMike1/doc-api.git
```

2. Navigate to the Project Directory
```shell
cd doc-api
```

3. Install PHP dependencies:
```shell
composer install
```

4. Create .env file
Copy the .env.example file to create a .env file and .env.testing (For testing):
```shell
cp .env.example .env
cp .env.example .env.testing
```

5. Generate Application Key
```shell
php artisan key:generate
```

6. Set up the database
Run migrations:
```shell
php artisan migrate
```

7. Start the server
```shell
php artisan serve
```

### Technologies Used

- Laravel: Backend framework
- Laravel Excel Package: Used for generating Excel files.

### Usage
- Start the queue worker
```shell
 php artisan queue:work
```

### Endpoint
- Export Students Data as excel

```shell
 GET  /api/students/export?file_type=excel
```
- Export Students Data as Pdf

```shell
 GET  /api/students/export?file_type=pdf
```
- Import Students Data from Excel File

```shell
 POST  /api/students/import
```

### Testing
Run test
```shell
 php artisan test
```