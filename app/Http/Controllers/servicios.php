from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import Optional, List
from decimal import Decimal
from datetime import datetime
import mysql.connector
from mysql.connector import Error

app = FastAPI(title="API Servicios Veterinaria")

# Configurar CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Configuración de base de datos
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "tu_password",
    "database": "veterinaria"
}

def get_db_connection():
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        return conn
    except Error as e:
        print(f"Error al conectar a la base de datos: {e}")
        raise HTTPException(status_code=500, detail="Error de conexión a la base de datos")

# Modelos Pydantic
class ServicioBase(BaseModel):
    nombre_servicio: str = Field(..., max_length=100)
    descripcion: Optional[str] = None
    precio: float = Field(..., gt=0)
    duracion_estimada: Optional[int] = Field(None, gt=0)
    categoria: str = Field(..., pattern="^(consulta|vacuna|baño|grooming)$")
    activo: bool = True

class ServicioCreate(ServicioBase):
    pass

class ServicioUpdate(ServicioBase):
    pass

class Servicio(ServicioBase):
    id_servicios: int
    creado_en: Optional[datetime] = None

    class Config:
        from_attributes = True

# Endpoints

@app.get("/")
def read_root():
    return {"message": "API Servicios Veterinaria"}

@app.get("/api/servicios", response_model=List[Servicio])
def listar_servicios():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    try:
        cursor.execute("""
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            ORDER BY creado_en DESC
        """)
        servicios = cursor.fetchall()
        return servicios
    except Error as e:
        raise HTTPException(status_code=500, detail=f"Error al obtener servicios: {str(e)}")
    finally:
        cursor.close()
        conn.close()

@app.get("/api/servicios/{id_servicio}", response_model=Servicio)
def obtener_servicio(id_servicio: int):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    try:
        cursor.execute("""
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            WHERE id_servicios = %s
        """, (id_servicio,))
        servicio = cursor.fetchone()
        
        if not servicio:
            raise HTTPException(status_code=404, detail="Servicio no encontrado")
        
        return servicio
    except Error as e:
        raise HTTPException(status_code=500, detail=f"Error al obtener servicio: {str(e)}")
    finally:
        cursor.close()
        conn.close()

@app.post("/api/servicios", response_model=Servicio, status_code=201)
def crear_servicio(servicio: ServicioCreate):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    try:
        cursor.execute("""
            INSERT INTO servicios (nombre_servicio, descripcion, precio, 
                                 duracion_estimada, categoria, activo)
            VALUES (%s, %s, %s, %s, %s, %s)
        """, (
            servicio.nombre_servicio,
            servicio.descripcion,
            servicio.precio,
            servicio.duracion_estimada,
            servicio.categoria,
            servicio.activo
        ))
        conn.commit()
        
        # Obtener el servicio recién creado
        id_nuevo = cursor.lastrowid
        cursor.execute("""
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            WHERE id_servicios = %s
        """, (id_nuevo,))
        
        nuevo_servicio = cursor.fetchone()
        return nuevo_servicio
    except Error as e:
        conn.rollback()
        raise HTTPException(status_code=500, detail=f"Error al crear servicio: {str(e)}")
    finally:
        cursor.close()
        conn.close()

@app.put("/api/servicios/{id_servicio}", response_model=Servicio)
def actualizar_servicio(id_servicio: int, servicio: ServicioUpdate):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    try:
        # Verificar si existe
        cursor.execute("SELECT id_servicios FROM servicios WHERE id_servicios = %s", (id_servicio,))
        if not cursor.fetchone():
            raise HTTPException(status_code=404, detail="Servicio no encontrado")
        
        # Actualizar
        cursor.execute("""
            UPDATE servicios
            SET nombre_servicio = %s,
                descripcion = %s,
                precio = %s,
                duracion_estimada = %s,
                categoria = %s,
                activo = %s
            WHERE id_servicios = %s
        """, (
            servicio.nombre_servicio,
            servicio.descripcion,
            servicio.precio,
            servicio.duracion_estimada,
            servicio.categoria,
            servicio.activo,
            id_servicio
        ))
        conn.commit()
        
        # Obtener el servicio actualizado
        cursor.execute("""
            SELECT id_servicios, nombre_servicio, descripcion, precio, 
                   duracion_estimada, categoria, activo, creado_en
            FROM servicios
            WHERE id_servicios = %s
        """, (id_servicio,))
        
        servicio_actualizado = cursor.fetchone()
        return servicio_actualizado
    except Error as e:
        conn.rollback()
        raise HTTPException(status_code=500, detail=f"Error al actualizar servicio: {str(e)}")
    finally:
        cursor.close()
        conn.close()

@app.delete("/api/servicios/{id_servicio}")
def eliminar_servicio(id_servicio: int):
    conn = get_db_connection()
    cursor = conn.cursor()
    
    try:
        # Verificar si existe
        cursor.execute("SELECT id_servicios FROM servicios WHERE id_servicios = %s", (id_servicio,))
        if not cursor.fetchone():
            raise HTTPException(status_code=404, detail="Servicio no encontrado")
        
        # Eliminar
        cursor.execute("DELETE FROM servicios WHERE id_servicios = %s", (id_servicio,))
        conn.commit()
        
        return {"message": "Servicio eliminado correctamente"}
    except Error as e:
        conn.rollback()
        raise HTTPException(status_code=500, detail=f"Error al eliminar servicio: {str(e)}")
    finally:
        cursor.close()
        conn.close()

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8000)