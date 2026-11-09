#!/bin/sh
set -eu
cd "$(dirname "$0")"
QLST_DB_PASSWORD=$(awk -F"'" '/private.*password/{print $2}' config/Database.php)
export QLST_DB_PASSWORD
docker compose stop
