#!/bin/bash

set -e

# Load environment variables
if [ -f .env.prod ]; then
    export $(cat .env.prod | grep -v '^#' | xargs)
fi

# Determine new color
if [ "$ACTIVE_COLOR" == "blue" ]; then
    NEW_COLOR="green"
else
    NEW_COLOR="blue"
fi

echo "=========================================="
echo "  Blue-Green Deployment Switch"
echo "=========================================="
echo "  Current: backend-$ACTIVE_COLOR"
echo "  Switching to: backend-$NEW_COLOR"
echo "=========================================="

# Update .env.prod
sed -i "s/ACTIVE_COLOR=.*/ACTIVE_COLOR=$NEW_COLOR/" .env.prod

# Restart nginx to apply new config
docker compose --env-file .env.prod up -d nginx

echo ""
echo "Switch completed!"
echo "Active backend is now: backend-$NEW_COLOR"
echo ""
