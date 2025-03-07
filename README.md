# Library Management System API

## Introduction
Hello👋, my name is Novi Rachmahwati. This project is developed as part of the Backend Engineer Technical Test for Altech Omega Andalan company.

## Project Description
This project implements a simple RESTful API for managing a library system, focusing on books and authors. This API provides endpoints to perform CRUD operations on books and authors, allowing efficient management of a library's catalog.

## Features
- Manage books (Create, Read, Update, Delete)
- Manage authors (Create, Read, Update, Delete)
- Associate books with authors

## Technologies Used
- PHP (Version 8.3.9)
- Laravel Framework (Version 11.1.3)
- MySQL

## Installation
1. Clone the repository:
    ```sh
    git clone https://github.com/novirachmahwati/library-management-api.git
    ```
2. Navigate to the project directory:
    ```sh
    cd library-management-api
    ```
3. Install dependencies:
    ```sh
    composer install
    ```
4. Set up the environment variables:
    ```sh
    cp .env.example .env
    ```
5. Update the `.env` file with your database configuration:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

   Make sure to replace `your_database_name`, `your_database_username`, and `your_database_password` with your actual database credentials.

6. Run the migrations:
    ```sh
    php artisan migrate
    ```

7. Run generate swagger:
    ```sh
    php artisan l5-swagger:generate
    ```

## Usage
1. Start the Laravel development server:
    ```sh
    php artisan serve
    ```
2. Access the API on:
    ```
    http://localhost:8000/api/1.0/{endpoint_name}
    ```

## API Documentation
Explore the API endpoints using Swagger UI:
- Swagger Documentation URL: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

You can view the documentation for this API using Swagger. It provides an interactive interface where you can explore endpoints, parameters, request/response schemas, and even test API calls directly from the documentation page.

## API Endpoints
### Authors
- `GET /authors` - Retrieve a list of all authors
- `GET /authors/{id}` - Retrieve details of a specific author
- `POST /authors` - Create a new author
- `PUT /authors/{id}` - Update an author
- `DELETE /authors/{id}` - Delete an author

### Books
- `GET /books` - Retrieve a list of all books
- `GET /books/{id}` - Retrieve details of a specific book
- `POST /books` - Create a new book
- `PUT /books/{id}` - Update a book
- `DELETE /books/{id}` - Delete a book

### Association
- `GET /authors/{id}/books` - Retrieve books by a specific author

## Testing
To run the tests, use the following command:
```sh
php artisan test
