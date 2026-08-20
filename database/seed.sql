USE SICAWN_BD;

-- =========================================
-- 1. USUARIOS
-- =========================================
INSERT INTO usuarios (nombre_completo, nombre_usuario, contrasena, telefono, rol) VALUES
('Jair Ismael Guerrero Luna', 'presidente1', '$2b$12$9kBBdwK/xDqzg9kTB.2Yjubx4djW8Jpn84Zrgi4lV99177LCzazCu', '2461111111', 'presidente'),
('Samuel Gómez Moreno', 'cobrador1', '$2b$12$9kBBdwK/xDqzg9kTB.2Yjubx4djW8Jpn84Zrgi4lV99177LCzazCu', '2462222222', 'cobrador'),
('Marco González Ahuactzin', 'marco_gonzalez', '$2b$12$9kBBdwK/xDqzg9kTB.2Yjubx4djW8Jpn84Zrgi4lV99177LCzazCu', '2463333333', NULL);  -- sin rol, para probar CU-01

-- =========================================
-- 2. CONTRIBUYENTES
-- =========================================
INSERT INTO contribuyentes (nombre_completo, telefono, calle, barrio, numero_exterior, numero_interior, fecha_alta, activo) VALUES
('Juan Pérez López', '2461234567', 'Juárez', 'Centro', '10', 'S/N', '2026-01-15', 1),
('María Ramos Hernández', '2461234568', 'Hidalgo', 'Centro', '5', NULL, '2026-01-16', 1),
('Pedro Sánchez Torres', '2461234569', 'Morelos', 'San Miguel', '22', 'A', '2026-01-20', 1),
('Rosa Martínez Cruz', '2461234570', 'Independencia', 'La Loma', '8', NULL, '2026-02-01', 1),
('Luis Torres Vázquez', '2461234571', 'Reforma', 'Centro', '15', NULL, '2026-02-05', 1),
('Ana Lucía Flores Ortega', '2461234572', 'Allende', 'San Miguel', '3', 'B', '2026-02-10', 1),
('Roberto Cid Meneses', '2461234573', '5 de Mayo', 'La Loma', '30', NULL, '2026-02-14', 1),
('Guadalupe Solís Ramírez', '2461234574', 'Zaragoza', 'Centro', '18', NULL, '2026-02-20', 1);

-- =========================================
-- 3. CONTRATOS
-- =========================================
INSERT INTO contratos (id_contribuyente, numero_contrato, fecha_inicio, estado_servicio, activo) VALUES
(1, 'C-0001', '2026-01-15', 'activo', 1),
(2, 'C-0002', '2026-01-16', 'activo', 1),
(3, 'C-0003', '2026-01-20', 'activo', 1),
(4, 'C-0004', '2026-02-01', 'activo', 1),
(5, 'C-0005', '2026-02-05', 'suspendido', 1),
(6, 'C-0006', '2026-02-10', 'activo', 1),
(7, 'C-0007', '2026-02-14', 'activo', 1),
(8, 'C-0008', '2026-02-20', 'activo', 1);

-- =========================================
-- 4. TOMAS DE AGUA
-- =========================================
INSERT INTO tomas_agua (id_contrato, calle, barrio, numero_exterior, numero_interior, tipo_toma, estado, fecha_registro) VALUES
(1, 'Juárez', 'Centro', '10', 'S/N', 'domestico', 'activa', '2026-01-15'),
(2, 'Hidalgo', 'Centro', '5', NULL, 'domestico', 'activa', '2026-01-16'),
(3, 'Morelos', 'San Miguel', '22', 'A', 'comercial', 'activa', '2026-01-20'),
(4, 'Independencia', 'La Loma', '8', NULL, 'domestico', 'activa', '2026-02-01'),
(5, 'Reforma', 'Centro', '15', NULL, 'domestico', 'activa', '2026-02-05'),
(6, 'Allende', 'San Miguel', '3', 'B', 'domestico', 'activa', '2026-02-10'),
(7, '5 de Mayo', 'La Loma', '30', NULL, 'comercial', 'activa', '2026-02-14'),
(8, 'Zaragoza', 'Centro', '18', NULL, 'domestico', 'activa', '2026-02-20');

-- =========================================
-- 5. TARIFAS (histórico, la más reciente por tipo es la vigente)
-- =========================================
INSERT INTO tarifas (tipo_contrato, monto_base, recargo_mensual, fecha_vigencia_desde, id_usuario) VALUES
('domestico', 60.00, 10.00, '2026-01-01', 1),
('comercial', 120.00, 20.00, '2026-01-01', 1);

