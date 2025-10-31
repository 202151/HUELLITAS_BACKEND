<?php
/**
 * Modelo Servicio
 * Maneja todas las operaciones relacionadas con los servicios veterinarios
 */
class Servicio {
    private $conn;
    private $table = 'servicios';

    // Propiedades del servicio
    public $id_servicios;
    public $nombre_servicio;
    public $descripcion;
    public $precio;
    public $duracion_estimada;
    public $categoria;
    public $activo;
    public $creado_en;

    /**
     * Constructor
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener todos los servicios
     * @param bool $soloActivos Si es true, solo devuelve servicios activos
     * @return array Array de servicios
     */
    public function obtenerTodos($soloActivos = false) {
        $query = "SELECT id_servicios, nombre_servicio, descripcion, precio, 
                         duracion_estimada, categoria, activo, creado_en
                  FROM " . $this->table;
        
        if ($soloActivos) {
            $query .= " WHERE activo = 1";
        }
        
        $query .= " ORDER BY creado_en DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener un servicio por ID
     * @param int $id ID del servicio
     * @return array|false Datos del servicio o false si no existe
     */
    public function obtenerPorId($id) {
        $query = "SELECT id_servicios, nombre_servicio, descripcion, precio, 
                         duracion_estimada, categoria, activo, creado_en
                  FROM " . $this->table . "
                  WHERE id_servicios = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener servicios por categoría
     * @param string $categoria Categoría del servicio
     * @return array Array de servicios
     */
    public function obtenerPorCategoria($categoria) {
        $query = "SELECT id_servicios, nombre_servicio, descripcion, precio, 
                         duracion_estimada, categoria, activo, creado_en
                  FROM " . $this->table . "
                  WHERE categoria = :categoria AND activo = 1
                  ORDER BY nombre_servicio ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar servicios por nombre
     * @param string $termino Término de búsqueda
     * @return array Array de servicios
     */
    public function buscar($termino) {
        $query = "SELECT id_servicios, nombre_servicio, descripcion, precio, 
                         duracion_estimada, categoria, activo, creado_en
                  FROM " . $this->table . "
                  WHERE nombre_servicio LIKE :termino 
                     OR descripcion LIKE :termino
                  ORDER BY nombre_servicio ASC";

        $stmt = $this->conn->prepare($query);
        $terminoBusqueda = '%' . $termino . '%';
        $stmt->bindParam(':termino', $terminoBusqueda);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crear un nuevo servicio
     * @return int|false ID del servicio creado o false si falla
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table . "
                  (nombre_servicio, descripcion, precio, duracion_estimada, categoria, activo)
                  VALUES (:nombre_servicio, :descripcion, :precio, :duracion_estimada, :categoria, :activo)";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_servicio = htmlspecialchars(strip_tags($this->nombre_servicio));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = floatval($this->precio);
        $this->duracion_estimada = $this->duracion_estimada ? intval($this->duracion_estimada) : null;
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->activo = $this->activo ? 1 : 0;

        // Bind de parámetros
        $stmt->bindParam(':nombre_servicio', $this->nombre_servicio);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':duracion_estimada', $this->duracion_estimada, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':activo', $this->activo, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar un servicio existente
     * @return bool True si se actualizó correctamente, false si falla
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table . "
                  SET nombre_servicio = :nombre_servicio,
                      descripcion = :descripcion,
                      precio = :precio,
                      duracion_estimada = :duracion_estimada,
                      categoria = :categoria,
                      activo = :activo
                  WHERE id_servicios = :id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_servicio = htmlspecialchars(strip_tags($this->nombre_servicio));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = floatval($this->precio);
        $this->duracion_estimada = $this->duracion_estimada ? intval($this->duracion_estimada) : null;
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->activo = $this->activo ? 1 : 0;
        $this->id_servicios = intval($this->id_servicios);

        // Bind de parámetros
        $stmt->bindParam(':nombre_servicio', $this->nombre_servicio);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':duracion_estimada', $this->duracion_estimada, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':activo', $this->activo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id_servicios, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Eliminar un servicio
     * @return bool True si se eliminó correctamente, false si falla
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id_servicios = :id";

        $stmt = $this->conn->prepare($query);
        $this->id_servicios = intval($this->id_servicios);
        $stmt->bindParam(':id', $this->id_servicios, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Desactivar un servicio (soft delete)
     * @return bool True si se desactivó correctamente, false si falla
     */
    public function desactivar() {
        $query = "UPDATE " . $this->table . " 
                  SET activo = 0 
                  WHERE id_servicios = :id";

        $stmt = $this->conn->prepare($query);
        $this->id_servicios = intval($this->id_servicios);
        $stmt->bindParam(':id', $this->id_servicios, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Activar un servicio
     * @return bool True si se activó correctamente, false si falla
     */
    public function activar() {
        $query = "UPDATE " . $this->table . " 
                  SET activo = 1 
                  WHERE id_servicios = :id";

        $stmt = $this->conn->prepare($query);
        $this->id_servicios = intval($this->id_servicios);
        $stmt->bindParam(':id', $this->id_servicios, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Verificar si existe un servicio por ID
     * @param int $id ID del servicio
     * @return bool True si existe, false si no
     */
    public function existe($id) {
        $query = "SELECT id_servicios FROM " . $this->table . " WHERE id_servicios = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    /**
     * Contar servicios por categoría
     * @return array Array asociativo con el conteo por categoría
     */
    public function contarPorCategoria() {
        $query = "SELECT categoria, COUNT(*) as total
                  FROM " . $this->table . "
                  WHERE activo = 1
                  GROUP BY categoria";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener servicios más caros
     * @param int $limite Número de servicios a retornar
     * @return array Array de servicios
     */
    public function obtenerMasCaros($limite = 5) {
        $query = "SELECT id_servicios, nombre_servicio, descripcion, precio, 
                         duracion_estimada, categoria, activo, creado_en
                  FROM " . $this->table . "
                  WHERE activo = 1
                  ORDER BY precio DESC
                  LIMIT :limite";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Validar datos del servicio
     * @return array Array de errores (vacío si no hay errores)
     */
    public function validar() {
        $errores = [];

        // Validar nombre
        if (empty($this->nombre_servicio) || strlen($this->nombre_servicio) > 100) {
            $errores[] = "El nombre del servicio es requerido y debe tener máximo 100 caracteres";
        }

        // Validar precio
        if (!isset($this->precio) || $this->precio <= 0) {
            $errores[] = "El precio es requerido y debe ser mayor a 0";
        }

        // Validar categoría
        $categorias_validas = ['consulta', 'vacuna', 'baño', 'grooming'];
        if (!in_array($this->categoria, $categorias_validas)) {
            $errores[] = "La categoría debe ser: consulta, vacuna, baño o grooming";
        }

        // Validar duración
        if (isset($this->duracion_estimada) && $this->duracion_estimada !== null && $this->duracion_estimada <= 0) {
            $errores[] = "La duración estimada debe ser mayor a 0";
        }

        return $errores;
    }

    /**
     * Obtener estadísticas generales
     * @return array Array con estadísticas
     */
    public function obtenerEstadisticas() {
        $query = "SELECT 
                    COUNT(*) as total_servicios,
                    COUNT(CASE WHEN activo = 1 THEN 1 END) as servicios_activos,
                    AVG(precio) as precio_promedio,
                    MIN(precio) as precio_minimo,
                    MAX(precio) as precio_maximo,
                    AVG(duracion_estimada) as duracion_promedio
                  FROM " . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}