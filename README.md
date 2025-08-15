

[![Release](https://img.shields.io/github/v/release/akaunting/akaunting?label=release)](https://github.com/akaunting/akaunting/releases)

Software de contabilidad en línea diseñado para pequeñas empresas y freelancers. Akaunting está construido con tecnologías modernas como Laravel, VueJS, Tailwind, API REST, etc. Gracias a su estructura modular, Akaunting ofrece una increíble tienda de aplicaciones para usuarios y desarrolladores.

## Requisitos

* PHP 8.1–8.3
* Base de datos (ej.: MariaDB, MySQL, PostgreSQL, SQLite)
* Servidor web (ej.: Apache, Nginx, IIS)
* [Otras librerías](https://akaunting.com/hc/docs/on-premise/requirements/)

## Framework

Utiliza [Laravel](http://laravel.com), el mejor framework PHP existente, como marco base y el paquete [Module](https://github.com/akaunting/module) para las aplicaciones.

## Instalación

* Instala [Composer](https://getcomposer.org/download) y [Npm](https://nodejs.org/en/download)
* Clona el repositorio: `git clone https://github.com/akaunting/akaunting.git`
* Instala dependencias: `composer install ; npm install ; npm run dev`
* Instala Akaunting:

```bash
php artisan install --db-name="akaunting" --db-username="root" --db-password="pass" --admin-email="admin@company.com" --admin-password="123456"
```

* Crea datos de ejemplo (opcional): `php artisan sample-data:seed`

## Contribuir

Por favor, sé muy claro en tus mensajes de commit y Pull Requests; los Pull Requests sin mensaje pueden ser rechazados sin motivo.

Al contribuir código a Akaunting, debes seguir los estándares de codificación PSR. La regla de oro es: imita el código existente de Akaunting.

## Traducción

Si deseas contribuir traducciones, visita nuestro proyecto en [Crowdin](https://crowdin.com/project/akaunting).

## Tests

Soporte oficial para PHP 8.1–8.3 y Node 18/20. Para ejecutar las pruebas localmente:

```bash
composer install
npm install
php artisan migrate --seed
php artisan test
```
