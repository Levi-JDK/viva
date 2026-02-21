-- =============================================================================
-- alter_checkout.sql
-- Aplica los cambios del módulo de checkout sobre la base de datos existente.
-- Seguro de ejecutar en producción: usa IF NOT EXISTS en todo.
--
-- ORDEN DE EJECUCIÓN:
--   1. Crear tab_clientes (depende de tab_users y tab_ciudades)
--   2. Ampliar tab_enc_fact con columnas de dirección y ePayco
-- =============================================================================


-- -----------------------------------------------------------------------------
-- 1. TABLA DE CLIENTES
--    Dos identificadores separados:
--      · id_user   → usuario registrado en la plataforma (FK a tab_users)
--      · id_client → número de documento de identidad del comprador (PK)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tab_clientes (
    -- Vínculo con el usuario de la plataforma
    id_user         INTEGER       NOT NULL,      -- FK → tab_users.id_user

    -- Documento de identidad del cliente (PK)
    id_client       VARCHAR(20)   NOT NULL,      -- Número de documento (CC, NIT, pasaporte, etc.)

    -- Datos de contacto
    nom_client      VARCHAR       NOT NULL,      -- Nombre completo
    mail_client     VARCHAR       NOT NULL,      -- Email
    tel_client      VARCHAR(20),                 -- Teléfono (llega de ePayco tras el pago)

    -- Tipo de documento (llega de ePayco)
    id_tipo_doc     DECIMAL(1,0),                -- FK nullable → tab_tipos_doc

    -- Dirección de envío (capturada en el formulario del checkout)
    id_pais         DECIMAL(3,0)  NOT NULL DEFAULT 1,
    id_departamento DECIMAL(2,0)  NOT NULL,
    id_ciudad       DECIMAL(5,0)  NOT NULL,
    dir_envio       VARCHAR       NOT NULL,      -- Ej: "Calle 10 # 5-20 Apto 301"
    barrio_envio    VARCHAR,                     -- Barrio (opcional)

    -- Datos del último pago ePayco (se sobreescriben en cada compra)
    epayco_ref      VARCHAR,                     -- x_ref_payco
    epayco_txn_id   VARCHAR,                     -- x_transaction_id
    epayco_banco    VARCHAR,                     -- x_bank_name
    epayco_cod_resp DECIMAL(1,0),                -- 1=Aceptada | 2=Rechazada | 3=Pendiente | 4=Fallida

    -- Auditoría
    created_at  TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP WITHOUT TIME ZONE,
    is_deleted  BOOLEAN NOT NULL DEFAULT FALSE,

    -- Restricciones
    PRIMARY KEY (id_client),                                          -- PK: documento del cliente
    FOREIGN KEY (id_user)         REFERENCES tab_users(id_user),      -- FK: usuario de la plataforma
    FOREIGN KEY (id_pais, id_departamento, id_ciudad)
                                  REFERENCES tab_ciudades(id_pais, id_departamento, id_ciudad),
    FOREIGN KEY (id_tipo_doc)     REFERENCES tab_tipos_doc(id_tipo_doc)
);

-- Índices de apoyo
CREATE INDEX IF NOT EXISTS idx_clientes_user   ON tab_clientes(id_user);
CREATE INDEX IF NOT EXISTS idx_clientes_mail   ON tab_clientes(mail_client);
CREATE INDEX IF NOT EXISTS idx_clientes_ciudad ON tab_clientes(id_pais, id_ciudad);


-- -----------------------------------------------------------------------------
-- 2. AMPLIAR tab_enc_fact
--    ADD COLUMN IF NOT EXISTS es idempotente: no falla si ya existen.
-- -----------------------------------------------------------------------------
ALTER TABLE tab_enc_fact
    ADD COLUMN IF NOT EXISTS dir_envio       VARCHAR,     -- Dirección de envío (texto libre)
    ADD COLUMN IF NOT EXISTS epayco_ref      VARCHAR,     -- x_ref_payco
    ADD COLUMN IF NOT EXISTS epayco_txn_id   VARCHAR,     -- x_transaction_id
    ADD COLUMN IF NOT EXISTS epayco_estado   VARCHAR;     -- "Aceptada" / "Rechazada" / "Pendiente"
