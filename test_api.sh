#!/bin/bash

echo "======================================"
echo "🧪 Probando API de Huellitas"
echo "======================================"
echo ""

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # Sin color

# URL base
BASE_URL="http://127.0.0.1:8000/api"

echo -e "${BLUE}1. Haciendo login...${NC}"
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@huellitas.com","password":"admin123"}')

echo "$LOGIN_RESPONSE" | python3 -m json.tool 2>/dev/null

# Extraer token
TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"access_token":"[^"]*' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    echo -e "${RED}❌ Error: No se pudo obtener el token${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}✅ Token obtenido exitosamente${NC}"
echo "Token: ${TOKEN:0:50}..."
echo ""

echo -e "${BLUE}2. Obteniendo lista de propietarios...${NC}"
OWNERS=$(curl -s -X GET "$BASE_URL/owners" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "$OWNERS" | python3 -m json.tool 2>/dev/null
echo ""

echo -e "${BLUE}3. Obteniendo lista de mascotas...${NC}"
PETS=$(curl -s -X GET "$BASE_URL/pets" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "$PETS" | python3 -m json.tool 2>/dev/null
echo ""

echo -e "${BLUE}4. Obteniendo lista de servicios...${NC}"
SERVICES=$(curl -s -X GET "$BASE_URL/services" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "$SERVICES" | python3 -m json.tool 2>/dev/null
echo ""

echo -e "${GREEN}======================================"
echo "✅ Pruebas completadas"
echo "======================================${NC}"
