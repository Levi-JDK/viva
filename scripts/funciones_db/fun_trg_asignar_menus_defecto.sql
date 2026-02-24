-- Trigger para auto-asignar menús de cliente por defecto al registrar un usuario
CREATE OR REPLACE FUNCTION fn_trg_asignar_menus_defecto()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO tab_menu_user (id_user, id_menu)
    VALUES 
        (NEW.id_user, 2), -- Vender en VIVA
        (NEW.id_user, 5), -- Inicio
        (NEW.id_user, 6), -- Categorías
        (NEW.id_user, 7), -- Catálogo
        (NEW.id_user, 8), -- Perfil
        (NEW.id_user, 9), -- Pedidos
        (NEW.id_user, 10), -- Favoritos
        (NEW.id_user, 11)  -- Configuración
    ON CONFLICT DO NOTHING;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_asignar_menus_defecto ON tab_users;
CREATE TRIGGER trg_asignar_menus_defecto
AFTER INSERT ON tab_users
FOR EACH ROW
EXECUTE FUNCTION fn_trg_asignar_menus_defecto();
