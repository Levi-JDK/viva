CREATE OR REPLACE FUNCTION fun_u_productor(
    p_id_productor        DECIMAL,
    p_tipo_doc_productor  DECIMAL,
    p_id_user             INTEGER,
    p_dir_prod            VARCHAR,
    p_id_pais             DECIMAL,
    p_id_ciudad           DECIMAL,
    p_id_departamento     DECIMAL,
    p_id_grupo            DECIMAL,
    p_id_banco            VARCHAR,
    p_id_cuenta_prod      DECIMAL,
    p_tipo_cuenta         DECIMAL
) RETURNS BOOLEAN AS $$
DECLARE
    w_id_productor  tab_productores.id_productor%TYPE;
    w_is_deleted    tab_productores.is_deleted%TYPE;
    w_id_productor2 tab_productores.id_productor%TYPE;
BEGIN
    -- 1) Validaciones básicas de nulidad
    IF p_id_productor IS NULL THEN RETURN FALSE; END IF;
    IF p_tipo_doc_productor IS NULL THEN RETURN FALSE; END IF;
    -- nom_prod y ape_prod ya no existen
    
    -- 2) Productor debe existir y estar ACTIVO
    SELECT pr.id_productor, pr.is_deleted
      INTO w_id_productor, w_is_deleted
      FROM tab_productores pr
     WHERE pr.id_productor = p_id_productor;
    
    IF NOT FOUND OR w_is_deleted THEN
        RETURN FALSE;
    END IF;

    -- 3) Usuario ACTIVO
    PERFORM 1 FROM tab_users u WHERE u.id_user = p_id_user AND u.is_deleted = FALSE;
    IF NOT FOUND THEN RETURN FALSE; END IF;

    -- 4) Evitar otro productor activo del mismo usuario
    SELECT pr.id_productor INTO w_id_productor2
      FROM tab_productores pr
     WHERE pr.id_user = p_id_user
       AND pr.is_deleted = FALSE
       AND pr.id_productor <> p_id_productor
     LIMIT 1;
    IF FOUND THEN
        RETURN FALSE; 
    END IF;

    -- 5) Validar FKs
    PERFORM 1 FROM tab_bancos b WHERE b.id_banco = p_id_banco AND b.is_deleted = FALSE;
    IF NOT FOUND THEN RETURN FALSE; END IF;

    PERFORM 1 FROM tab_grupos g WHERE g.id_grupo = p_id_grupo AND g.is_deleted = FALSE;
    IF NOT FOUND THEN RETURN FALSE; END IF;

    PERFORM 1 FROM tab_departamentos d WHERE d.id_departamento = p_id_departamento AND d.id_pais = p_id_pais AND d.is_deleted = FALSE;
    IF NOT FOUND THEN RETURN FALSE; END IF;

    PERFORM 1 FROM tab_ciudades c WHERE c.id_ciudad = p_id_ciudad AND c.id_pais = p_id_pais AND c.is_deleted = FALSE;
    IF NOT FOUND THEN RETURN FALSE; END IF;
    
    PERFORM 1 FROM tab_tipos_doc t WHERE t.id_tipo_doc = p_tipo_doc_productor AND t.is_deleted = FALSE;
    IF NOT FOUND THEN RETURN FALSE; END IF;

    -- 6) UPDATE
    UPDATE tab_productores
       SET tipo_doc_productor = p_tipo_doc_productor,
           id_user            = p_id_user,
           dir_prod           = p_dir_prod,
           id_pais            = p_id_pais,
           id_ciudad          = p_id_ciudad,
           id_departamento    = p_id_departamento,
           id_grupo           = p_id_grupo,
           id_banco           = p_id_banco,
           id_cuenta_prod     = p_id_cuenta_prod,
           tipo_cuenta        = p_tipo_cuenta
     WHERE id_productor = p_id_productor;

    RETURN TRUE;
END;
$$ LANGUAGE plpgsql;
