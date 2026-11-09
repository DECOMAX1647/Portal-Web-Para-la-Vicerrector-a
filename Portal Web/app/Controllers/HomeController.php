<?php
/* Página pública de Inicio. */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Anuncio;
use App\Models\Visita;

final class HomeController extends Controller
{
    public function index(): void
    {
        Visita::registrar();

        $this->view('home/index', [
            'titulo' => 'Portal de Difusión de Información Académica · Vicerrectoría Académica UPTP',
            'zona' => 'inicio',
            'cssVista' => '/assets/css/publico.css?v=17',
            'anuncios' => Anuncio::todos(6),
        ], 'publico');
    }
}
