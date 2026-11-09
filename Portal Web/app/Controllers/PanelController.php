<?php
/* Panel privado: dashboard, repositorio, anuncios, bitácora y perfil. */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Anuncio;
use App\Models\Bitacora;
use App\Models\Documento;
use App\Models\Usuario;
use App\Models\Visita;

final class PanelController extends Controller
{
    private const CATEGORIAS = ['Académico', 'Convocatoria', 'Aviso', 'Evento'];

    private const ETIQUETAS = [
        'Académico' => 'etiqueta-amarillo',
        'Convocatoria' => 'etiqueta-blanco',
        'Aviso' => 'etiqueta-rojo',
        'Evento' => 'etiqueta-amarillo',
    ];

    public function index(): void
    {
        $this->requiereSesion();

        $vista = (string) ($this->request()->query['vista'] ?? 'dashboard');
        if (!in_array($vista, ['dashboard', 'repo', 'anuncios', 'bitacora', 'perfil'], true)) {
            $vista = 'dashboard';
        }

        $usuarioId = (int) ($_SESSION['usuario_id'] ?? 0);
        $perfil = $usuarioId > 0 ? Usuario::buscar($usuarioId) : false;

        $this->view('panel/index', [
            'titulo' => 'Administración · Vicerrectoría Académica UPTP',
            'cssVista' => '/assets/css/admin.css?v=15',
            'usuario' => (string) $_SESSION['usuario'],
            'usuarioNombre' => (string) ($_SESSION['usuario_nombre'] ?? $_SESSION['usuario']),
            'perfil' => $perfil,
            'vista' => $vista,
            'flash' => flash_obtener(),
            'documentos' => Documento::todos(),
            'anuncios' => Anuncio::todos(),
            'visitas' => Visita::total(),
            'bitacora' => Bitacora::recientes(25),
        ], 'panel');
    }

    /* Sube un PDF y lo registra en el repositorio. */
    public function subirDocumento(): void
    {
        $this->requiereSesion();
        $this->verificarCsrf();

        $titulo = trim((string) ($this->request()->post['titulo'] ?? ''));
        $autor = trim((string) ($this->request()->post['autor'] ?? ''));
        $categoria = trim((string) ($this->request()->post['categoria'] ?? ''));
        $anio = (int) ($this->request()->post['anio'] ?? 0);
        $descripcion = trim((string) ($this->request()->post['descripcion'] ?? ''));

        if ($titulo === '' || $autor === '' || $categoria === '' || $anio < 1900 || $anio > 2100) {
            flash('error', 'Complete todos los datos del documento.');
            $this->redirect(url_for('/panel') . '?vista=repo');
        }

        $archivo = $this->guardarPdf();

        if ($archivo === null) {
            $this->redirect(url_for('/panel') . '?vista=repo');
        }

        if ($descripcion === '') {
            $descripcion = 'Documento de ' . $categoria . ' publicado por ' . $autor . '.';
        }

        Documento::crear([
            'titulo' => $titulo,
            'autor' => $autor,
            'categoria' => $categoria,
            'anio' => $anio,
            'descripcion' => $descripcion,
            'archivo' => $archivo,
        ]);

        $this->registrar('Documento subido', $titulo);
        flash('ok', 'Documento subido al repositorio.');
        $this->redirect(url_for('/panel') . '?vista=repo');
    }

    public function eliminarDocumento(string $id): void
    {
        $this->requiereSesion();
        $this->verificarCsrf();

        $documento = Documento::buscar((int) $id);

        if ($documento === false) {
            flash('error', 'El documento no existe.');
            $this->redirect(url_for('/panel') . '?vista=repo');
        }

        Documento::eliminar((int) $id);

        /* Solo borra los PDF subidos desde el panel, no el manual inicial. */
        $ruta = (string) $documento['archivo'];
        if (str_starts_with($ruta, 'assets/documentos/documento-')) {
            @unlink(dirname(__DIR__, 2) . '/public/' . $ruta);
        }

        $this->registrar('Documento eliminado', (string) $documento['titulo']);
        flash('ok', 'Documento eliminado.');
        $this->redirect(url_for('/panel') . '?vista=repo');
    }

