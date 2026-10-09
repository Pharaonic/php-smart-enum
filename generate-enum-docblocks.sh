#!/usr/bin/env bash

set -Eeuo pipefail

PROJECT_ROOT="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PHP_SCRIPT="$PROJECT_ROOT/bin/generate-enum-docblocks.php"

if [[ ! -f "$PROJECT_ROOT/vendor/autoload.php" ]]; then
    echo "[ERROR] vendor/autoload.php not found."
    exit 1
fi

if [[ ! -f "$PHP_SCRIPT" ]]; then
    echo "[ERROR] PHP script not found: $PHP_SCRIPT"
    exit 1
fi

exec php "$PHP_SCRIPT" "$@"