-- =========================================
-- 6. TIPOS DE DESCUENTO
-- =========================================
INSERT INTO tipos_descuento (nombre, descripcion, tipo_valor, valor, activo, id_usuario) VALUES
('Descuento 3ra edad', 'Aplica a contribuyentes de la tercera edad', 'porcentaje', 20.00, 1, 1),
('Pronto pago', 'Incentivo por pagar antes de la fecha límite del periodo', 'monto_fijo', 10.00, 1, 1),
('Regularización', 'Incentivo para contribuyentes morosos que se ponen al corriente', 'porcentaje', 15.00, 1, 1);

-- =========================================
-- 7. ADEUDOS (varios periodos, algunos ya vencidos)
-- =========================================
INSERT INTO adeudos (id_toma, periodo, monto_base, recargo_acumulado, saldo_pendiente, estado) VALUES
-- Juan Pérez (toma 1) — al corriente hasta abril, debe mayo
(1, '2026-05-01', 60.00, 0.00, 60.00, 'pendiente'),
-- María Ramos (toma 2) — sin adeudos (no se inserta nada = al corriente)
-- Pedro Sánchez (toma 3, comercial) — debe abril y mayo
(3, '2026-04-01', 120.00, 20.00, 140.00, 'pendiente'),
(3, '2026-05-01', 120.00, 0.00, 120.00, 'pendiente'),
-- Rosa Martínez (toma 4) — adeudo con abono parcial ya aplicado (usada en CP-13)
(4, '2026-04-01', 60.00, 10.00, 0.00, 'pagado'),
(4, '2026-05-01', 60.00, 0.00, 40.00, 'parcial'),
-- Luis Torres (toma 5) — moroso 3+ meses (usado en CP-11, CP-29)
(5, '2026-03-01', 60.00, 30.00, 90.00, 'pendiente'),
(5, '2026-04-01', 60.00, 20.00, 80.00, 'pendiente'),
(5, '2026-05-01', 60.00, 10.00, 70.00, 'pendiente');

-- =========================================
-- 8. PAGOS
-- =========================================
INSERT INTO pagos (folio, id_contrato, id_cobrador, tipo_pago, monto_recibido, cambio, id_tipo_descuento, monto_descuento, fecha_pago) VALUES
('REC-2026-001', 4, 2, 'total', 70.00, 0.00, NULL, 0.00, '2026-04-28 10:15:00'),   -- Rosa liquidó abril
('REC-2026-002', 4, 2, 'parcial', 20.00, 0.00, NULL, 0.00, '2026-05-10 11:30:00'),  -- Rosa abonó parcial mayo
('REC-2026-003', 2, 2, 'total', 54.00, 0.00, 1, 6.00, '2026-05-12 09:00:00');       -- María pagó mayo con descuento 3ra edad

-- =========================================
-- 9. PAGO_DETALLE (qué adeudo cubrió cada pago)
-- =========================================
INSERT INTO pago_detalle (id_pago, id_adeudo, monto_aplicado) VALUES
(1, 4, 70.00),   -- pago 1 -> adeudo abril de Rosa (id_adeudo 4)
(2, 5, 20.00);   -- pago 2 -> abono parcial al adeudo mayo de Rosa (id_adeudo 5)

-- =========================================
-- 10. COMPROBANTES
-- =========================================
INSERT INTO comprobantes (id_pago, folio_comprobante, fecha_generacion, impreso) VALUES
(1, 'COMP-2026-001', '2026-04-28 10:15:30', 1),
(2, 'COMP-2026-002', '2026-05-10 11:30:20', 0),
(3, 'COMP-2026-003', '2026-05-12 09:00:15', 1);

-- =========================================
-- 11. BITÁCORA DE ESTADO DE SERVICIO
-- =========================================
INSERT INTO bitacora_estado_servicio (id_contrato, estado_anterior, estado_nuevo, motivo, id_usuario, fecha_cambio) VALUES
(5, 'activo', 'suspendido', 'Morosidad mayor a 3 meses', 2, '2026-05-15 12:00:00');

-- =========================================
-- 12. GASTOS
-- =========================================
INSERT INTO gastos (concepto, categoria, monto, fecha_gasto, id_usuario) VALUES
('Mantenimiento de bomba de pozo', 'Mantenimiento', 850.00, '2026-04-10', 1),
('Recibo de energía eléctrica CFE', 'Energía eléctrica', 620.00, '2026-04-30', 1),
('Compra de papel y tinta para recibos', 'Insumos de oficina', 180.00, '2026-05-05', 1);