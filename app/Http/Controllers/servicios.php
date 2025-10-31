<?php
require 'vendor/autoload.php';
require_once 'models/Servicio.php';

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

// Función helper para respuestas JSON
function jsonResponse(Response $response, $data, $status = 200) {
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus($status);
}

// ==================== RUTAS ====================

// Ruta raíz
$app->get('/', function (Request $request, Response $response) {
    return jsonResponse($response, [
        'message' => 'API Servicios Veterinaria',
        'version' => '1.0',
        'endpoints' => [
            'GET /api/servicios' => 'Listar todos los servicios',
            'GET /api/servicios/{id}' => 'Obtener un servicio',
            'POST /api/servicios' => 'Crear un servicio',
            'PUT /api/servicios/{id}' => 'Actualizar un servicio',
            'DELETE /api/servicios/{id}' => 'Eliminar un servicio',
            'GET /api/servicios/categoria/{categoria}' => 'Servicios por categoría',
            'GET /api/servicios/estadisticas' => 'Estadísticas generales'
        ]
    ]);
});

// GET - Listar todos los servicios
$app->get('/api/servicios', function (Request $request, Response $response) {
    try {
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        // Obtener parámetros query opcional
        $params = $request->getQueryParams();
        $soloActivos = isset($params['activos']) && $params['activos'] === 'true';
        
        $servicios = $servicio->obtenerTodos($soloActivos);
        return jsonResponse($response, $servicios);
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al obtener servicios',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// GET - Obtener un servicio por ID
$app->get('/api/servicios/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = (int)$args['id'];
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        $resultado = $servicio->obtenerPorId($id);
        
        if (!$resultado) {
            return jsonResponse($response, [
                'error' => 'Servicio no encontrado'
            ], 404);
        }
        
        return jsonResponse($response, $resultado);
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al obtener servicio',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// GET - Obtener servicios por categoría
$app->get('/api/servicios/categoria/{categoria}', function (Request $request, Response $response, array $args) {
    try {
        $categoria = $args['categoria'];
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        $servicios = $servicio->obtenerPorCategoria($categoria);
        return jsonResponse($response, $servicios);
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al obtener servicios por categoría',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// GET - Buscar servicios
$app->get('/api/servicios/buscar/{termino}', function (Request $request, Response $response, array $args) {
    try {
        $termino = $args['termino'];
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        $servicios = $servicio->buscar($termino);
        return jsonResponse($response, $servicios);
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al buscar servicios',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// GET - Obtener estadísticas
$app->get('/api/servicios/stats/general', function (Request $request, Response $response) {
    try {
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        $estadisticas = $servicio->obtenerEstadisticas();
        $porCategoria = $servicio->contarPorCategoria();
        $masCaros = $servicio->obtenerMasCaros(5);
        
        return jsonResponse($response, [
            'generales' => $estadisticas,
            'por_categoria' => $porCategoria,
            'mas_caros' => $masCaros
        ]);
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al obtener estadísticas',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// POST - Crear un nuevo servicio
$app->post('/api/servicios', function (Request $request, Response $response) {
    try {
        $data = $request->getParsedBody();
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        // Asignar propiedades
        $servicio->nombre_servicio = $data['nombre_servicio'] ?? '';
        $servicio->descripcion = $data['descripcion'] ?? null;
        $servicio->precio = $data['precio'] ?? 0;
        $servicio->duracion_estimada = $data['duracion_estimada'] ?? null;
        $servicio->categoria = $data['categoria'] ?? '';
        $servicio->activo = isset($data['activo']) ? (bool)$data['activo'] : true;
        
        // Validar datos
        $errores = $servicio->validar();
        if (!empty($errores)) {
            return jsonResponse($response, [
                'error' => 'Datos inválidos',
                'detalles' => $errores
            ], 400);
        }
        
        // Crear servicio
        $id_nuevo = $servicio->crear();
        
        if ($id_nuevo) {
            $nuevo_servicio = $servicio->obtenerPorId($id_nuevo);
            return jsonResponse($response, $nuevo_servicio, 201);
        } else {
            return jsonResponse($response, [
                'error' => 'No se pudo crear el servicio'
            ], 500);
        }
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al crear servicio',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// PUT - Actualizar un servicio
$app->put('/api/servicios/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        // Verificar si existe
        if (!$servicio->existe($id)) {
            return jsonResponse($response, [
                'error' => 'Servicio no encontrado'
            ], 404);
        }
        
        // Asignar propiedades
        $servicio->id_servicios = $id;
        $servicio->nombre_servicio = $data['nombre_servicio'] ?? '';
        $servicio->descripcion = $data['descripcion'] ?? null;
        $servicio->precio = $data['precio'] ?? 0;
        $servicio->duracion_estimada = $data['duracion_estimada'] ?? null;
        $servicio->categoria = $data['categoria'] ?? '';
        $servicio->activo = isset($data['activo']) ? (bool)$data['activo'] : true;
        
        // Validar datos
        $errores = $servicio->validar();
        if (!empty($errores)) {
            return jsonResponse($response, [
                'error' => 'Datos inválidos',
                'detalles' => $errores
            ], 400);
        }
        
        // Actualizar servicio
        if ($servicio->actualizar()) {
            $servicio_actualizado = $servicio->obtenerPorId($id);
            return jsonResponse($response, $servicio_actualizado);
        } else {
            return jsonResponse($response, [
                'error' => 'No se pudo actualizar el servicio'
            ], 500);
        }
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al actualizar servicio',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// DELETE - Eliminar un servicio
$app->delete('/api/servicios/{id}', function (Request $request, Response $response, array $args) {
    try {
        $id = (int)$args['id'];
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        // Verificar si existe
        if (!$servicio->existe($id)) {
            return jsonResponse($response, [
                'error' => 'Servicio no encontrado'
            ], 404);
        }
        
        // Eliminar servicio
        $servicio->id_servicios = $id;
        
        if ($servicio->eliminar()) {
            return jsonResponse($response, [
                'message' => 'Servicio eliminado correctamente'
            ]);
        } else {
            return jsonResponse($response, [
                'error' => 'No se pudo eliminar el servicio'
            ], 500);
        }
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al eliminar servicio',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

// PATCH - Desactivar/Activar servicio
$app->patch('/api/servicios/{id}/toggle', function (Request $request, Response $response, array $args) {
    try {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $db = getDbConnection();
        $servicio = new Servicio($db);
        
        // Verificar si existe
        if (!$servicio->existe($id)) {
            return jsonResponse($response, [
                'error' => 'Servicio no encontrado'
            ], 404);
        }
        
        $servicio->id_servicios = $id;
        $activar = isset($data['activo']) ? (bool)$data['activo'] : true;
        
        if ($activar) {
            $resultado = $servicio->activar();
        } else {
            $resultado = $servicio->desactivar();
        }
        
        if ($resultado) {
            $servicio_actualizado = $servicio->obtenerPorId($id);
            return jsonResponse($response, $servicio_actualizado);
        } else {
            return jsonResponse($response, [
                'error' => 'No se pudo actualizar el estado del servicio'
            ], 500);
        }
    } catch (Exception $e) {
        return jsonResponse($response, [
            'error' => 'Error al cambiar estado del servicio',
            'mensaje' => $e->getMessage()
        ], 500);
    }
});

$app->run();