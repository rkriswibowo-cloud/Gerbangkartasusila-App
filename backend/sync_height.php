<?php
$dir = __DIR__ . '/app/Filament/Widgets';

foreach (['ContentGrowthChart.php', 'DestinationCategoryChart.php'] as $file) {
    $path = $dir . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (!str_contains($content, '$maxHeight')) {
            // Inserting maxHeight right after $sort
            $content = preg_replace('/(protected static \?int \$sort = 2;)/', "$1\n    protected static ?string \$maxHeight = '275px';", $content);
            file_put_contents($path, $content);
        }
    }
}
echo "Heights synced.";
