#!/bin/bash

set -e

echo "Stopping all containers..."

docker compose --env-file .env.prod down

echo "All containers stopped."
