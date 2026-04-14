-- Asistente IA: sesiones de chat, mensajes y reuniones solicitadas
-- Ejecutar una vez si la base ya existía sin estas tablas.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS ia_chat_sesiones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  public_token CHAR(36) NOT NULL UNIQUE,
  ip VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_ia_ses_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ia_chat_mensajes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sesion_id INT UNSIGNED NOT NULL,
  rol ENUM('user','assistant','system') NOT NULL,
  contenido MEDIUMTEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_ia_msg_sesion (sesion_id, id),
  CONSTRAINT fk_ia_msg_sesion FOREIGN KEY (sesion_id) REFERENCES ia_chat_sesiones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ia_reuniones_solicitadas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sesion_id INT UNSIGNED NOT NULL,
  nombre_contacto VARCHAR(150) NOT NULL,
  email VARCHAR(120) NOT NULL,
  telefono VARCHAR(40) NOT NULL,
  fecha_hora DATETIME NOT NULL,
  motivo TEXT NOT NULL,
  estado ENUM('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_ia_reu_sesion (sesion_id),
  KEY idx_ia_reu_fecha (fecha_hora),
  CONSTRAINT fk_ia_reu_sesion FOREIGN KEY (sesion_id) REFERENCES ia_chat_sesiones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
