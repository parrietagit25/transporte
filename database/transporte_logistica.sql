-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-04-2026 a las 03:38:28
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `transporte_logistica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_web`
--

CREATE TABLE `configuracion_web` (
  `id` int(10) UNSIGNED NOT NULL,
  `clave` varchar(80) NOT NULL,
  `valor` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_web`
--

INSERT INTO `configuracion_web` (`id`, `clave`, `valor`, `updated_at`) VALUES
(1, 'empresa_nombre', 'Super Heavy Lift', '2026-04-12 15:19:49'),
(2, 'telefono', '+507 6974-9731', '2026-04-12 03:33:00'),
(3, 'whatsapp_numero', '50769749731', '2026-04-12 03:33:00'),
(4, 'whatsapp_mensaje', 'Hola Super Heavy Lift, quiero información sobre transporte especializado.', '2026-04-12 15:19:49'),
(5, 'correo', 'comercial@superheavylift.com', '2026-04-12 15:19:49'),
(6, 'direccion', 'Zona industrial La Favorita, Bodega 12, Mosquera — Cundinamarca, Colombia', '2026-04-12 03:33:00'),
(7, 'hero_titulo', 'Potencia operativa para su carga más exigente', '2026-04-12 03:33:00'),
(8, 'hero_subtitulo', 'Transporte pesado, gandolas, cama bajas, modulares y logística integral con estándares de seguridad y trazabilidad de punta a punta.', '2026-04-12 03:33:00'),
(9, 'facebook_url', 'https://www.facebook.com/', '2026-04-12 03:33:00'),
(10, 'instagram_url', 'https://www.instagram.com/', '2026-04-12 03:33:00'),
(11, 'linkedin_url', 'https://www.linkedin.com/', '2026-04-12 03:33:00'),
(12, 'logo_path', '/assets/img/logo.png', '2026-04-12 02:40:35'),
(13, 'hero_imagen_path', 'https://interwf.com/hs-fs/hubfs/AdobeStock_985189555.jpg?width=1920&height=1080&name=AdobeStock_985189555.jpg', '2026-04-12 03:33:00'),
(14, 'meta_description', 'Empresa de transporte de carga pesada, gandolas, cama bajas, modulares y logística especializada en Colombia.', '2026-04-12 03:33:00'),
(15, 'mapa_embed_url', '', '2026-04-12 03:33:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos_recibidos`
--

CREATE TABLE `contactos_recibidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(120) NOT NULL,
  `telefono` varchar(40) DEFAULT NULL,
  `asunto` varchar(200) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `cliente_nombre` varchar(150) NOT NULL,
  `cliente_empresa` varchar(200) DEFAULT NULL,
  `cliente_telefono` varchar(40) NOT NULL,
  `cliente_correo` varchar(120) NOT NULL,
  `tipo_servicio` varchar(120) NOT NULL,
  `origen` varchar(200) NOT NULL,
  `destino` varchar(200) NOT NULL,
  `tipo_carga` varchar(200) NOT NULL,
  `peso_estimado` varchar(80) DEFAULT NULL,
  `dimensiones` varchar(120) DEFAULT NULL,
  `fecha_requerida` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('pendiente','en_revision','enviada','aprobada','rechazada','cerrada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cotizaciones`
--

INSERT INTO `cotizaciones` (`id`, `cliente_nombre`, `cliente_empresa`, `cliente_telefono`, `cliente_correo`, `tipo_servicio`, `origen`, `destino`, `tipo_carga`, `peso_estimado`, `dimensiones`, `fecha_requerida`, `observaciones`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Carolina Méndez', 'Hidroandes S.A.S.', '+57 310 4448899', 'cmendez@hidroandes.co', 'Cama bajas', 'Bogotá D.C.', 'Medellín', 'Excavadora hidráulica 36 ton', '36 t', '11,2 x 3,4 x 3,6 m', '2026-05-18', 'Se requiere ventana nocturna y póliza ampliada.', 'en_revision', '2026-03-02 14:12:00', NULL),
(2, 'Luis Ortega', 'Metalúrgica El Faro', '+57 320 7788120', 'lortega@elfaro.com', 'Movimiento de carga pesada especializada', 'Cartagena', 'Yumbo', 'Prensa industrial desarmada parcialmente', '42 t', 'Plataforma 13 m', '2026-04-10', 'Coordinar con taller para retiro de accesorios.', 'pendiente', '2026-03-05 19:40:00', NULL),
(3, 'Andrea Ruiz', 'Campamentos del Norte', '+57 301 9900112', 'aruiz@campnorte.co', 'Alquiler de modulares', 'Mosquera', 'Segovia (Antioquia)', '12 módulos habitacionales + comedor', 'N/A', 'Estándar 40 ft habitable', '2026-06-01', 'Necesitamos entrega escalonada en dos semanas.', 'enviada', '2026-03-08 16:05:00', NULL),
(4, 'Felipe Duarte', 'Red Logística Pacífico', '+57 315 2233445', 'fduarte@rlpacifico.com', 'Logística', 'Buenaventura', 'Cali y Palmira', 'Contenedores refrigerados', 'Mixto', '40 pies', '2026-03-28', 'Priorizar cadena de frío y ventanas de descarga matutinas.', 'aprobada', '2026-03-10 13:22:00', NULL),
(5, 'María Prada', 'Ingeniería Horizonte', '+57 300 6677889', 'mprada@horizonte-ing.com', 'Transporte en gandola (mula)', 'Puerto Brisa', 'Patio La Tebaida', 'Contenedores dry 40 pies', 'Por unidad', 'Estándar', '2026-04-02', 'Operación recurrente, cotizar paquete mensual.', 'pendiente', '2026-03-12 21:50:00', '2026-04-12 03:28:42'),
(6, 'Jorge Salinas', 'EPC Andina', '+57 318 4455667', 'jsalinas@epcandina.com', 'Movimiento de carga pesada especializada', 'Ipiales', 'Pasto', 'Transformador 110 MVA (tramo final)', 'Sobredimensionado', 'Convoy especial', '2026-07-05', 'Coordinación con operador de red y cuerpo de bomberos.', 'rechazada', '2026-03-15 15:18:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ia_chat_mensajes`
--

CREATE TABLE `ia_chat_mensajes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sesion_id` int(10) UNSIGNED NOT NULL,
  `rol` enum('user','assistant','system') NOT NULL,
  `contenido` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ia_chat_mensajes`
--

INSERT INTO `ia_chat_mensajes` (`id`, `sesion_id`, `rol`, `contenido`, `created_at`) VALUES
(1, 3, 'user', 'hola', '2026-04-12 16:05:16'),
(2, 3, 'assistant', '¡Hola! ¿En qué puedo ayudarte hoy?', '2026-04-12 16:05:18'),
(3, 3, 'user', 'quiero saber que productos tienen?', '2026-04-12 16:05:55'),
(4, 3, 'assistant', 'Ofrecemos los siguientes servicios en Super Heavy Lift:\n\n1. **Transporte en gandola (mula):** Movilización de volquetas, contenedores y unidades de gran porte en rutas controladas, con planeación de curvas, pendientes y puntos de apoyo.\n\n2. **Alquiler de modulares:** Soluciones habitables y operativas para obra, campamentos y puntos de control logístico, con entrega, montaje y soporte de mantenimiento básico.\n\n3. **Cama bajas:** Transporte de maquinaria y piezas sobredimensionadas con plataformas de altura reducida, anclajes certificados y esquemas de izaje coordinados.\n\n4. **Movimiento de carga pesada especializada:** Proyectos de alto riesgo operativo, incluyendo transformadores, turbinas, prensas industriales y estructuras metálicas, con ingeniería de transporte y protocolos de seguridad.\n\n5. **Logística:** Integración de almacenamiento intermedio, consolidación, distribución y última milla para cadena de suministro industrial.\n\nSi necesitas más detalles sobre alguno de estos servicios, no dudes en preguntar.', '2026-04-12 16:06:02'),
(5, 3, 'user', 'podria agendar una cita ? necesito mas informacion', '2026-04-12 16:06:44'),
(6, 3, 'assistant', 'Claro, puedo ayudarte a agendar una cita. Para ello, por favor proporcioname la siguiente información:\n\n1. Nombre completo.\n2. Correo electrónico.\n3. Teléfono con indicativo.\n4. Fecha y hora deseadas (con zona horaria Colombia si no la indicas).\n5. Motivo o temas a tratar en la reunión.\n\nUna vez tenga toda esta información confirmada, procederé a agendar la reunión.', '2026-04-12 16:06:51'),
(7, 3, 'user', 'Pedro Arrieta\npedroarrieta25@hotmail.com\n60026773\ncualquier dia de la semana que viene\nel motivo es dar detalle del trabajo que quiero realizar', '2026-04-12 16:08:03'),
(8, 3, 'assistant', 'Gracias, Pedro. Solo necesito que me confirmes una hora específica para la reunión la próxima semana. ¿Tienes alguna hora en mente o prefieres que te sugiera alguna? Además, por favor confirma la zona horaria, ya que asumo que es Colombia.', '2026-04-12 16:08:05'),
(9, 3, 'user', '2 pm', '2026-04-12 16:08:22'),
(10, 3, 'assistant', 'Perfecto. Entonces, para resumir, tengo la siguiente información para agendar la reunión:\n\n- **Nombre completo:** Pedro Arrieta  \n- **Correo electrónico:** pedroarrieta25@hotmail.com  \n- **Teléfono:** 60026773  \n- **Fecha y hora deseadas:** Cualquier día de la semana que viene a las 2:00 PM (zona horaria Colombia)  \n- **Motivo:** Dar detalle del trabajo que quiero realizar  \n\n¿Hay algún día específico de la próxima semana que prefieras para la reunión?', '2026-04-12 16:08:25'),
(11, 3, 'user', 'miercoles', '2026-04-12 16:08:50'),
(12, 3, 'assistant', 'La reunión ha sido agendada con éxito para el miércoles 25 de octubre a las 2:00 PM (hora Colombia). \n\nSi necesitas algo más o tienes alguna pregunta, no dudes en decírmelo. ¡Nos vemos en la reunión!', '2026-04-12 16:08:54'),
(13, 4, 'user', 'hola', '2026-04-12 16:37:26'),
(14, 4, 'assistant', '¡Hola! ¿En qué puedo ayudarte hoy?', '2026-04-12 16:37:29'),
(15, 5, 'user', 'hola', '2026-04-12 16:44:23'),
(16, 5, 'assistant', '¡Hola! ¿En qué puedo ayudarte hoy?', '2026-04-12 16:44:25'),
(17, 6, 'user', 'hola', '2026-04-12 17:02:57'),
(18, 6, 'assistant', '¡Hola! ¿Cómo puedo asistirte hoy?', '2026-04-12 17:02:59'),
(19, 6, 'user', 'i can in english', '2026-04-12 17:03:12'),
(20, 6, 'assistant', 'Lo siento, pero solo puedo comunicarme en español. ¿En qué puedo ayudarte hoy?', '2026-04-12 17:03:14'),
(21, 6, 'user', 'why not?', '2026-04-12 17:03:25'),
(22, 6, 'assistant', 'Disculpa, mi capacidad de respuesta está limitada al español. Estoy aquí para ayudarte con cualquier consulta que tengas. ¿Cómo puedo asistirte?', '2026-04-12 17:03:26'),
(23, 7, 'user', 'hola', '2026-04-12 17:06:12'),
(24, 7, 'assistant', '¡Hola! ¿En qué puedo ayudarte hoy?', '2026-04-12 17:06:14'),
(25, 7, 'user', 'i can in english?', '2026-04-12 17:06:27'),
(26, 7, 'assistant', 'Yes, you can continue in English. How can I assist you today?', '2026-04-12 17:06:28'),
(27, 7, 'user', 'ok, your have transpor in modular?', '2026-04-12 17:06:55'),
(28, 7, 'assistant', 'We do not offer transportation for modular units specifically, but we do provide modular rentals, which include habitable and operational solutions for construction sites, camps, and logistic control points. This service includes delivery, assembly, and basic maintenance support.\n\nIf you need further information or would like to explore our services, please let me know!', '2026-04-12 17:06:57'),
(29, 7, 'user', 'ok i wanna a meet with your ?', '2026-04-12 17:07:30'),
(30, 7, 'assistant', 'I can help you schedule a meeting with our sales team. Could you please provide me with the following details?\n\n1. Full name\n2. Email address\n3. Phone number\n4. Desired date and time for the meeting (in America/Bogota timezone unless you specify otherwise)\n5. Reason or topics you would like to discuss during the meeting\n\nOnce I have this information, I can arrange the meeting for you.', '2026-04-12 17:07:32'),
(31, 7, 'user', 'Jotn show\ntucupita@travel.com\n60026773\n14 april 3 pm\ni wanna meeting because i want speck arround the jon transport', '2026-04-12 17:10:05'),
(32, 7, 'assistant', 'Thank you for providing the details. Here\'s a summary of your meeting request:\n\n- **Full Name:** Jotn Show\n- **Email Address:** tucupita@travel.com\n- **Phone Number:** 60026773\n- **Desired Date and Time:** April 14th at 3 PM (America/Bogota timezone)\n- **Reason for Meeting:** Discussion about transport options\n\nPlease confirm if everything is correct, and I will proceed to schedule the meeting.', '2026-04-12 17:10:07'),
(33, 7, 'user', 'yes is correct', '2026-04-12 17:11:06'),
(34, 7, 'assistant', 'Your meeting has been successfully scheduled for April 14th at 3 PM. A sales representative will reach out to you to discuss transport options. If you have any further questions or need assistance, feel free to ask. Thank you!', '2026-04-12 17:11:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ia_chat_sesiones`
--

CREATE TABLE `ia_chat_sesiones` (
  `id` int(10) UNSIGNED NOT NULL,
  `public_token` char(36) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ia_chat_sesiones`
--

INSERT INTO `ia_chat_sesiones` (`id`, `public_token`, `ip`, `user_agent`, `created_at`) VALUES
(1, '42d7bb56-273c-4ee6-a1bd-d6acfb2d66a7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 15:59:41'),
(2, '14876b74-9b20-4b63-876f-7399a290a4da', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 16:00:50'),
(3, 'c128dae7-0c83-4e2c-a76e-1cb841544eb5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 16:05:16'),
(4, '498527bf-a3df-4913-862d-8bb56b3e44ab', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 16:37:26'),
(5, '6589fcfa-849c-44e3-ac7c-2c44eeb3c071', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 16:44:23'),
(6, 'ebbe8baa-cdbd-46df-8597-af87b3a9e8ab', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 17:02:57'),
(7, 'd43f1b58-45dc-4dc8-ba94-428910c50156', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 17:06:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ia_reuniones_solicitadas`
--

CREATE TABLE `ia_reuniones_solicitadas` (
  `id` int(10) UNSIGNED NOT NULL,
  `sesion_id` int(10) UNSIGNED NOT NULL,
  `nombre_contacto` varchar(150) NOT NULL,
  `email` varchar(120) NOT NULL,
  `telefono` varchar(40) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `motivo` text NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ia_reuniones_solicitadas`
--

INSERT INTO `ia_reuniones_solicitadas` (`id`, `sesion_id`, `nombre_contacto`, `email`, `telefono`, `fecha_hora`, `motivo`, `estado`, `created_at`) VALUES
(1, 3, 'Pedro Arrieta', 'pedroarrieta25@hotmail.com', '60026773', '2023-10-25 14:00:00', 'Dar detalle del trabajo que quiero realizar', 'pendiente', '2026-04-12 16:08:53'),
(2, 7, 'Jotn Show', 'tucupita@travel.com', '60026773', '2024-04-14 15:00:00', 'Discussion about transport options', 'pendiente', '2026-04-12 17:11:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(280) NOT NULL,
  `resumen` text NOT NULL,
  `contenido` longtext NOT NULL,
  `imagen_destacada` varchar(255) DEFAULT NULL,
  `fecha_publicacion` date NOT NULL,
  `estado` enum('borrador','publicado') NOT NULL DEFAULT 'borrador',
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `titulo`, `slug`, `resumen`, `contenido`, `imagen_destacada`, `fecha_publicacion`, `estado`, `destacado`, `created_at`, `updated_at`) VALUES
(1, 'Traslado de retroexcavadora y compactador para obra vial', 'traslado-maquinaria-pesada-obra-vial', 'Coordinamos el movimiento nocturno de maquinaria amarilla entre patio técnico y frente de obra, minimizando impacto urbano.', 'Para un consorcio vial en la región central, Atlas Cargo planificó el traslado de una retroexcavadora y un compactador sobre cama baja, con ventana nocturna, acompañamiento de seguridad vial y verificación de alturas en puentes. El cliente obtuvo cumplimiento de ventana contractual y cero incidentes en riesgo operativo.\n\nEl proyecto incluyó checklist de amarre, inspección fotográfica y entrega de acta de conformidad en destino.', 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80', '2026-03-28', 'publicado', 1, '2026-04-12 02:28:33', NULL),
(2, 'Transporte en cama baja: prensa industrial de 42 toneladas', 'transporte-cama-baja-prensa-industrial', 'Movimiento interplanta de prensa hidráulica con desarme parcial de accesorios y ruta alterna por restricción de peso.', 'Se ejecutó un estudio de distribución de pesos y anclajes, además de coordinación con talleres para retiro de componentes periféricos. La ruta contempló bypass en peajes y apoyo de grúa auxiliar en maniobra final. El activo llegó en la fecha comprometida para inicio de línea de ensamble.', 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1200&q=80', '2026-03-12', 'publicado', 1, '2026-04-12 02:28:33', NULL),
(3, 'Operativo con gándolas: contenedores de 40 pies en cadena', 'operativos-gandolas-contenedores', 'Secuencia de ida y vuelta con dos unidades gándola para abastecimiento de patio portuario alterno.', 'Atlas Cargo desplegó dos equipos mula con ventanas de carga sincronizadas, reduciendo tiempos de espera en muelle y optimizando turnos de conductores. Se implementó tablero de seguimiento compartido con el cliente para visibilidad en tiempo casi real.', 'https://images.unsplash.com/photo-1494412519320-aa613dfb7738?auto=format&fit=crop&w=1200&q=80', '2026-02-20', 'publicado', 1, '2026-04-12 02:28:33', NULL),
(4, 'Alquiler de modulares para campamento minero', 'alquiler-modulares-campamento-minero', 'Dotación de 18 módulos habitacionales y comedor central para proyecto de 10 meses en zona remota.', 'Incluimos transporte, nivelación de plataformas, conexiones eléctricas básicas y plan de mantenimiento preventivo mensual. El cliente destacó la condición de los módulos y la rapidez de desmontaje al cierre del proyecto.', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80', '2026-02-05', 'publicado', 1, '2026-04-12 02:28:33', NULL),
(5, 'Solución logística para cadena de autopartes', 'soluciones-logisticas-autopartes', 'Cross-docking y distribución regional con ventanas de entrega a plantas OEM.', 'Diseñamos rutas milk-run con consolidación nocturna y entregas matutinas. Se redujeron faltantes en línea en un 27% en el primer trimestre de operación conjunta, con tablero semanal de KPIs compartido.', 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1200&q=80', '2026-01-22', 'publicado', 0, '2026-04-12 02:28:33', NULL),
(6, 'Movimiento de estructura metálica de gran volumen', 'movimiento-estructuras-gran-volumen', 'Traslado de truss industrial de 28 metros con escolta y permisos especiales en corredor nacional.', 'Se trabajó con ingeniería de ruta, levantamiento LIDAR simplificado en curvas críticas y acompañamiento de escolta certificada. La estructura fue entregada para montaje en sitio con tolerancia milimétrica requerida por el cliente EPC.', 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1200&q=80', '2026-01-08', 'publicado', 0, '2026-04-12 02:28:33', NULL),
(7, 'Coordinación de rutas especiales con múltiples actores', 'coordinacion-rutas-especiales', 'Gestión integral de permisos ante organismos de tránsito y operadores de infraestructura.', 'Centralizamos la ventanilla de permisos, cronograma de movilización y comunicación con cuerpos de seguridad. El cliente redujo retrabajos administrativos y aceleró la autorización de segunda etapa del proyecto.', 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1200&q=80', '2025-12-14', 'publicado', 0, '2026-04-12 02:28:33', NULL),
(8, 'Transporte de carga sobredimensionada: transformador', 'transporte-carga-sobredimensionada-transformador', 'Proyecto eléctrico con transformador de potencia y convoy especializado.', 'Atlas Cargo lideró la ingeniería de transporte, selección de eje modular, pruebas de frenado y plan de contingencia climática. La pieza fue posicionada en subestación con ventana única acordada con el operador de red.', 'https://images.unsplash.com/photo-1581092162384-8987c1d64718?auto=format&fit=crop&w=1200&q=80', '2025-11-30', 'publicado', 0, '2026-04-12 02:28:33', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `descripcion` text NOT NULL,
  `beneficios` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `titulo`, `slug`, `descripcion`, `beneficios`, `imagen`, `orden`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Transporte en gandola (mula)', 'transporte-gandola-mula', 'Operación con equipos tipo mula para movilizar volquetas, contenedores y unidades de gran porte en rutas controladas, con planeación de curvas, pendientes y puntos de apoyo.', 'Coordinación de rutas y permisos\nSupervisión en sitio de carga y descarga\nConductores certificados en maniobras especiales\nSeguros y pólizas alineadas al riesgo operativo', 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1200&q=80', 1, 1, '2026-04-12 02:28:33', NULL),
(2, 'Alquiler de modulares', 'alquiler-modulares', 'Soluciones habitables y operativas para obra, campamentos y puntos de control logístico, con entrega, montaje y soporte de mantenimiento básico.', 'Modulares en excelente estado\nLogística de traslado y ubicación\nOpciones de climatización y mobiliario\nContratos flexibles por proyecto', 'https://faymonvilleiberica.com/wp-content/uploads/2025/09/image-4-1024x657.png', 2, 1, '2026-04-12 02:28:33', '2026-04-12 03:17:05'),
(3, 'Cama bajas', 'cama-bajas', 'Transporte de maquinaria y piezas sobredimensionadas con plataformas de altura reducida, anclajes certificados y esquemas de izaje coordinados.', 'Capacidad según requerimiento del cliente\nEstudio de diagrama de carga\nAcompañamiento en permisos de oversized\nMonitoreo durante el trayecto', 'https://remolquestramontana.com/wp-content/uploads/2023/04/suspension-4-ejes-1.jpg', 3, 1, '2026-04-12 02:28:33', '2026-04-12 03:18:11'),
(4, 'Movimiento de carga pesada especializada', 'carga-pesada-especializada', 'Proyectos de alto riesgo operativo: transformadores, turbinas, prensas industriales y estructuras metálicas, con ingeniería de transporte y protocolos de seguridad.', 'Ingeniería aplicada a cada movimiento\nPlanes de contingencia\nCuadrillas especializadas\nReportes de avance al cliente', 'https://www.diarioelcanal.com/wp-content/uploads/2022/05/Pieza-1024x680.jpg', 4, 1, '2026-04-12 02:28:33', '2026-04-12 03:21:11'),
(5, 'Logística', 'logistica', 'Integración de almacenamiento intermedio, consolidación, distribución y última milla para cadena de suministro industrial.', 'Visibilidad de inventarios\nVentanas de entrega confiables\nReducción de tiempos muertos\nKPIs compartidos con su equipo', 'https://thelogisticsworld.com/wp-content/uploads/2023/06/manos-sosteniendo-una-tableta-de-computadora-contra-el-telon-de-fondo-de-un-barco-que-transporta-contenedores.jpg', 5, 1, '2026-04-12 02:28:33', '2026-04-12 03:19:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_admin`
--

CREATE TABLE `usuarios_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(120) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_admin`
--

INSERT INTO `usuarios_admin` (`id`, `username`, `password_hash`, `email`, `activo`, `created_at`) VALUES
(1, 'admin', '$2y$10$BbAUqAn2REVBGSSYFHkdiOKbs3izpWS9tNEwcWN49hVcZywNp.fDm', 'operaciones@salernoheavylift.com', 1, '2026-04-12 02:28:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion_web`
--
ALTER TABLE `configuracion_web`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `contactos_recibidos`
--
ALTER TABLE `contactos_recibidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_leido` (`leido`);

--
-- Indices de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cot_estado` (`estado`),
  ADD KEY `idx_cot_created` (`created_at`);

--
-- Indices de la tabla `ia_chat_mensajes`
--
ALTER TABLE `ia_chat_mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ia_msg_sesion` (`sesion_id`,`id`);

--
-- Indices de la tabla `ia_chat_sesiones`
--
ALTER TABLE `ia_chat_sesiones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `public_token` (`public_token`),
  ADD KEY `idx_ia_ses_created` (`created_at`);

--
-- Indices de la tabla `ia_reuniones_solicitadas`
--
ALTER TABLE `ia_reuniones_solicitadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ia_reu_sesion` (`sesion_id`),
  ADD KEY `idx_ia_reu_fecha` (`fecha_hora`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_pub_estado_fecha` (`estado`,`fecha_publicacion`),
  ADD KEY `idx_destacado` (`destacado`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion_web`
--
ALTER TABLE `configuracion_web`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `contactos_recibidos`
--
ALTER TABLE `contactos_recibidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ia_chat_mensajes`
--
ALTER TABLE `ia_chat_mensajes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `ia_chat_sesiones`
--
ALTER TABLE `ia_chat_sesiones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ia_reuniones_solicitadas`
--
ALTER TABLE `ia_reuniones_solicitadas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ia_chat_mensajes`
--
ALTER TABLE `ia_chat_mensajes`
  ADD CONSTRAINT `fk_ia_msg_sesion` FOREIGN KEY (`sesion_id`) REFERENCES `ia_chat_sesiones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ia_reuniones_solicitadas`
--
ALTER TABLE `ia_reuniones_solicitadas`
  ADD CONSTRAINT `fk_ia_reu_sesion` FOREIGN KEY (`sesion_id`) REFERENCES `ia_chat_sesiones` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