    public function publicarAnuncio(): void
    {
        $this->requiereSesion();
        $this->verificarCsrf();

        $titulo = trim((string) ($this->request()->post['titulo'] ?? ''));
        $texto = trim((string) ($this->request()->post['texto'] ?? ''));
        $categoria = (string) ($this->request()->post['categoria'] ?? '');

        if (!in_array($categoria, self::CATEGORIAS, true)) {
            $categoria = 'Académico';
        }

        if ($titulo === '' || $texto === '') {
            flash('error', 'Complete el título y el contenido del anuncio.');
            $this->redirect(url_for('/panel') . '?vista=anuncios');
        }

        Anuncio::crear([
            'categoria' => $categoria,
            'etiqueta' => self::ETIQUETAS[$categoria],
            'titulo' => $titulo,
            'texto' => $texto,
            'autor' => (string) ($_SESSION['usuario_nombre'] ?? $_SESSION['usuario']),
            'publicado_en' => date('Y-m-d'),
        ]);

        $this->registrar('Anuncio publicado', $titulo);
        flash('ok', 'Anuncio publicado.');
        $this->redirect(url_for('/panel') . '?vista=anuncios');
    }

    public function eliminarAnuncio(string $id): void
    {
        $this->requiereSesion();
        $this->verificarCsrf();

        $anuncio = Anuncio::buscar((int) $id);

        if ($anuncio === false) {
            flash('error', 'El anuncio no existe.');
            $this->redirect(url_for('/panel') . '?vista=anuncios');
        }

        Anuncio::eliminar((int) $id);
        $this->registrar('Anuncio eliminado', (string) $anuncio['titulo']);
        flash('ok', 'Anuncio eliminado.');
        $this->redirect(url_for('/panel') . '?vista=anuncios');
    }

    /* Guarda el PDF en public/assets/documentos y devuelve su ruta. */
    private function guardarPdf(): ?string
    {
        $archivo = $_FILES['archivo'] ?? null;

        if (!is_array($archivo) || (int) ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flash('error', 'Seleccione un archivo PDF.');
            return null;
        }

        if ((int) ($archivo['size'] ?? 0) > 10 * 1024 * 1024) {
            flash('error', 'El PDF supera el máximo de 10 MB.');
            return null;
        }

        $extension = strtolower(pathinfo((string) ($archivo['name'] ?? ''), PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            flash('error', 'El archivo debe tener extensión .pdf.');
            return null;
        }

        if (class_exists(\finfo::class)) {
            $mime = (new \finfo(FILEINFO_MIME_TYPE))->file((string) $archivo['tmp_name']);
            if (!in_array($mime, ['application/pdf', 'application/x-pdf'], true)) {
                flash('error', 'El archivo no parece un PDF válido.');
                return null;
            }
        }

        $carpeta = dirname(__DIR__, 2) . '/public/assets/documentos';

        if (!is_dir($carpeta) && !mkdir($carpeta, 0775, true)) {
            flash('error', 'No se pudo preparar la carpeta de documentos.');
            return null;
        }

        $nombre = 'documento-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.pdf';

        if (!move_uploaded_file((string) $archivo['tmp_name'], $carpeta . '/' . $nombre)) {
            flash('error', 'No se pudo guardar el archivo en el servidor.');
            return null;
        }

        return 'assets/documentos/' . $nombre;
    }

    private function requiereSesion(): void
    {
        if (!isset($_SESSION['usuario'], $_SESSION['usuario_id'])) {
            $this->redirect(url_for('/login'));
        }
    }

    private function verificarCsrf(): void
    {
        if (!csrf_verify($this->request()->post['_csrf'] ?? null)) {
            flash('error', 'La sesión expiró. Intente de nuevo.');
            $this->redirect(url_for('/panel'));
        }
    }

    private function registrar(string $accion, string $detalle): void
    {
        Bitacora::registrar(
            isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null,
            (string) ($_SESSION['usuario'] ?? 'sistema'),
            $accion,
            $detalle
        );
    }
}
