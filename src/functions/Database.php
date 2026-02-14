<?php
require dirname(__DIR__, 2) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

class Database {
    private static $instance = null;
    public $connection;
    private $statements = []; 

    private function __construct() {
        $config = [
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'dbname' => $_ENV['DB_NAME']
        ];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $dsn = 'pgsql:' . http_build_query($config, '', ';');
        
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
        ]);
        
        $this->prepararConsultas();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self(); 
        }
        return self::$instance;
    }

    private function prepararConsultas() {
        $this->statements['validarEmail'] = $this->connection->prepare("SELECT fun_val_mail(:email)");
        $this->statements['crearUsuario'] = $this->connection->prepare("SELECT fun_c_user(:email, :contrasena, :nombre, :apellido)");
        $this->statements['obtenerHashLogin'] = $this->connection->prepare("SELECT fun_val_log(:email)");
        $this->statements['obtenerUsuarioPorEmail'] = $this->connection->prepare("SELECT id_user, nom_user FROM tab_users WHERE mail_user = :email");
        $this->statements['obtenerUsuarioPorId'] = $this->connection->prepare("SELECT nom_user, ape_user, mail_user, foto_user, created_at FROM tab_users WHERE id_user = :id");
        $this->statements['actualizarPerfil'] = $this->connection->prepare("UPDATE tab_users SET nom_user = :nombre, ape_user = :apellido WHERE id_user = :id");
        $this->statements['obtenerIdPorEmail'] = $this->connection->prepare("SELECT id_user FROM tab_users WHERE mail_user = :email");
        $this->statements['actualizarFotoUsuario'] = $this->connection->prepare("SELECT fun_u_foto_user(:id, :foto) as resultado");
        $this->statements['obtenerTiposDocumento'] = $this->connection->prepare("SELECT id, nombre FROM tipos_col_view");
        $this->statements['obtenerDepartamentos'] = $this->connection->prepare("SELECT id, nombre FROM departamentos_col_view");
        $this->statements['obtenerCiudades'] = $this->connection->prepare("SELECT id, nombre FROM  obtener_ciudades(:id_depto) ORDER BY nombre ASC;");
        $this->statements['obtenerGrupos'] = $this->connection->prepare("SELECT id, nombre FROM grupos_view");
        $this->statements['obtenerBancos'] = $this->connection->prepare("SELECT id, nombre FROM bancos_view");
        $this->statements['crearProductor'] = $this->connection->prepare(
            "SELECT fun_c_productor(
                :tipo_doc, :id_prod, :id_user,
                :dir, :pais, :dpto, :ciudad, :grupo,
                :banco, :cuenta, :tipo_cuenta
            )"
        );
        $this->statements['validarProductor'] = $this->connection->prepare("SELECT fun_val_productor(:id_user)");
        
        // Consultas para el formulario de productos
        $this->statements['obtenerCategorias'] = $this->connection->prepare("SELECT * FROM categorias_view");
        $this->statements['obtenerColores'] = $this->connection->prepare("SELECT * FROM colores_view");
        $this->statements['obtenerOficios'] = $this->connection->prepare("SELECT * FROM oficios_view");
        $this->statements['obtenerMaterias'] = $this->connection->prepare("SELECT * FROM materias_view");

        // Consultas para registrar productos
        $this->statements['obtenerIdProductor'] = $this->connection->prepare("SELECT id_productor FROM tab_productores WHERE id_user = :id_user");
        $this->statements['registrarProducto'] = $this->connection->prepare("
            INSERT INTO tab_productos (
                id_productor, id_producto, nom_producto, stock_productor, 
                id_categoria, id_color, id_oficio, id_materia, 
                precio_producto, descripcion_producto
            ) VALUES (
                :id_productor, :id_producto, :nom_producto, :stock_productor, 
                :id_categoria, :id_color, :id_oficio, :id_materia, 
                :precio_producto, :descripcion_producto
            )
        ");
        $this->statements['registrarProductoProductor'] = $this->connection->prepare("
            INSERT INTO tab_producto_productor (
                id_producto, id_productor, precio_prod, stock_prod, 
                desc_prod_personal, img_personal, activo
            ) VALUES (
                :id_producto, :id_productor, :precio_prod, :stock_prod, 
                :desc_prod_personal, :img_personal, :activo
            )
        ");
        $this->statements['registrarImagen'] = $this->connection->prepare("
            INSERT INTO tab_imagenes (id_producto, id_imagen, url_imagen) 
            VALUES (:id_producto, :id_imagen, :url_imagen)
        ");

        // Consultas para el catálogo
        $this->statements['buscarProductos'] = $this->connection->prepare("
            SELECT 
                p.id_producto, 
                p.nom_producto, 
                p.precio_producto, 
                p.stock_productor,
                c.nom_categoria,
                COALESCE((SELECT img_personal FROM tab_producto_productor pp WHERE pp.id_producto = p.id_producto LIMIT 1), 'images/default_product.png') as imagen
            FROM tab_productos p
            JOIN tab_categorias c ON p.id_categoria = c.id_categoria
            WHERE 
                (p.nom_producto ILIKE '%' || :search || '%' OR :search IS NULL)
                AND (p.id_categoria = :categoria OR :categoria IS NULL)
                AND (p.precio_producto >= :min_precio OR :min_precio IS NULL)
                AND (p.precio_producto <= :max_precio OR :max_precio IS NULL)
            ORDER BY p.nom_producto ASC
                AND (p.precio_producto <= :max_precio OR :max_precio IS NULL)
            ORDER BY p.nom_producto ASC
        ");

        $this->statements['obtenerProductosPorProductor'] = $this->connection->prepare("
            SELECT 
                p.id_producto, p.nom_producto, p.precio_producto, p.stock_productor, 
                c.nom_categoria,
                pp.activo,
                (SELECT url_imagen FROM tab_imagenes i WHERE i.id_producto = p.id_producto LIMIT 1) as imagen
            FROM tab_productos p
            JOIN tab_producto_productor pp ON p.id_producto = pp.id_producto
            JOIN tab_categorias c ON p.id_categoria = c.id_categoria
            WHERE pp.id_productor = :id_productor
            ORDER BY p.created_at DESC
        ");

        $this->statements['obtenerConteoCategorias'] = $this->connection->prepare("
            SELECT c.id_categoria, c.nom_categoria, COUNT(p.id_producto) as total
            FROM tab_categorias c
            LEFT JOIN tab_productos p ON c.id_categoria = p.id_categoria
            GROUP BY c.id_categoria, c.nom_categoria
            ORDER BY c.nom_categoria ASC
        ");
    }

    public function ejecutar($nombre, $params = []) {
        if (!isset($this->statements[$nombre])) {
            throw new Exception("Consulta preparada '$nombre' no encontrada.");
        }
        $stmt = $this->statements[$nombre];
        
        // Bind dinámico respetando tipos
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_NULL);
            } else {
                // Strings o cualquier otra cosa se pasa como string
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    private function __clone() {}
}