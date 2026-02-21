-- =============================================================================
-- tab_kardex.sql  — Registro de movimientos de inventario
-- DROP + CREATE para aplicar sobre una tabla existente (ejecutar como superuser)
-- =============================================================================

DROP TABLE IF EXISTS tab_kardex CASCADE;

CREATE TABLE tab_kardex (
    id_kardex      SERIAL          NOT NULL,
    id_producto    DECIMAL(7,0)    NOT NULL,
    id_productor   DECIMAL(10,0)   NOT NULL,  -- mismo tipo que tab_det_fact.id_productor
    tipo_movim     BOOLEAN         NOT NULL,   -- TRUE=entrada, FALSE=salida (venta)
    cantidad       DECIMAL(7,0)    NOT NULL CHECK (cantidad > 0),
    ref_factura    DECIMAL(7,0),               -- FK de trazabilidad → tab_enc_fact
    created_at     TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_deleted     BOOLEAN         NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id_kardex),
    FOREIGN KEY (id_producto)  REFERENCES tab_productos  (id_producto),
    FOREIGN KEY (id_productor) REFERENCES tab_productores(id_productor),
    FOREIGN KEY (ref_factura)  REFERENCES tab_enc_fact   (id_factura)
);

-- Índice para consultas por producto/productor
CREATE INDEX idx_kardex_prod ON tab_kardex (id_producto, id_productor);

-- =============================================================================
-- Trigger: actualiza stock_productor en tab_productos al insertar en kardex
-- =============================================================================
CREATE OR REPLACE FUNCTION fun_trg_kardex_stock()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.tipo_movim = TRUE THEN
        -- Entrada: sumar al stock
        UPDATE tab_productos
           SET stock_productor = stock_productor + NEW.cantidad
         WHERE id_producto = NEW.id_producto
           AND id_productor = NEW.id_productor
           AND is_deleted = FALSE;
    ELSE
        -- Salida (venta): restar del stock
        UPDATE tab_productos
           SET stock_productor = stock_productor - NEW.cantidad
         WHERE id_producto = NEW.id_producto
           AND id_productor = NEW.id_productor
           AND is_deleted = FALSE;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_kardex_actualizar_stock ON tab_kardex;

CREATE TRIGGER trg_kardex_actualizar_stock
AFTER INSERT ON tab_kardex
FOR EACH ROW
EXECUTE FUNCTION fun_trg_kardex_stock();
