<?php
/* Rutas del portal: cada URL apunta a un controlador y su método. */

declare(strict_types=1);

use App\Core\Router;

return static function (Router $router): void {
    // Área pública
    $router->get('/', 'HomeController@index');
    $router->get('/repositorio', 'RepositorioController@index');

    // Acceso
    $router->get('/login', 'AuthController@mostrarLogin');
    $router->post('/login', 'AuthController@ingresar');
    $router->post('/cerrar-sesion', 'AuthController@cerrar');

    // Panel privado
    $router->get('/panel', 'PanelController@index');
    $router->post('/panel/documentos', 'PanelController@subirDocumento');
    $router->post('/panel/documentos/{id}/eliminar', 'PanelController@eliminarDocumento');
    $router->post('/panel/anuncios', 'PanelController@publicarAnuncio');
    $router->post('/panel/anuncios/{id}/eliminar', 'PanelController@eliminarAnuncio');
};
