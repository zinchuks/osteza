#!/bin/sh
# Local, consistent database dump plus uploaded media. Store outside the web root.
set -eu
umask 077
backup_dir=${1:?Usage: sh scripts/backup-local.sh /absolute/path/outside/webroot}
case "$backup_dir" in /*) ;; *) echo 'Use an absolute backup directory.' >&2; exit 1;; esac
project_dir=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd -P)
mkdir -p "$backup_dir"
backup_dir=$(CDPATH= cd -- "$backup_dir" && pwd -P)
case "$backup_dir/" in "$project_dir/"*) echo 'Backups must be outside the web root.' >&2; exit 1;; esac
cd "$project_dir"
stamp=$(date -u +%Y%m%dT%H%M%SZ)
dump="$backup_dir/osteza-$stamp.sql"
docker compose exec -T db sh -c 'MYSQL_PWD=$MYSQL_PASSWORD mysqldump --no-tablespaces --single-transaction --quick -u"$MYSQL_USER" "$MYSQL_DATABASE"' > "$dump.partial"
test -s "$dump.partial"
mv "$dump.partial" "$dump"
tar -czf "$backup_dir/osteza-uploads-$stamp.tar.gz" wp-content/uploads
printf 'Database: %s\nUploads: %s\n' "$dump" "$backup_dir/osteza-uploads-$stamp.tar.gz"
