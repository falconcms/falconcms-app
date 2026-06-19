<?php
// Post-composer-install patches applied at Docker build time.

// Fix 1: PHP_BINARY in DashboardController resolves to php-fpm in web context.
// We need to detect and use the CLI php binary instead.
$file = 'vendor/falconcms/falconcms/src/Http/Controllers/Admin/DashboardController.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    if (!str_contains($content, 'which php')) {
        $content = str_replace(
            '$phpBin    = PHP_BINARY;',
            '$phpBin    = PHP_BINARY;' . "\n" .
            "        // PHP_BINARY points to php-fpm in web context; resolve to CLI php\n" .
            "        if (basename(\$phpBin) !== 'php') {\n" .
            "            \$cliPhp = trim(shell_exec('which php 2>/dev/null') ?? '');\n" .
            "            if (\$cliPhp && is_executable(\$cliPhp)) \$phpBin = \$cliPhp;\n" .
            '        }',
            $content
        );
        file_put_contents($file, $content);
        echo "Patch 1 applied: PHP_BINARY fix in DashboardController\n";
    } else {
        echo "Patch 1 skipped: PHP_BINARY fix already present\n";
    }
} else {
    echo "Patch 1 skipped: DashboardController.php not found\n";
}
