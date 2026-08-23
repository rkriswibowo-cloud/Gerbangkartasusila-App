<?php

$dir = __DIR__ . '/app/Filament/Widgets';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$statsWidget = <<<PHP
<?php

namespace App\Filament\Widgets;

use App\Models\Destination;
use App\Models\Event;
use App\Models\Article;
use App\Models\User;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Destinations', Destination::count())
                ->description('All tourism destinations')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),
            Stat::make('Total Events', Event::count())
                ->description('Upcoming & past events')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('Total Articles', Article::count())
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
PHP;

$chartWidget = <<<PHP
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Article;

class ContentGrowthChart extends ChartWidget
{
    protected static ?string \$heading = 'Content Growth (Dummy Data)';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Destinations',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'borderColor' => '#10b981', // emerald
                ],
                [
                    'label' => 'Events',
                    'data' => [0, 5, 12, 10, 15, 20, 24, 25, 30, 35, 40, 50],
                    'borderColor' => '#3b82f6', // blue
                ],
                [
                    'label' => 'Articles',
                    'data' => [0, 2, 4, 8, 12, 25, 30, 45, 50, 60, 75, 80],
                    'borderColor' => '#f59e0b', // amber
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
PHP;

// Fixing variable escaping for the write_to_file input
$chartWidget = str_replace('\$heading', '$heading', $chartWidget);

file_put_contents($dir . '/StatsOverview.php', $statsWidget);
file_put_contents($dir . '/ContentGrowthChart.php', $chartWidget);
echo "Widgets created successfully.";
