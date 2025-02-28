## About This Project

This project is a web application built with modern technologies. It aims to provide a robust and scalable solution for Weather api usage.

## Getting Started

To get started with this project, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/yourproject.git
    cd yourproject
    ```

2. Install dependencies:
    ```bash
    composer install
    npm install
    ```

3. Copy the example environment file and configure the environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Run the database migrations:
    ```bash
    php artisan migrate --seed
    ```

5. Start the local development server:
    ```bash
    php artisan serve
    npm run dev
    ```

## Aditional

se debe crear un corn para ejecutar la tarea de optener la informacion en segundo plano 
    * * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1

Se debe eejecutar el comando php artisan queue:work en segundo plano


## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
