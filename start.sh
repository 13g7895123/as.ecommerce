#!/bin/bash

set -e

# Load environment variables
if [ -f .env.prod ]; then
    export $(cat .env.prod | grep -v '^#' | xargs)
fi

COLOR=${1:-$ACTIVE_COLOR}

echo "=========================================="
echo "  E-commerce Docker Environment"
echo "=========================================="
echo "Starting environment with ACTIVE_COLOR=$COLOR..."

# Update .env.prod with the new color if provided
if [ "$1" != "" ]; then
    sed -i "s/ACTIVE_COLOR=.*/ACTIVE_COLOR=$COLOR/" .env.prod
fi

# Use 'docker compose' (v2) instead of 'docker-compose' (v1)
DOCKER_COMPOSE="docker compose"

# Check if docker compose v2 is available
if ! docker compose version &> /dev/null; then
    echo "Error: docker compose v2 is not available."
    echo "Please install Docker Compose V2 plugin."
    exit 1
fi

# Build and start containers
$DOCKER_COMPOSE --env-file .env.prod up -d --build

# Wait for containers to be ready
echo "Waiting for containers to start..."
sleep 5

# Check if CI4 is installed
if [ ! -f backend/spark ]; then
    echo "CodeIgniter 4 not found. Installing..."
    $DOCKER_COMPOSE exec -T -u root backend-$COLOR composer create-project codeigniter4/appstarter . --no-interaction
    
    # Set permissions
    $DOCKER_COMPOSE exec -T -u root backend-$COLOR chown -R dev:www-data /var/www/html
    $DOCKER_COMPOSE exec -T -u root backend-$COLOR chmod -R 775 /var/www/html/writable
    
    echo "CodeIgniter 4 installed successfully."
else
    echo "CodeIgniter 4 already installed."
fi

echo ""
echo "=========================================="
echo "  Environment is up and running!"
echo "=========================================="
echo "  Web (Nginx):    http://localhost:$NGINX_PORT"
echo "  phpMyAdmin:     http://localhost:$PMA_PORT"
echo "  Active Backend: backend-$COLOR"
echo ""
echo "  Verify spark:"
echo "    $DOCKER_COMPOSE exec backend-$COLOR php spark list"
echo "=========================================="
