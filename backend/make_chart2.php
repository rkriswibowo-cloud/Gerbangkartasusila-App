<?php

$dir = __DIR__ . '/app/Filament/Widgets';

$chart2 = <<<PHP
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DestinationCategory;
use App\Models\Destination;

class DestinationCategoryChart extends ChartWidget
{
    protected static ?string \$heading = 'Destinations by Category';
    protected static ?int \$sort = 3;

    protected function getData(): array
    {
        \$categories = DestinationCategory::withCount('destinations')->get();
        
        return [
            'datasets' => [
                [
                    'label' => 'Destinations',
                    'data' => \$categories->pluck('destinations_count')->toArray(),
                    'backgroundColor' => [
                        '#10b981', // emerald
                        '#3b82f6', // blue
                        '#f59e0b', // amber
                        '#ef4444', // red
                        '#8b5cf6', // violet
                    ],
                ],
            ],
            'labels' => \$categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
PHP;

// Fix escaping
$chart2 = str_replace('\$heading', '$heading', $chart2);
$chart2 = str_replace('\$sort', '$sort', $chart2);
$chart2 = str_replace('\$categories', '$categories', $chart2);

file_put_contents($dir . '/DestinationCategoryChart.php', $chart2);
echo "Chart 2 created.\\n";
