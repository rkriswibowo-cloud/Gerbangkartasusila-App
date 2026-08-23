<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DestinationCategory;
use App\Models\Destination;

class DestinationCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Destinations by Category';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '275px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $categories = DestinationCategory::withCount('destinations')->get();
        
        return [
            'datasets' => [
                [
                    'label' => 'Destinations',
                    'data' => $categories->pluck('destinations_count')->toArray(),
                    'backgroundColor' => [
                        '#10b981', // emerald
                        '#3b82f6', // blue
                        '#f59e0b', // amber
                        '#ef4444', // red
                        '#8b5cf6', // violet
                    ],
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}