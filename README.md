<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sistema de Reserva de Espacios

Este proyecto es un sistema de reserva de espacios desarrollado con Laravel. Permite a los usuarios reservar salas, auditorios y laboratorios de manera eficiente.

## Características

- Gestión de usuarios (registro, inicio de sesión, roles)
- Gestión de espacios (creación, edición, eliminación)
- Reserva de espacios
- API RESTful para integración con aplicaciones frontend
- Documentación de API con Swagger

## Requisitos

- PHP >= 8.1
- Composer
- MySQL o PostgreSQL
- Node.js y NPM (para compilar assets)

## Instalación

1. Clonar el repositorio:
   ```
   git clone https://github.com/tu-usuario/reserva-espacios-backend.git
   ```

2. Instalar dependencias de PHP:
   ```
   composer install
   ```

3. Copiar el archivo de configuración:
   ```
   cp .env.example .env
   ```

4. Generar clave de aplicación:
   ```
   php artisan key:generate
   ```

5. Configurar la base de datos en el archivo `.env`

6. Ejecutar migraciones y seeders:
   ```
   php artisan migrate --seed
   ```

7. Iniciar el servidor de desarrollo:
   ```
   php artisan serve
   ```

## Uso de la API

La documentación de la API está disponible a través de Swagger. Para acceder a ella:

1. Generar la documentación:
   ```
   php artisan l5-swagger:generate
   ```

2. Acceder a la URL: `http://tu-dominio.com/api/documentation`

## Pruebas

Para ejecutar las pruebas:

```
php artisan test
```

## Contribuir

Las contribuciones son bienvenidas. Por favor, lee el archivo CONTRIBUTING.md para más detalles sobre nuestro código de conducta y el proceso para enviarnos pull requests.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.
