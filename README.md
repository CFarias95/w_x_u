## Acerca de Este Proyecto

Este proyecto es una aplicación web construida con tecnologías modernas. Su objetivo es proporcionar una solución robusta y escalable para el uso de la API del clima.

## Comenzando

Para comenzar con este proyecto, sigue estos pasos:

1. Clona el repositorio:
    ```bash
    git clone https://github.com/tuusuario/tuproyecto.git
    cd tuproyecto
    ```

2. Instala las dependencias:
    ```bash
    composer install
    npm install
    ```

3. Copia el archivo de entorno de ejemplo y configura las variables de entorno:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Ejecuta las migraciones de la base de datos:
    ```bash
    php artisan migrate --seed
    ```

5. Inicia el servidor de desarrollo local:
    ```bash
    php artisan serve
    npm run dev
    ```

## Adicional

Se debe crear un cron para ejecutar la tarea de obtener la información en segundo plano:
    * * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1

Se debe ejecutar el comando php artisan queue:work en segundo plano.

## Licencia

Este proyecto es software de código abierto licenciado bajo la [licencia MIT](https://opensource.org/licenses/MIT).
