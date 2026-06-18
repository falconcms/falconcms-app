#!/bin/bash
set -e

echo "=============================="
echo "  FalconCMS — Starting Up"
echo "=============================="

# Railway injects $PORT — update nginx to listen on it (default 80)
NGINX_PORT="${PORT:-80}"
sed -i "s/listen 80;/listen $NGINX_PORT;/g" /etc/nginx/sites-enabled/default
echo "==> Nginx listening on port $NGINX_PORT"

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set. Add it in Render environment variables."
    exit 1
fi

echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Copying theme screenshots to public..."
php artisan tinker --execute="
    \$themes = glob(resource_path('views/themes/*'), GLOB_ONLYDIR);
    foreach (\$themes as \$dir) {
        \$slug = basename(\$dir);
        \$dest = public_path('themes/' . \$slug);
        @mkdir(\$dest, 0775, true);
        foreach (['screenshot.png', 'screenshot.jpg'] as \$f) {
            if (file_exists(\$dir . '/' . \$f)) @copy(\$dir . '/' . \$f, \$dest . '/' . \$f);
        }
    }
    echo 'done';
" --no-interaction 2>/dev/null || true

echo "==> Caching..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# First install: seed roles/permissions, create admin user, create shop pages
if [ "${FALCON_FIRST_INSTALL:-false}" = "true" ]; then
    echo "==> First-time install: seeding system data..."
    php artisan db:seed --class="FalconCms\\Core\\Database\\Seeders\\SystemSyncSeeder" --force
    php artisan db:seed --class="FalconCms\\Core\\Database\\Seeders\\LanguageSeeder" --force

    ADMIN_EMAIL="${FALCON_ADMIN_EMAIL:-admin@admin.com}"
    ADMIN_PASS="${FALCON_ADMIN_PASSWORD:-password}"

    echo "==> Creating admin user: ${ADMIN_EMAIL}"
    php artisan tinker --execute="
        \$role = \FalconCms\Core\Models\Role::whereIn('slug', ['super-admin','administrator'])->first();
        if (\$role) {
            \$u = \App\Models\User::firstOrNew(['email' => '${ADMIN_EMAIL}']);
            \$u->name = 'Administrator';
            \$u->password = bcrypt('${ADMIN_PASS}');
            \$u->role_id = \$role->id;
            \$u->save();
            echo 'Admin ready.';
        }
    " --no-interaction

    echo "==> Setting default CMS options..."
    php artisan tinker --execute="
        \$opts = ['login_url'=>'falcon-admin','register_url'=>'falcon-registration','login_theme'=>'modern','registration_theme'=>'modern','active_theme'=>'falcon-theme'];
        foreach (\$opts as \$k => \$v) {
            \Illuminate\Support\Facades\DB::table('cms_settings')->updateOrInsert(['key'=>\$k],['value'=>\$v]);
        }
        echo 'Options set.';
    " --no-interaction

    echo "==> First install complete!"
    echo "    Admin: ${ADMIN_EMAIL} / ${ADMIN_PASS}"
    echo "    Set FALCON_FIRST_INSTALL=false in Render for future deploys."
fi

echo "=============================="
echo "  Starting: Nginx + PHP-FPM + Queue + Scheduler"
echo "=============================="

exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
