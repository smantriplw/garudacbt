#!/bin/sh
set -e

APP_DIR=/var/www/html
CONFIG="$APP_DIR/application/config/config.php"

# The repository intentionally ships only config.example.php. Create the real
# config on first start so the container is usable without manual steps.
# -s also covers an empty file left behind when a bind mount targeted it.
if [ ! -s "$CONFIG" ]; then
    cp "$APP_DIR/application/config/config.example.php" "$CONFIG"
    # Local containers are served over http; the example hardcodes https.
    sed -i "s/^\$scheme = 'https';/\$scheme = 'http';/" "$CONFIG"
    # The official image has no /var/lib/php/sessions.
    sed -i "s|\$config\['sess_save_path'\] = NULL;|\$config['sess_save_path'] = '/tmp';|" "$CONFIG"
    chown "${APP_UID:-1000}:${APP_GID:-1000}" "$CONFIG" 2>/dev/null || true
    echo "[entrypoint] created application/config/config.php from config.example.php"
fi

# Directories the application writes to. They exist in the repository, this
# covers a checkout that lost them alongside the bind mount.
mkdir -p "$APP_DIR/uploads"/bank_soal "$APP_DIR/uploads"/foto_siswa \
         "$APP_DIR/uploads"/profiles "$APP_DIR/uploads"/settings \
         "$APP_DIR/uploads"/materi "$APP_DIR/uploads"/import \
         "$APP_DIR/uploads"/temp "$APP_DIR"/application/logs "$APP_DIR"/application/cache
chown "${APP_UID:-1000}:${APP_GID:-1000}" \
      "$APP_DIR/uploads" "$APP_DIR/application/logs" "$APP_DIR/application/cache" 2>/dev/null || true

exec "$@"
