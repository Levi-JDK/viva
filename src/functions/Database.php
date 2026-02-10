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