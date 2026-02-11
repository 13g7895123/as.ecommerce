#!/bin/bash

# Deploy script for Docker Compose environment
# Usage: ./deploy.sh [environment] [action]
# Example: ./deploy.sh production
#          ./deploy.sh development restart
#          ./deploy.sh production down

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Parse arguments
# Check if first argument is an action (if no second argument provided)
VALID_ACTIONS="up down restart logs ps build"
VALID_ENVS="development production"

if [ -z "$1" ]; then
    # No arguments, use defaults
    ENVIRONMENT="development"
    ACTION="up"
elif [ -z "$2" ]; then
    # Only one argument provided
    # Check if it's an action or environment
    if [[ " $VALID_ACTIONS " =~ " $1 " ]]; then
        # First arg is action, use default environment
        ENVIRONMENT="development"
        ACTION="$1"
    else
        # First arg is environment, use default action
        ENVIRONMENT="$1"
        ACTION="up"
    fi
else
    # Both arguments provided
    ENVIRONMENT="$1"
    ACTION="$2"
fi

DOCKER_DIR="../docker"
ENV_FILE="$DOCKER_DIR/.env"
ENV_SOURCE="$DOCKER_DIR/envs/$ENVIRONMENT.env"

# Print colored message
print_message() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${NC}"
}

# Print usage
print_usage() {
    echo "Usage: $0 [environment] [action]"
    echo ""
    echo "Environments:"
    echo "  development  - Development environment (default)"
    echo "  production   - Production environment"
    echo ""
    echo "Actions:"
    echo "  up           - Start services (default)"
    echo "  down         - Stop and remove services"
    echo "  restart      - Restart services"
    echo "  logs         - Show logs"
    echo "  ps           - List services"
    echo "  build        - Build or rebuild services"
    echo ""
    echo "Examples:"
    echo "  $0                      # Start development (default)"
    echo "  $0 production           # Start production"
    echo "  $0 development restart  # Restart development"
    echo "  $0 production down      # Stop production"
    echo "  $0 logs                 # Show development logs"
}

# Check if docker directory exists
if [ ! -d "$DOCKER_DIR" ]; then
    print_message "$RED" "Error: Docker directory not found at $DOCKER_DIR"
    exit 1
fi

# Check if environment file exists
if [ ! -f "$ENV_SOURCE" ]; then
    print_message "$RED" "Error: Environment file not found at $ENV_SOURCE"
    print_message "$YELLOW" "Available environments:"
    ls -1 "$DOCKER_DIR/envs/" | sed 's/.env$//'
    exit 1
fi

# Check if .env exists, if not copy from envs
if [ ! -f "$ENV_FILE" ]; then
    print_message "$YELLOW" "Environment file not found. Copying from $ENV_SOURCE..."
    cp "$ENV_SOURCE" "$ENV_FILE"
    print_message "$GREEN" "✓ Environment file created successfully"
else
    # Check if source file is newer than .env
    if [ "$ENV_SOURCE" -nt "$ENV_FILE" ]; then
        print_message "$YELLOW" "Warning: $ENV_SOURCE is newer than $ENV_FILE"
        read -p "Do you want to update .env file? (y/N) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            cp "$ENV_SOURCE" "$ENV_FILE"
            print_message "$GREEN" "✓ Environment file updated"
        fi
    fi
fi

print_message "$GREEN" "==================================="
print_message "$GREEN" "Environment: $ENVIRONMENT"
print_message "$GREEN" "Action: $ACTION"
print_message "$GREEN" "==================================="

# Change to docker directory
cd "$DOCKER_DIR"

# Execute docker compose command
case "$ACTION" in
    up)
        print_message "$YELLOW" "Starting services..."
        docker compose --env-file .env up -d --build
        print_message "$GREEN" "✓ Services started successfully"
        echo ""
        docker compose ps
        ;;
    down)
        print_message "$YELLOW" "Stopping services..."
        docker compose --env-file .env down
        print_message "$GREEN" "✓ Services stopped successfully"
        ;;
    restart)
        print_message "$YELLOW" "Restarting services..."
        docker compose --env-file .env restart
        print_message "$GREEN" "✓ Services restarted successfully"
        echo ""
        docker compose ps
        ;;
    logs)
        docker compose --env-file .env logs -f
        ;;
    ps)
        docker compose --env-file .env ps
        ;;
    build)
        print_message "$YELLOW" "Building services..."
        docker compose --env-file .env build --no-cache
        print_message "$GREEN" "✓ Services built successfully"
        ;;
    *)
        print_message "$RED" "Error: Unknown action '$ACTION'"
        echo ""
        print_usage
        exit 1
        ;;
esac

# Show service URLs if services are running
if [ "$ACTION" = "up" ] || [ "$ACTION" = "restart" ]; then
    echo ""
    print_message "$GREEN" "==================================="
    print_message "$GREEN" "Services are running at:"
    print_message "$GREEN" "==================================="
    
    # Load .env file to get ports
    source .env
    
    echo "Web Application: http://localhost:${NGINX_PORT}"
    echo "PhpMyAdmin:      http://localhost:${PHPMYADMIN_PORT}"
    echo "MySQL:           localhost:${MYSQL_PORT}"
    echo ""
    print_message "$YELLOW" "To view logs: ./deploy.sh $ENVIRONMENT logs"
    print_message "$YELLOW" "To stop:      ./deploy.sh $ENVIRONMENT down"
fi
