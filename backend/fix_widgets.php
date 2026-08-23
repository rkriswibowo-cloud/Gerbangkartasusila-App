<?php
$dir = __DIR__ . '/app/Filament/Widgets';

// StatsOverview.php
$statsPath = $dir . '/StatsOverview.php';
$content = file_get_contents($statsPath);
if (!str_contains($content, '$sort = 1;')) {
    $content = preg_replace('/class StatsOverview extends BaseWidget\s*\{/', "class StatsOverview extends BaseWidget\n{\n    protected static ?int \$sort = 1;", $content);
    file_put_contents($statsPath, $content);
}

// ContentGrowthChart.php
$growthPath = $dir . '/ContentGrowthChart.php';
$content = file_get_contents($growthPath);
if (!str_contains($content, '$sort = 2;')) {
    $content = preg_replace('/protected static \?string \$heading = (.*?);/', "protected static ?string \$heading = $1;\n    protected static ?int \$sort = 2;", $content);
    file_put_contents($growthPath, $content);
}

// DestinationCategoryChart.php
$catPath = $dir . '/DestinationCategoryChart.php';
$content = file_get_contents($catPath);
$content = preg_replace('/protected static \?int \$sort = 3;/', "protected static ?int \$sort = 2;", $content);
file_put_contents($catPath, $content);

echo "Widgets sorted.";
