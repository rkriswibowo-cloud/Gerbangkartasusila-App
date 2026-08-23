<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Article;

class ContentGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Content Growth (Dummy Data)';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '275px';
    protected int | string | array $columnSpan = 1;

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