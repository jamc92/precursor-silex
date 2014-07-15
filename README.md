El Precursor
=========

Periódico El Presursor Colegio Universitario Francisco de Miranda

### Tabla de Contenidos
- [Configurar assets](#assets)
- [Configurar virtual host](#vhost)
- [Autores](#autores)

### <a name='assets'></a> **Configurar assets:**

En el archivo web/index.php en la linea 74 se debe poner la url absoluta de donde se encuentran los recursos css, img, js, y otros.

`
$app['asset_path'] = 'http://localhost/precursor-silex/web/resources';
`

### <a name='vhost'></a> **Configurar virtual host:**

El siquiente comando es para hacer pruebas en tu computador como si fuera un virtual host.

`
php -S localhost:8080 -t web web/server_vhost.php
`

Donde:

- `localhost:8080` es como si fuera el dominio de la página web del precursor.
- Tienes que tener en cuenta que el comando se corre en el terminal de comandos, ya entrado en la carpeta del proyecto.

### <a name='autor'></a> **Autores:** 

- Ramón Serrano <ramon.calle.88@gmail.com>
- Javier Madrid <javiermadrid19@hotmail.com>
- Sander Rodríguez
