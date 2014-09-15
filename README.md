El Precursor
=========

Periódico El Presursor Colegio Universitario Francisco de Miranda

URL de página de pruebas en internet: [http://precursor.esy.es](http://precursor.esy.es) ó [http://www.precursor.esy.es](http://www.precursor.esy.es)

### Tabla de Contenidos
- [Configurar assets](#assets)
- [Configurar virtual host](#vhost)
- [Modelo - Vista - Controlador](#mvc)
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

### <a name='mvc'></a> **Modelo - Vista - Controlador**

En el modelo vista controlador del código se establece de la siguiente forma:

- La ruta: es el URI que ejecutará la acción del controlador. De la siguiente manera:

```
$app->match('/uri', 'Clase::accion')
    ->bind('nombre_uri');
```

- El controlador o acción: en este caso es un callable o un string del llamado a la funcion de una clase controlador para ejecutar la acción que procesará la petición de la URI. Los controladores están en la carpeta *src/Precursor/Application/Controller*. En la ruta se invocan de la siguiente manera:

```
$app->match('/accionUri', 'Precursor\\Application\\Controller\\Clase::funcion')
    ->bind('nombre_uri');
```

- El modelo: el modelo es usado por el controlador las veces que sea necesario obtener datos de la base de datos. De la siguiente manera:

```
...
use Precursor\Application\Model\Categoria,
    Symfony\Component\HttpFoundation\Request,
    Silex\Application,
    ...
class Clase
{
  ...
  function funcion(Request $request, Application $app) {
    $categoriaModelo = new Categoria($app['db']);
    $categorias = $categoriaModelo->getTodo(array(), array(), "WHERE id > 1");
    ...
  }
  ...
}
```

### <a name='autor'></a> **Autores:** 

- Ramón Serrano <ramon.calle.88@gmail.com>
- Javier Madrid <javiermadrid19@hotmail.com>
- Sander Rodríguez
