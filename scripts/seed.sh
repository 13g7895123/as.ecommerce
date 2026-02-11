#!/bin/bash

# Database Seeder Script for CodeIgniter 4
# Usage: ./scripts/seed.sh [environment] [seeder_name]
# Example: ./scripts/seed.sh production
#          ./scripts/seed.sh development UserSeeder

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Parse arguments
ENVIRONMENT=${1:-development}
SEEDER=${2:-}

# Print colored message
print_message() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${NC}"
}

# Print usage
print_usage() {
    echo "Usage: $0 [environment] [seeder_name]"
    echo ""
    echo "Environments:"
    echo "  development  - Development environment (default)"
    echo "  production   - Production environment"
    echo ""
    echo "Seeder Name:"
    echo "  If not specified, runs the default seeder (DatabaseSeeder)"
    echo "  You can specify a specific seeder class name"
    echo ""
    echo "Examples:"
    echo "  $0                          # Run default seeder in development"
    echo "  $0 production               # Run default seeder in production"
    echo "  $0 development UserSeeder   # Run UserSeeder in development"
    echo "  $0 production ProductSeeder # Run ProductSeeder in production"
}

# Get the service name from docker compose
get_php_container() {
    cd ../docker
    local container=$(docker compose ps -q php)
    echo "$container"
}

print_message "$GREEN" "==================================="
print_message "$GREEN" "Environment: $ENVIRONMENT"
if [ -n "$SEEDER" ]; then
    print_message "$GREEN" "Seeder: $SEEDER"
else
    print_message "$GREEN" "Seeder: DatabaseSeeder (default)"
fi
print_message "$GREEN" "==================================="

# Get PHP container
PHP_CONTAINER=$(get_php_container)

if [ -z "$PHP_CONTAINER" ]; then
    print_message "$RED" "Error: PHP container is not running"
    print_message "$YELLOW" "Please start the services first: ./scripts/deploy.sh $ENVIRONMENT"
    exit 1
fi

# Execute seeder
if [ -n "$SEEDER" ]; then
    print_message "$YELLOW" "Running seeder: $SEEDER..."
    docker exec "$PHP_CONTAINER" php spark db:seed "$SEEDER"
else
    print_message "$YELLOW" "Running default seeder..."
    docker exec "$PHP_CONTAINER" php spark db:seed
fi

print_message "$GREEN" "✓ Seeding completed successfully"

# Show helpful message
echo ""
print_message "$YELLOW" "Available Commands:"
echo "  List all seeders: docker exec $PHP_CONTAINER php spark db:seed --help"
echo "  Create new seeder: docker exec $PHP_CONTAINER php spark make:seeder [SeederName]"
