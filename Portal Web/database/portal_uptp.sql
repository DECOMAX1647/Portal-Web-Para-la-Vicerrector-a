-- Base de datos del Portal de Difusión Académica (MariaDB / MySQL).
-- Instalación limpia. Importar con:
--   mysql -u root < database/portal_uptp.sql
-- o desde phpMyAdmin (pestaña Importar).

CREATE DATABASE IF NOT EXISTS portal_uptp
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE portal_uptp;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS visitas;
DROP TABLE IF EXISTS bitacora;
DROP TABLE IF EXISTS anuncios;
DROP TABLE IF EXISTS documentos;
DROP TABLE IF EXISTS usuarios;
SET FOREIGN_KEY_CHECKS = 1;

-- Cuentas del sistema.
CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario VARCHAR(50) NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  clave_hash VARCHAR(255) NOT NULL,
  rol VARCHAR(20) NOT NULL DEFAULT 'admin',
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_usuarios_usuario (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documentos del repositorio (PDF).
CREATE TABLE documentos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(200) NOT NULL,
  autor VARCHAR(150) NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  anio SMALLINT UNSIGNED NOT NULL,
  descripcion TEXT NOT NULL,
  archivo VARCHAR(255) NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_documentos_creado (creado_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Anuncios publicados en el Inicio.
CREATE TABLE anuncios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  categoria VARCHAR(80) NOT NULL,
  etiqueta VARCHAR(40) NOT NULL DEFAULT 'etiqueta-blanco',
  titulo VARCHAR(200) NOT NULL,
  texto TEXT NOT NULL,
  autor VARCHAR(150) NOT NULL,
  publicado_en DATE NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_anuncios_publicado (publicado_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Personas que han visitado el portal (una fila por navegador).
CREATE TABLE visitas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  token CHAR(32) NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_visitas_token (token),
  KEY idx_visitas_creado (creado_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Historial de acciones del panel.
CREATE TABLE bitacora (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NULL,
  usuario VARCHAR(50) NOT NULL,
  accion VARCHAR(60) NOT NULL,
  detalle VARCHAR(255) NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_bitacora_creado (creado_en),
  CONSTRAINT fk_bitacora_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Datos iniciales
-- --------------------------------------------------------------------

-- Usuario: admin / 123456 (contraseña guardada con password_hash).
INSERT INTO usuarios (usuario, nombre, clave_hash, rol, activo) VALUES
('admin', 'Administrador del Portal',
 '$2y$10$3mHvwPcdMloR5KdWOaToKeYg6fwnldY5RXK5.eF0dn1iTBCG8KqnS',
 'admin', 1);

INSERT INTO documentos (titulo, autor, categoria, anio, descripcion, archivo) VALUES
('Manual de Usuario del Portal', 'Vicerrectoría Académica', 'Guía', 2026,
 'Guía de uso del portal de difusión académica para la comunidad universitaria.',
 'assets/documentos/manual-de-usuario.pdf');

INSERT INTO anuncios (categoria, etiqueta, titulo, texto, autor, publicado_en) VALUES
('Académico', 'etiqueta-amarillo',
 'Jornada de inducción para nuevos docentes',
 'La actividad se realizará en el auditorio central. Se tratarán temas de evaluación, planificación y uso del repositorio institucional.',
 'Dirección de Docencia', '2026-10-02'),
('Convocatoria', 'etiqueta-blanco',
 'Convocatoria: trabajos de grado al repositorio',
 'Invitamos a los egresados a publicar sus trabajos de grado en el repositorio digital de la universidad.',
 'Coordinación de Investigación', '2026-09-20');
