CREATE OR REPLACE FUNCTION fun_obtener_navegacion_usuario(p_id_user INTEGER)
RETURNS TABLE (
    id_menu INT,
    nom_menu VARCHAR,
    url_menu VARCHAR,
    icono_menu VARCHAR,
    orden_menu INT
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        m.id_menu,
        m.nom_menu,
        m.url_menu,
        m.icono_menu,
        m.orden_menu
    FROM 
        tab_menu_user mu
    INNER JOIN 
        tab_menu m ON mu.id_menu = m.id_menu
    WHERE 
        mu.id_user = p_id_user
        AND mu.is_deleted = FALSE 
        AND m.is_deleted = FALSE
    ORDER BY 
        m.orden_menu ASC;
END;
$$ LANGUAGE plpgsql;
