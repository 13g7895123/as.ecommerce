#!/bin/bash

# Load environment variables
if [ -f .env.prod ]; then
    export $(cat .env.prod | grep -v '#' | awk '/=/ {print $1}')
fi

COLOR=${1:-$ACTIVE_COLOR}

echo "Starting environment with ACTIVE_COLOR=$COLOR..."

# Update .env.prod with the new color if provided
if [ "$1" != "" ]; then
    sed -i "s/ACTIVE_COLOR=.*/ACTIVE_COLOR=$COLOR/" .env.prod
fi

# Build and start containers
docker-compose --env-file .env.prod up -d --build

# Check if CI4 is installed
if [ ! -f backend/spark ]; then
    echo "CodeIgniter 4 not found. Installing..."
    docker-compose exec -T backend-$COLOR composer create-project codeigniter4/appstarter . --no-interaction
    
    # Set permissions
    docker-compose exec -T backend-$COLOR chmod -R 777 writable
    
    echo "CodeIgniter 4 installed successfully."
else
    echo "CodeIgniter 4 already installed."
fi

echo "Environment is up and running!"
echo "Nginx: http://localhost:$NGINX_PORT"
echo "phpMyAdmin: http://localhost:$PMA_PORT"
echo "Active Backend: backend-$COLOR"
