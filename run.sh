#!/bin/sh
set -eu
cd "$(dirname "$0")"

QLST_DB_PASSWORD=$(awk -F"'" '/private.*password/{print $2}' config/Database.php)
if [ -z "$QLST_DB_PASSWORD" ]; then
    echo 'Không tìm thấy mật khẩu MySQL trong config/Database.php.' >&2
    exit 1
fi
export QLST_DB_PASSWORD

docker compose up -d --build

table_count=$(docker compose exec -T db sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '\''quanlysieuthi'\''"')
if [ "$table_count" -eq 0 ]; then
    if [ "$#" -ne 1 ] || [ ! -f "$1" ]; then
        echo 'Cơ sở dữ liệu đang trống. Chạy: ./run.sh /đường/dẫn/databasechinh.sql' >&2
        exit 1
    fi
    docker compose exec -T db sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot' < "$1"
fi

echo 'Ứng dụng đã chạy tại http://127.0.0.1:8000/index.php?controller=nguoidung&action=login'
