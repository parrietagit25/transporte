-- Corrige URLs de imágenes que ya no existen en Unsplash (404) o duplican el hero.
-- Ejecutar en phpMyAdmin sobre la base ya importada:

UPDATE servicios SET imagen = 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=85'
WHERE slug = 'cama-bajas';

UPDATE servicios SET imagen = 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1600&q=85'
WHERE slug = 'logistica';

-- Imagen de modulares (copia local recomendada; el archivo debe existir en public/assets/img/)
UPDATE servicios SET imagen = '/assets/img/servicio-modulares.png'
WHERE slug = 'alquiler-modulares';
