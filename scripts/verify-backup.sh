#!/bin/sh
# Restore into a disposable database, leaving the site's database untouched.
set -eu
backup_file=${1:?Usage: sh scripts/verify-backup.sh /absolute/path/backup.sql}
test -s "$backup_file"
cd "$(dirname "$0")/.."
restore_db="osteza_restore_check_$$"
cleanup() {
 docker compose exec -T -e OSTEZA_RESTORE_DB="$restore_db" db sh -c 'MYSQL_PWD=$MYSQL_ROOT_PASSWORD mysql -uroot -e "DROP DATABASE IF EXISTS \`$OSTEZA_RESTORE_DB\`"' >/dev/null
}
trap cleanup EXIT INT TERM
docker compose exec -T -e OSTEZA_RESTORE_DB="$restore_db" db sh -c 'MYSQL_PWD=$MYSQL_ROOT_PASSWORD mysql -uroot -e "CREATE DATABASE \`$OSTEZA_RESTORE_DB\`"'
docker compose exec -T -e OSTEZA_RESTORE_DB="$restore_db" db sh -c 'MYSQL_PWD=$MYSQL_ROOT_PASSWORD mysql -uroot "$OSTEZA_RESTORE_DB"' < "$backup_file"
docker compose exec -T -e OSTEZA_RESTORE_DB="$restore_db" db sh -c 'MYSQL_PWD=$MYSQL_ROOT_PASSWORD mysql -uroot "$OSTEZA_RESTORE_DB" -N -e "SELECT COUNT(*) AS posts FROM wp_posts; SELECT COUNT(*) AS options FROM wp_options;"'
printf 'Backup restored successfully into a disposable database.\n'
