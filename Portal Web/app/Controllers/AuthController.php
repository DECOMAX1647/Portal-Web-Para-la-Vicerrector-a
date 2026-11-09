<?php
/* Acceso: mostrar formulario, validar credenciales y cerrar sesión. */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bitacora;
use App\Models\Usuario;

final class AuthController extends Controller
{
    public function mostrarLogin(): void
    {
        if (isset($_SESSION['usuario'], $_SESSION['usuario_id'])) {
            $this->redirect(url_for('/panel'));
        }

        $error = (string) ($_SESSION['error_login'] ?? '');
        $usuarioPrevio = (string) ($_SESSION['usuario_previo'] ?? '');
        unset($_SESSION['error_login'], $_SESSION['usuario_previo']);

        $this->view('auth/login', [
            'titulo' => 'Iniciar sesión · Portal de Difusión Académica',
            'cssVista' => '/assets/css/login.css?v=13',
            'error' => $error,
            'usuarioPrevio' => $usuarioPrevio,
        ], 'acceso');
    }

    public function ingresar(): void
    {
        if (!csrf_verify($this->request()->post['_csrf'] ?? null)) {
            $_SESSION['error_login'] = 'La sesión expiró. Intente de nuevo.';
            $this->redirect(url_for('/login'));
        }

        $usuario = trim((string) ($this->request()->post['usuario'] ?? ''));
        $clave = (string) ($this->request()->post['clave'] ?? '');

        if ($usuario === '' || $clave === '') {
            $_SESSION['error_login'] = 'Complete el usuario y la contraseña.';
            $_SESSION['usuario_previo'] = $usuario;
            $this->redirect(url_for('/login'));
        }

        $fila = Usuario::validar($usuario, $clave);

        if ($fila === false) {
            $_SESSION['error_login'] = 'Usuario o contraseña incorrectos.';
            $_SESSION['usuario_previo'] = $usuario;
            $this->redirect(url_for('/login'));
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = (string) $fila['usuario'];
        $_SESSION['usuario_id'] = (int) $fila['id'];
        $_SESSION['usuario_nombre'] = (string) $fila['nombre'];
        $_SESSION['usuario_rol'] = (string) $fila['rol'];

        Bitacora::registrar(
            (int) $fila['id'],
            (string) $fila['usuario'],
            'Inicio de sesión',
            'Ingresó al panel de administración'
        );

        $this->redirect(url_for('/panel'));
    }

    public function cerrar(): void
    {
        if (!csrf_verify($this->request()->post['_csrf'] ?? null)) {
            $this->redirect(url_for('/panel'));
        }

        if (isset($_SESSION['usuario'])) {
            Bitacora::registrar(                isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null,
                (string) $_SESSION['usuario'],
                'Cierre de sesión',
                'Salió del panel de administración'
            );
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        $this->redirect(url_for('/login'));
    }
}
