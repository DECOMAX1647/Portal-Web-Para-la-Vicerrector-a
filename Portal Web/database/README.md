# Base de datos

Motor: **MySQL / MariaDB** (Laragon).

## Instalación con Laragon

1. Abra Laragon y pulse **Start All** (o inicie MySQL).
2. Importe el archivo `portal_uptp.sql`:
   - Desde **phpMyAdmin** (Laragon → Database → phpMyAdmin) → pestaña *Importar*, o
   - Desde la consola:
     ```bat
     mysql -u root < database/portal_uptp.sql
     ```
3. Revise las credenciales en el archivo `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_uptp
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Laragon usa por defecto el usuario `root` sin contraseña.

## Servir el proyecto con Laragon

1. Copie la carpeta del proyecto dentro de `C:\laragon\www` (por ejemplo, `C:\laragon\www\portal`).
2. Abra `http://portal.test` o `http://localhost/portal`.
3. El archivo `.htaccess` de la raíz se encarga de las rutas y de los assets.

> El documento raíz debe ser la carpeta del proyecto. No hace falta apuntar a `public/`.

## Tablas

| Tabla | Contenido |
|---|---|
| `usuarios` | Cuentas del sistema (contraseña con `password_hash`). |
| `documentos` | PDFs del repositorio y su ruta en `public/assets/documentos`. |
| `anuncios` | Comunicados que se muestran en el Inicio. |
| `visitas` | Personas que han visto el portal (una fila por navegador). |
| `bitacora` | Historial de acciones del panel. |

## Credenciales iniciales

- Usuario: **admin**
- Contraseña: **123456**

La contraseña se guarda cifrada; el archivo `.sql` ya trae el hash.
