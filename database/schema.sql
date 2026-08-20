CREATE DATABASE IF NOT EXISTS sicawn_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sicawn_db;

-- 1. usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(10) NOT NULL UNIQUE,
    rol ENUM('presidente','cobrador') DEFAULT NULL,
    intentos_fallidos INT NOT NULL DEFAULT 0,
    bloqueado_hasta DATETIME NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. contribuyentes
CREATE TABLE contribuyentes (
    id_contribuyente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    calle VARCHAR(100),
    barrio VARCHAR(100),
    numero_exterior VARCHAR(20),
    numero_interior VARCHAR(20),
    fecha_alta DATE NOT NULL DEFAULT (CURRENT_DATE),
    activo TINYINT(1) NOT NULL DEFAULT 1
);

-- 3. contratos
CREATE TABLE contratos (
    id_contrato INT AUTO_INCREMENT PRIMARY KEY,
    id_contribuyente INT NOT NULL,
    numero_contrato VARCHAR(20) NOT NULL UNIQUE,
    fecha_inicio DATE NOT NULL,
    estado_servicio ENUM('activo','suspendido','cortado') NOT NULL DEFAULT 'activo',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_contratos_contribuyente FOREIGN KEY (id_contribuyente) REFERENCES contribuyentes(id_contribuyente)
);

-- 4. tomas_agua
CREATE TABLE tomas_agua (
    id_toma INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    calle VARCHAR(100),
    barrio VARCHAR(100),
    numero_exterior VARCHAR(20),
    numero_interior VARCHAR(20),
    tipo_toma ENUM('domestico','comercial') NOT NULL,
    estado ENUM('activa','inactiva') NOT NULL DEFAULT 'activa',
    fecha_registro DATE NOT NULL DEFAULT (CURRENT_DATE),
    CONSTRAINT fk_tomas_contrato FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato)
);

-- 5. tarifas
CREATE TABLE tarifas (
    id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
    tipo_contrato ENUM('domestico','comercial') NOT NULL,
    monto_base DECIMAL(10,2) NOT NULL,
    recargo_mensual DECIMAL(10,2) NOT NULL,
    fecha_vigencia_desde DATE NOT NULL,
    id_usuario INT NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tarifas_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- 6. tipos_descuento (NUEVA)
CREATE TABLE tipos_descuento (
    id_tipo_descuento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    tipo_valor ENUM('porcentaje','monto_fijo') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    id_usuario INT NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tipos_descuento_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- 7. adeudos
CREATE TABLE adeudos (
    id_adeudo INT AUTO_INCREMENT PRIMARY KEY,
    id_toma INT NOT NULL,
    periodo DATE NOT NULL,
    monto_base DECIMAL(10,2) NOT NULL,
    recargo_acumulado DECIMAL(10,2) NOT NULL DEFAULT 0,
    saldo_pendiente DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente','parcial','pagado') NOT NULL DEFAULT 'pendiente',
    CONSTRAINT fk_adeudos_toma FOREIGN KEY (id_toma) REFERENCES tomas_agua(id_toma),
    CONSTRAINT uq_adeudo_toma_periodo UNIQUE (id_toma, periodo)
);

-- 8. pagos (actualizada con descuento)
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    folio VARCHAR(30) NOT NULL UNIQUE,
    id_contrato INT NOT NULL,
    id_cobrador INT NOT NULL,
    tipo_pago ENUM('total','parcial') NOT NULL,
    monto_recibido DECIMAL(10,2) NOT NULL,
    cambio DECIMAL(10,2) NOT NULL DEFAULT 0,
    id_tipo_descuento INT NULL,
    monto_descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
    fecha_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pagos_contrato FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato),
    CONSTRAINT fk_pagos_cobrador FOREIGN KEY (id_cobrador) REFERENCES usuarios(id_usuario),
    CONSTRAINT fk_pagos_descuento FOREIGN KEY (id_tipo_descuento) REFERENCES tipos_descuento(id_tipo_descuento)
);

-- 9. pago_detalle
CREATE TABLE pago_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    id_adeudo INT NOT NULL,
    monto_aplicado DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_pago FOREIGN KEY (id_pago) REFERENCES pagos(id_pago),
    CONSTRAINT fk_detalle_adeudo FOREIGN KEY (id_adeudo) REFERENCES adeudos(id_adeudo)
);

-- 10. comprobantes
CREATE TABLE comprobantes (
    id_comprobante INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL UNIQUE,
    folio_comprobante VARCHAR(30) NOT NULL UNIQUE,
    fecha_generacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    impreso TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT fk_comprobantes_pago FOREIGN KEY (id_pago) REFERENCES pagos(id_pago)
);

-- 11. bitacora_estado_servicio
CREATE TABLE bitacora_estado_servicio (
    id_bitacora INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    estado_anterior ENUM('activo','suspendido','cortado') NOT NULL,
    estado_nuevo ENUM('activo','suspendido','cortado') NOT NULL,
    motivo TEXT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_cambio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bitacora_contrato FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato),
    CONSTRAINT fk_bitacora_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- 12. gastos (NUEVA — egresos)
CREATE TABLE gastos (
    id_gasto INT AUTO_INCREMENT PRIMARY KEY,
    concepto VARCHAR(150) NOT NULL,
    categoria VARCHAR(100),
    monto DECIMAL(10,2) NOT NULL,
    fecha_gasto DATE NOT NULL,
    id_usuario INT NOT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_gastos_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);