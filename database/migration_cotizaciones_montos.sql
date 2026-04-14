-- Agrega montos y totales a cotizaciones (bases ya creadas antes de este cambio).
-- Ejecutar una sola vez en phpMyAdmin o consola MySQL.

ALTER TABLE cotizaciones
  ADD COLUMN subtotal_sin_iva DECIMAL(14,2) DEFAULT NULL AFTER observaciones,
  ADD COLUMN otros_cargos DECIMAL(14,2) NOT NULL DEFAULT 0.00 AFTER subtotal_sin_iva,
  ADD COLUMN iva_pct DECIMAL(5,2) NOT NULL DEFAULT 19.00 AFTER otros_cargos,
  ADD COLUMN iva_monto DECIMAL(14,2) NOT NULL DEFAULT 0.00 AFTER iva_pct,
  ADD COLUMN total DECIMAL(14,2) DEFAULT NULL AFTER iva_monto;
