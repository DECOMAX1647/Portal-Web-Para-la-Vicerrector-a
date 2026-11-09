<?php
/* Repositorio público de documentos. */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Documento;
use App\Models\Visita;

final class RepositorioController extends Controller
{
    public function index(): void
    {
        Visita::registrar();

        $this->view('repositorio/index', [
            'titulo' => 'Repositorio · Vicerrectoría Académica UPTP',
            'zona' => 'repositorio',
            'cssVista' => '/assets/css/publico.css?v=17',
            'documentos' => Documento::todos(),
        ], 'publico');
    }
}
