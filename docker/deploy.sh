#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

COMPOSE="docker compose -f docker-compose.yml -f docker-compose.prod.yml"

usage() {
    cat <<'EOF'
Usage: ./docker/deploy.sh <command>

Commands:
  up          Build and start production stack
  down        Stop containers
  logs        Follow logs (all services)
  shell       Open bash in app container
  artisan     Run artisan (e.g. ./docker/deploy.sh artisan migrate --force)
  install     composer install + storage:link + optimize
  ssl-on      Enable HTTPS nginx template (set NGINX_SSL=true in .env first)
EOF
}

cmd="${1:-up}"
shift || true

case "$cmd" in
  up)
    $COMPOSE up -d --build
    ;;
  down)
    $COMPOSE down
    ;;
  logs)
    $COMPOSE logs -f "$@"
    ;;
  shell)
    $COMPOSE exec app sh
    ;;
  artisan)
    $COMPOSE exec app php artisan "$@"
    ;;
  install)
    $COMPOSE exec app composer install --no-dev --optimize-autoloader --no-interaction
    $COMPOSE exec app php artisan storage:link --force || true
    $COMPOSE exec app php artisan config:cache
    $COMPOSE exec app php artisan route:cache
    $COMPOSE exec app php artisan view:cache
    ;;
  *)
    usage
    exit 1
    ;;
esac
