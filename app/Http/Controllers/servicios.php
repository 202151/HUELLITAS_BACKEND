<?php
require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Configuración de CORS
$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Middleware para manejar OPTIONS
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

// Middleware para parsear JSON
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'tu_password');
define('DB_NAME', 'veterinaria');

// Función para obtener conexión a la base de datos
function getDbConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch (PDOException $e) {
        throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
    }
}

// Función para validar datos de servicio
function validarServicio($data, $esActualizacion = false) {
    $errores = [];
    
    if (empty($data['nombre_servicio']) || strlen($data['nombre_servicio']) > 100) {
        $errores[] = "El nombre del servicio es requerido y debe tener máximo 100 caracteres";
    }
    
    if (!isset($data['precio']) || $data['precio'] <= 0) {
        $errores[] = "El precio es requerido y debe ser mayor a 0";
    }
    
    if (!in_array($data['categoria'], ['consulta', 'vacuna', 'baño', 'grooming'])) {
        $errores[] = "La categoría debe ser: consulta, vacuna, baño o grooming";
    }
    
    if (isset($data['duracion_estimada']) && $data['duracion_estimada'] <= 0) {
        $errores[] = "La duración estimada debe ser mayor a 0";
    }
    
    return $errores;
}

// Ruta raíz
$app->get('/', function (Request $request, Response $response) {
    $data = ['message' => 'API Servicios Veterinaria'];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// GET - Listar todos los servicios
$app->get('/api/servicios', function (Request $request, Response $response) {
    try {
        $db = getDbConnection();
        $stmt = $db->query("
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            ORDER BY creado_en DESC
        ");
        $servicios = $stmt->fetchAll();
        
        $response->getBody()->write(json_encode($servicios));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (Exception $e) {
        $error = ['error' => 'Error al obtener servicios: ' . $e->getMessage()];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// GET - Obtener un servicio por ID
$app->get('/api/servicios/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = $args['id'];
        $db = getDbConnection();
        
        $stmt = $db->prepare("
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            WHERE id_servicios = ?
        ");
        $stmt->execute([$id]);
        $servicio = $stmt->fetch();
        
        if (!$servicio) {
            $error = ['error' => 'Servicio no encontrado'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        
        $response->getBody()->write(json_encode($servicio));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (Exception $e) {
        $error = ['error' => 'Error al obtener servicio: ' . $e->getMessage()];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// POST - Crear un nuevo servicio
$app->post('/api/servicios', function (Request $request, Response $response) {
    try {
        $data = $request->getParsedBody();
        
        // Validar datos
        $errores = validarServicio($data);
        if (!empty($errores)) {
            $error = ['error' => 'Datos inválidos', 'detalles' => $errores];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $db = getDbConnection();
        
        $stmt = $db->prepare("
            INSERT INTO servicios (nombre_servicio, descripcion, precio, 
                                 duracion_estimada, categoria, activo)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $activo = isset($data['activo']) ? (bool)$data['activo'] : true;
        
        $stmt->execute([
            $data['nombre_servicio'],
            $data['descripcion'] ?? null,
            $data['precio'],
            $data['duracion_estimada'] ?? null,
            $data['categoria'],
            $activo
        ]);
        
        // Obtener el servicio recién creado
        $id_nuevo = $db->lastInsertId();
        $stmt = $db->prepare("
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            WHERE id_servicios = ?
        ");
        $stmt->execute([$id_nuevo]);
        $nuevo_servicio = $stmt->fetch();
        
        $response->getBody()->write(json_encode($nuevo_servicio));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } catch (Exception $e) {
        $error = ['error' => 'Error al crear servicio: ' . $e->getMessage()];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// PUT - Actualizar un servicio
$app->put('/api/servicios/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = $args['id'];
        $data = $request->getParsedBody();
        
        // Validar datos
        $errores = validarServicio($data, true);
        if (!empty($errores)) {
            $error = ['error' => 'Datos inválidos', 'detalles' => $errores];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        $db = getDbConnection();
        
        // Verificar si existe
        $stmt = $db->prepare("SELECT id_servicios FROM servicios WHERE id_servicios = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            $error = ['error' => 'Servicio no encontrado'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        
        // Actualizar
        $stmt = $db->prepare("
            UPDATE servicios
            SET nombre_servicio = ?,
                descripcion = ?,
                precio = ?,
                duracion_estimada = ?,
                categoria = ?,
                activo = ?
            WHERE id_servicios = ?
        ");
        
        $activo = isset($data['activo']) ? (bool)$data['activo'] : true;
        
        $stmt->execute([
            $data['nombre_servicio'],
            $data['descripcion'] ?? null,
            $data['precio'],
            $data['duracion_estimada'] ?? null,
            $data['categoria'],
            $activo,
            $id
        ]);
        
        // Obtener el servicio actualizado
        $stmt = $db->prepare("
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            WHERE id_servicios = ?
        ");
        $stmt->execute([$id]);
        $servicio_actualizado = $stmt->fetch();
        
        $response->getBody()->write(json_encode($servicio_actualizado));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (Exception $e) {
        $error = ['error' => 'Error al actualizar servicio: ' . $e->getMessage()];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// DELETE - Eliminar un servicio
$app->delete('/api/servicios/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = $args['id'];
        $db = getDbConnection();
        
        // Verificar si existe
        $stmt = $db->prepare("SELECT id_servicios FROM servicios WHERE id_servicios = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            $error = ['error' => 'Servicio no encontrado'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        
        // Eliminar
        $stmt = $db->prepare("DELETE FROM servicios WHERE id_servicios = ?");
        $stmt->execute([$id]);
        
        $data = ['message' => 'Servicio eliminado correctamente'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (Exception $e) {
        $error = ['error' => 'Error al eliminar servicio: ' . $e->getMessage()];
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->run();