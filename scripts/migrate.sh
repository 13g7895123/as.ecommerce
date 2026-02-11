#!/bin/bash

# Database Migration Script for CodeIgniter 4
# Usage: ./scripts/migrate.sh [environment] [action]
# Example: ./scripts/migrate.sh production
#          ./scripts/migrate.sh development rollback

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Parse arguments
ENVIRONMENT=${1:-development}
ACTION=${2:-migrate}

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
    echo "  migrate      - Run all migrations (default)"
    echo "  rollback     - Rollback the last migration"
    echo "  refresh      - Rollback all migrations and re-run them"
    echo "  status       - Check migration status"
    echo ""
    echo "Examples:"
    echo "  $0                          # Run migrations in development"
    echo "  $0 production               # Run migrations in production"
    echo "  $0 development rollback     # Rollback last migration"
    echo "  $0 production status        # Check migration status"
}

# Get the service name from docker compose
get_php_container() {
    cd ../docker
    local container=$(docker compose ps -q php)
    echo "$container"
}

print_message "$GREEN" "==================================="
print_message "$GREEN" "Environment: $ENVIRONMENT"
print_message "$GREEN" "Action: $ACTION"
print_message "$GREEN" "==================================="

# Get PHP container
PHP_CONTAINER=$(get_php_container)

if [ -z "$PHP_CONTAINER" ]; then
    print_message "$RED" "Error: PHP container is not running"
    print_message "$YELLOW" "Please start the services first: ./scripts/deploy.sh $ENVIRONMENT"
    exit 1
fi

# Execute migration commands
case "$ACTION" in
    migrate)
        print_message "$YELLOW" "Running migrations..."
        docker exec "$PHP_CONTAINER" php spark migrate
        print_message "$GREEN" "✓ Migrations completed successfully"
        ;;
    rollback)
        print_message "$YELLOW" "Rolling back last migration..."
        docker exec "$PHP_CONTAINER" php spark migrate:rollback
        print_message "$GREEN" "✓ Rollback completed successfully"
        ;;
    refresh)
        print_message "$YELLOW" "Refreshing all migrations..."
        docker exec "$PHP_CONTAINER" php spark migrate:refresh
        print_message "$GREEN" "✓ Migrations refreshed successfully"
        ;;
    status)
        print_message "$YELLOW" "Checking migration status..."
        docker exec "$PHP_CONTAINER" php spark migrate:status
        ;;
    *)
        print_message "$RED" "Error: Unknown action '$ACTION'"
        echo ""
        print_usage
        exit 1
        ;;
esac
