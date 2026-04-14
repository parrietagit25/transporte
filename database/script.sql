-- Super Heavy Lift — Esquema inicial + datos demo
-- MySQL 8+ recomendado (utf8mb4)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS ia_chat_mensajes;
DROP TABLE IF EXISTS ia_reuniones_solicitadas;
DROP TABLE IF EXISTS ia_chat_sesiones;
DROP TABLE IF EXISTS contactos_recibidos;
DROP TABLE IF EXISTS cotizaciones;
DROP TABLE IF EXISTS publicaciones;
DROP TABLE IF EXISTS servicios;
DROP TABLE IF EXISTS configuracion_web;
DROP TABLE IF EXISTS usuarios_admin;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios_admin (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(120) NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE servicios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  descripcion TEXT NOT NULL,
  beneficios TEXT NOT NULL,
  imagen VARCHAR(255) DEFAULT NULL,
  orden INT NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE publicaciones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(280) NOT NULL UNIQUE,
  resumen TEXT NOT NULL,
  contenido LONGTEXT NOT NULL,
  imagen_destacada VARCHAR(255) DEFAULT NULL,
  fecha_publicacion DATE NOT NULL,
  estado ENUM('borrador','publicado') NOT NULL DEFAULT 'borrador',
  destacado TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_pub_estado_fecha (estado, fecha_publicacion),
  KEY idx_destacado (destacado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cotizaciones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_nombre VARCHAR(150) NOT NULL,
  cliente_empresa VARCHAR(200) DEFAULT NULL,
  cliente_telefono VARCHAR(40) NOT NULL,
  cliente_correo VARCHAR(120) NOT NULL,
  tipo_servicio VARCHAR(120) NOT NULL,
  origen VARCHAR(200) NOT NULL,
  destino VARCHAR(200) NOT NULL,
  tipo_carga VARCHAR(200) NOT NULL,
  peso_estimado VARCHAR(80) DEFAULT NULL,
  dimensiones VARCHAR(120) DEFAULT NULL,
  fecha_requerida DATE DEFAULT NULL,
  observaciones TEXT,
  subtotal_sin_iva DECIMAL(14,2) DEFAULT NULL,
  otros_cargos DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  iva_pct DECIMAL(5,2) NOT NULL DEFAULT 19.00,
  iva_monto DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(14,2) DEFAULT NULL,
  estado ENUM('pendiente','en_revision','enviada','aprobada','rechazada','cerrada') NOT NULL DEFAULT 'pendiente',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_cot_estado (estado),
  KEY idx_cot_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE configuracion_web (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(80) NOT NULL UNIQUE,
  valor TEXT,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contactos_recibidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(120) NOT NULL,
  telefono VARCHAR(40) DEFAULT NULL,
  asunto VARCHAR(200) DEFAULT NULL,
  mensaje TEXT NOT NULL,
  leido TINYINT(1) NOT NULL DEFAULT 0,
  ip VARCHAR(45) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_leido (leido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ia_chat_sesiones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  public_token CHAR(36) NOT NULL UNIQUE,
  ip VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_ia_ses_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ia_chat_mensajes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sesion_id INT UNSIGNED NOT NULL,
  rol ENUM('user','assistant','system') NOT NULL,
  contenido MEDIUMTEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_ia_msg_sesion (sesion_id, id),
  CONSTRAINT fk_ia_msg_sesion FOREIGN KEY (sesion_id) REFERENCES ia_chat_sesiones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ia_reuniones_solicitadas (
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

-- Usuario admin: usuario "admin" / contraseña "Admin2024!"
INSERT INTO usuarios_admin (username, password_hash, email, activo) VALUES
('admin', '$2y$10$BbAUqAn2REVBGSSYFHkdiOKbs3izpWS9tNEwcWN49hVcZywNp.fDm', 'operaciones@superheavylift.com', 1);

INSERT INTO configuracion_web (clave, valor) VALUES
('empresa_nombre', 'Super Heavy Lift'),
('telefono', '+57 601 555 0142'),
('whatsapp_numero', '573001112233'),
('whatsapp_mensaje', 'Hola, soy de una empresa industrial y necesito cotizar transporte pesado / logística con Super Heavy Lift.'),
('correo', 'comercial@superheavylift.com'),
('direccion', 'Zona industrial La Favorita, Bodega 12, Mosquera — Cundinamarca, Colombia'),
('hero_titulo', 'Le movemos el peso que a otros les frena'),
('hero_subtitulo', 'Gándolas, cama bajas, convoyes sobredimensionados, modulares para obra y logística de cadena industrial. Un solo equipo comercial y operativo, con ingeniería de transporte y cumplimiento medible en cada kilómetro.'),
('facebook_url', 'https://www.facebook.com/'),
('instagram_url', 'https://www.instagram.com/'),
('linkedin_url', 'https://www.linkedin.com/'),
('logo_path', '/assets/img/logo.png'),
('hero_imagen_path', 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=2400&q=85'),
('meta_description', 'Super Heavy Lift: transporte de carga pesada, gándolas, cama bajas, modulares y logística para minería, energía, obra civil e industria en Colombia.'),
('mapa_embed_url', '');

INSERT INTO servicios (titulo, slug, descripcion, beneficios, imagen, orden, activo) VALUES
('Transporte en gándola (mula)', 'transporte-gandola-mula',
'Cuando el volumen y el peso superan lo convencional, la gándola —o mula— es la respuesta para arrastrar volquetas, contenedores de 40 pies o unidades especiales con tracción controlada. Diseñamos la secuencia de enganche, puntos de apoyo y velocidad de avance según pendiente y radio de giro, alineados a su cronograma de obra o a su operación portuaria.',
'Estudio de ruta y simulación de maniobras en curvas críticas\nCoordinación de permisos y ventanas de circulación\nSupervisión en origen y destino con checklist de seguridad\nConductores con experiencia en operaciones de alto riesgo',
'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=85', 1, 1),
('Alquiler de modulares', 'alquiler-modulares',
'Habitabilidad y oficinas de obra listas para operar: dormitorios, comedores, salas HSE y puntos de mando en terreno. Entregamos unidades en buen estado, con logística de traslado, nivelación y conexiones básicas para que su equipo se concentre en producir, no en improvisar campamentos.',
'Flota revisada antes de cada salida a campo\nContratos por mes o por proyecto con renovación flexible\nOpciones de climatización, mobiliario y señalética corporativa\nDesmontaje y retiro coordinado al cierre del frente',
'/assets/img/servicio-modulares.png', 2, 1),
('Cama bajas y plataformas extendibles', 'cama-bajas',
'Maquinaria amarilla, piezas industriales y equipos con centro de gravedad exigente viajan con plataformas de altura reducida, anclajes certificados y, cuando el proyecto lo requiere, extensiones hidráulicas para longitudes mayores. Cada viaje incluye diagrama de carga, inspección de amarre y comunicación en tiempo real con su mesa de proyecto.',
'Selección de plataforma según peso, longitud y ancho útil\nAcompañamiento en gestión de rutas excepcionales\nCoordinación con grúas y talleres en maniobras de izaje\nSeguimiento GPS y reportes fotográficos en hitos clave',
'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=85', 3, 1),
('Movimiento de carga pesada especializada', 'carga-pesada-especializada',
'Transformadores, turbinas, prensas, hornos y estructuras metálicas de gran porte exigen más que un camión: exigen ingeniería de transporte, protocolos de seguridad y un plan B para cada variable climática o urbana. Integramos fabricante, cliente EPC y autoridades en un solo hilo de trabajo documentado.',
'Modelado de esfuerzos y distribución de peso en eje\nPlanes de contingencia y comunicación de crisis\nCuadrillas propias y aliadas certificadas en izaje pesado\nEntrega con actas técnicas y trazabilidad documental',
'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1600&q=85', 4, 1),
('Logística industrial y proyectos', 'logistica',
'Consolidación, cross-docking, distribución regional y última milla para plantas OEM, retail y cadena de frío. Aterrizamos acuerdos de ventana de entrega, tableros de KPI y reuniones de mejora continua para que la logística deje de ser un cuello de botella y pase a ser ventaja competitiva.',
'Diseño de rutas y frecuencias según demanda real\nIntegración con inventarios y avisos de llegada\nReducción de tiempos muertos en muelle y patio\nReporting ejecutivo mensual con indicadores acordados',
'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1600&q=85', 5, 1);

INSERT INTO publicaciones (titulo, slug, resumen, contenido, imagen_destacada, fecha_publicacion, estado, destacado) VALUES
('Convoy nocturno: tuneladora entre patios de obra en Bogotá', 'convoy-nocturno-tuneladora-bogota',
'Ventana de 4 horas, cierre parcial de carril y traslado de 180 t sin afectar el tránsito pico de la ciudad.',
'Un contratista de metro liviano nos encargó mover una tuneladora entre dos patios técnicos en la capital. El reto no era solo el peso: era el tiempo, la geometría urbana y la percepción de riesgo ante la ciudadanía.\n\nSuper Heavy Lift montó un convoy nocturno con cama baja multi-eje, escolta certificada y un plan de comunicaciones con Policía de Carreteras y locales de obra. Previo al rol, recorrimos virtualmente cada intersección, medimos radios y validamos alturas en pasos a nivel.\n\nResultado: la pieza ingresó al segundo patio a las 03:40, dentro de la ventana contractual, con cero incidentes y acta de conformidad firmada por el residente de obra y el cliente final.',
'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=85', '2026-04-02', 'publicado', 1),
('Cama extensible y escolta: tramo de aerogenerador en zona andina', 'cama-extensible-aerogenerador-andes',
'Componente de 32 m de longitud útil, pendientes superiores al 6 % y coordinación con comunidades de paso.',
'Para un desarrollador eólico en el altiplano, transportamos un tramo de pala y buje en secuencia, con plataforma extensible y juego de ballestas calculado para no exceder tensiones en el tablero.\n\nTrabajamos mano a mano con el fabricante del aerogenerador y con la Policía de Carreteras para definir puntos de detención, zonas de rebasamiento y mensajes VMS en corredor nacional.\n\nEl cliente destacó la puntualidad en el hito de montaje y la claridad de los informes diarios de avance, incluyendo fotografía de amarre en cada recarga de combustible.',
'https://images.unsplash.com/photo-1466618913921-943d3a89689d?auto=format&fit=crop&w=1600&q=85', '2026-03-22', 'publicado', 1),
('Gándola mula en corredor minero: cadena de volquetas 240 t', 'gandola-mula-corredor-minero-volquetas',
'Operación continua entre tajo abierto y planta de chancado con dos equipos mula y ventanas de carga sincronizadas.',
'En una mina de cobre en el norte del país, el cliente necesitaba reducir el ciclo de ida y vuelta de volquetas fuera de estándar. Super Heavy Lift desplegó dos gándolas con conductores rotativos y un despachador dedicado en radio.\n\nImplementamos un tablero compartido (Excel + WhatsApp operativo) con ETA por segmento y tiempos de espera en cola. En ocho semanas, el promedio de ciclos diarios subió un 14 % frente al arranque, sin incidentes de seguridad reportables.\n\nHoy el contrato se renovó por un segundo año con metas de disponibilidad de equipo y penalidades compartidas, señal de confianza mutua.',
'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=1600&q=85', '2026-03-08', 'publicado', 1),
('Campamento modular llave en mano para contrato LSTK en el Meta', 'campamento-modular-lstk-meta',
'22 módulos habitacionales, comedor de 80 cubiertos y oficina de HSE en 18 días desde orden de inicio.',
'El integrador LSTK requería alojar a 140 personas en un frente remoto con acceso restringido por vía sin pavimentar. Nosotros gestionamos transporte desde patio en Villavicencio, descarga con grúa telescópica aliada y nivelación con topografía simplificada.\n\nEntregamos módulos con divisiones internas según plano del cliente, iluminación LED y tableros eléctricos etiquetados. Se incluyó plan de mantenimiento preventivo quincenal y botiquín de repuestos menores.\n\nAl cierre del contrato, el desmontaje se completó en cuatro días y el 96 % de los módulos reingresaron a patio sin daños estructurales, listos para el siguiente arriendo.',
'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1600&q=85', '2026-02-24', 'publicado', 1),
('Cadena logística B2B para línea blanca: del puerto al punto de venta', 'logistica-linea-blanca-puerto-pdv',
'Cross-docking en patio propio aliado, rutas nocturnas a retail y reducción de quiebres de stock en tienda.',
'Un importador de electrodomésticos tenía presión por inventarios altos en puerto y faltantes en cadena nacional. Super Heavy Lift diseñó un esquema de consolidación nocturna en patio aliado en la costa, con despachos en ventanas matutinas a centros de distribución en tres ciudades.\n\nLos conductores utilizan sellos y checklist de daños visibles; el cliente recibe POD digital el mismo día. A los noventa días, los quiebres en tienda bajaron en dos dígitos y el inventario en puerto se estabilizó.\n\nEl modelo es escalable: estamos replicando la misma plantilla para importaciones de temporada alta.',
'https://images.unsplash.com/photo-1566576721346-d4a3b7e0a835?auto=format&fit=crop&w=1600&q=85', '2026-02-10', 'publicado', 0),
('Puente peatonal prefabricado: izaje en franja de 6 horas sobre autopista', 'puente-peatonal-prefabricado-izaje',
'Coordinación con ANI, concesión vial y cuerpo de bomberos para levantar 48 t sin cierre total de corredor.',
'La estructura debía atravesar un corredor concesionado con tráfico mixto pesado. Super Heavy Lift lideró la mesa de coordinación, definiendo un esquema de cierre parcial con desvíos y velocidad máxima en zona de obra.\n\nEl izaje se hizo con dos grúas todo terreno y supervisión de ingeniería de montaje del cliente EPC. Nuestro aporte fue el transporte desde patio de prefabricación hasta abscisa exacta, con sistema de giro controlado en el último tramo.\n\nLa franja se cumplió con 45 minutos de holgura y sin reclamos de la concesión por tiempos de cierre.',
'https://images.unsplash.com/photo-1545558014-8692077e9b5c?auto=format&fit=crop&w=1600&q=85', '2026-01-28', 'publicado', 0),
('Rutas excepcionales: una sola ventanilla frente a gremios y tránsito', 'rutas-excepcionales-ventanilla-unica',
'Menos idas y vueltas administrativas, más tiempo para ejecutar en campo: así ordenamos permisos multinivel.',
'Cuando un proyecto de carga sobredimensionada involucra tres departamentos y varios operadores de infraestructura, el riesgo es la dispersión de trámites. Centralizamos la documentación, homogenizamos formatos y asignamos un líder de permisos con autoridad para hablar en nombre del cliente.\n\nEn el último caso, acortamos en cinco semanas el calendario de autorizaciones para un convoy de transformador, alineando inspecciones en un solo viaje de comisión técnica.\n\nEl cliente dejó de dedicar dos ingenieros a tiempo completo al trámite y los reasignó a obra, con ahorro directo en costo fijo.',
'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1600&q=85', '2026-01-14', 'publicado', 0),
('Transformador y eje hidráulico: paso por túnel con ingeniería de perfil', 'transformador-eje-hidraulico-tunel',
'Levantamiento LIDAR, perfil digital del túnel y marcha a paso de hombre en tramo crítico de 800 m.',
'Un operador de red nos solicitó mover un transformador con carro hidráulico por un túnel de acceso a subestación. El margen de altura era de menos de 12 cm en el punto más bajo.\n\nLevantamos nube de puntos, contrastamos con el plano as-built y definimos velocidad máxima y asiento de ballestas. El convoy avanzó con intercomunicador permanente entre cabina y pie de formación.\n\nLa pieza salió del túnel en la madrugada acordada, con video de respaldo y acta firmada por el residente de interventoría.',
'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=1600&q=85', '2025-12-20', 'publicado', 0),
('Barcaza industrial: desde astillero hasta río Magdalena', 'barcaza-industrial-astillero-magdalena',
'Transporte terrestre de casco parcialmente armado con apoyo naval para recepción en muelle fluvial.',
'El astillero entregó el casco en un muelle privado; nuestro trabajo fue el tramo terrestre hasta el punto de botadura en el río, con plataforma modular y ballestas de refuerzo. Coordinamos con Marina Mercante ventana de maniobra y con remolcador fluvial para recepción.\n\nEl cliente valoró la integración con su equipo naval y la ausencia de daños en pintura anticorrosiva durante el traslado.\n\nEl proyecto quedó referenciado como caso de estudio interno en nuestra empresa para futuras piezas de gran eslora.',
'https://images.unsplash.com/photo-1494412574643-0c87602822d0?auto=format&fit=crop&w=1600&q=85', '2025-12-05', 'publicado', 0),
('Peak retail: 90 contenedores dry en 72 horas para temporada alta', 'peak-retail-noventa-contenedores-setenta-y-dos-horas',
'Operación puerto–CDU con despachadores dedicados y doble turno de conductores en corredor central.',
'Un retailer de moda necesitaba vaciar inventario de temporada en tres días para abrir tiendas en Black Friday. Montamos despacho 24/7 con dos líneas de conductores y patio de revisión express en destino.\n\nCada unidad salió con precinto y fotografía de puertas; el CDU recibió lotes agrupados por ciudad de destino final. Cumplimos 88 de 90 contenedores en ventana; los dos restantes quedaron por demora en aduana ajena a transporte.\n\nHoy ese cliente nos tiene en su lista corta para picos de importación en junio y noviembre.',
'https://images.unsplash.com/photo-1494412519320-aa613dfb7738?auto=format&fit=crop&w=1600&q=85', '2025-11-18', 'publicado', 0);

INSERT INTO cotizaciones (
  cliente_nombre, cliente_empresa, cliente_telefono, cliente_correo, tipo_servicio,
  origen, destino, tipo_carga, peso_estimado, dimensiones, fecha_requerida, observaciones,
  subtotal_sin_iva, otros_cargos, iva_pct, iva_monto, total, estado, created_at
) VALUES
('Carolina Méndez', 'Hidroandes S.A.S.', '+57 310 4448899', 'cmendez@hidroandes.co', 'Cama bajas',
'Bogotá D.C.', 'Medellín (La Estrella)', 'Excavadora hidráulica CAT 336', '36 t', '11,2 x 3,4 x 3,6 m', '2026-05-18',
'Ventana nocturna obligatoria. Póliza RC elevada y contacto de residente de obra adjunto en correo.',
18500000.00, 1200000.00, 19.00, 3743000.00, 23443000.00, 'en_revision', '2026-04-01 09:12:00'),
('Luis Ortega', 'Metalúrgica El Faro S.A.', '+57 320 7788120', 'lortega@elfaro.com', 'Movimiento de carga pesada especializada',
'Cartagena (Zona Franca)', 'Yumbo (Parque industrial)', 'Prensa hidráulica desarmada en 3 módulos', '42 t', 'Longitud 13 m en plataforma extendida', '2026-04-22',
'Taller del fabricante retira accesorios el 18/04; necesitamos ventana de salida el 22/04 al mediodía.',
28900000.00, 3500000.00, 19.00, 6156000.00, 38556000.00, 'pendiente', '2026-04-03 14:40:00'),
('Andrea Ruiz', 'Campamentos del Norte Ltda.', '+57 301 9900112', 'aruiz@campnorte.co', 'Alquiler de modulares',
'Mosquera (patio SHL)', 'Segovia, Antioquia (frente minero)', '12 módulos habitacionales + comedor 60 pax', 'N/A', 'Contenedores 40 ft habilitados', '2026-06-10',
'Entrega escalonada en dos viajes; requiere grúa 70 t en destino (cliente ya contrató).',
42000000.00, 8900000.00, 19.00, 9671000.00, 60571000.00, 'enviada', '2026-04-05 11:05:00'),
('Felipe Duarte', 'Red Logística Pacífico', '+57 315 2233445', 'fduarte@rlpacifico.com', 'Logística',
'Buenaventura (muelle 4)', 'Cali, Palmira y Buga', 'Contenedores reefer 40 pies (cadena de frío)', 'Mixto 18 t / contenedor', 'Estándar ISO', '2026-04-28',
'Ventanas de descarga 05:00–09:00. Coordinar con jefe de cadena de frío del CDU.',
128000000.00, 18500000.00, 19.00, 27835000.00, 174335000.00, 'aprobada', '2026-04-06 08:22:00'),
('María Prada', 'Ingeniería Horizonte S.A.S.', '+57 300 6677889', 'mprada@horizonte-ing.com', 'Transporte en gandola (mula)',
'Puerto Brisa', 'La Tebaida (patio temporal)', 'Contenedores dry 40 pies (lotes de 2)', 'Por unidad', 'ISO 40 ft', '2026-04-15',
'Cotización anual por volumen promedio 24 viajes/mes. Incluir opción de segundo equipo mula.',
985000000.00, 0.00, 19.00, 187150000.00, 1172150000.00, 'cerrada', '2026-04-07 16:50:00'),
('Jorge Salinas', 'EPC Andina', '+57 318 4455667', 'jsalinas@epcandina.com', 'Movimiento de carga pesada especializada',
'Ipiales', 'Subestación Pasto', 'Transformador 110 MVA — tramo urbano final', 'Sobredimensionado', 'Convoy Goldhofer + escolta', '2026-07-12',
'Cliente canceló por cambio de ruta del operador de red; agradecen propuesta para futura licitación.',
195000000.00, 42000000.00, 19.00, 45030000.00, 282030000.00, 'rechazada', '2026-04-08 10:18:00'),
('Patricia Gómez', 'Cemex Andina', '+57 311 2200198', 'pgomez@cemex.com.co', 'Cama bajas',
'Planta Nobsa', 'Obra Neiva', 'Molino de bolas (carcasa única)', '58 t', '14,2 x 3,8 x 4,1 m', '2026-05-05',
'Peajes: requiere acuerdo previo con concesiones. Coordinar con seguridad industrial de planta.',
45200000.00, 2800000.00, 19.00, 9120000.00, 57120000.00, 'en_revision', '2026-04-09 07:30:00'),
('Ricardo Vela', 'Vientos del Sur Eólica', '+57 304 8899011', 'rvela@vientosdelsur.co', 'Cama bajas',
'Puerto Barranquilla', 'Parque eólico La Guajira', 'Tramo de pala 52 m + buje (envío en secuencia)', 'Por tramo', 'Extensible + ballestas', '2026-06-20',
'Priorizar descarga en ventana de viento menor a 35 km/h según manual OEM.',
NULL, 0.00, 19.00, 0.00, NULL, 'pendiente', '2026-04-10 13:45:00'),
('Sandra Mejía', 'Química Orion', '+57 316 5544332', 'smejia@quimicaorion.com', 'Logística',
'Zona Franca Rionegro', 'Manizales y Pereira', 'Contenedores ISO tanque (ADR clase 8)', '22 t nominal', '20 pies', '2026-05-02',
'Conductores con curso MMTKA vigente. Adjuntamos fichas de seguridad en portal del cliente.',
67500000.00, 9500000.00, 19.00, 14630000.00, 91630000.00, 'enviada', '2026-04-11 09:00:00');

INSERT INTO contactos_recibidos (nombre, email, telefono, asunto, mensaje, leido, ip, created_at) VALUES
('Gustavo Peña', 'gpena@constructoranorte.com', '+57 310 1122334', 'Cotización cama baja', 'Buenos días, necesitamos mover una retro de 28 toneladas de Chía a Zipaquirá la próxima semana. ¿Pueden enviarnos tarifa y disponibilidad de equipo?', 0, NULL, '2026-04-10 11:20:00'),
('Lucía Fernández', 'lfernandez@minasdelvalle.co', '+57 322 9988776', 'Modulares obra', 'Estamos montando un campamento para 80 personas en Sogamoso. Necesitan visita técnica al sitio y propuesta con plazos de entrega.', 1, NULL, '2026-04-08 16:05:00'),
('Compras AllPetro', 'compras@allpetro.com', '+57 601 7778899', NULL, 'Solicitud formal de inclusión en banco de proveedores para transporte de carga pesada y logística. Adjuntamos RUT y certificaciones en el correo de respuesta.', 0, NULL, '2026-04-05 08:40:00');
