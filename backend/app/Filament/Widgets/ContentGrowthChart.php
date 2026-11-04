<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Article;

class ContentGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Konten Baru per Bulan';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '275px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn ($offset) => now()->startOfMonth()->subMonths($offset));
        $counts = function (string $model) use ($months): array {
            $byMonth = $model::where('created_at', '>=', $months->first())
                ->where('created_at', '<', now()->startOfMonth()->addMonth())
                ->get(['created_at'])->countBy(fn ($row) => $row->created_at->format('Y-m'));
            return $months->map(fn ($month) => $byMonth->get($month->format('Y-m'), 0))->all();
        };
        return [
            'datasets' => [
                [
                    'label' => 'Destinations',
                    'data' => $counts(Destination::class),
                    'borderColor' => '#10b981', // emerald
                ],
                [
                    'label' => 'Events',
                    'data' => $counts(Event::class),
                    'borderColor' => '#3b82f6', // blue
                ],
                [
                    'label' => 'Articles',
                    'data' => $counts(Article::class),
                    'borderColor' => '#f59e0b', // amber
                ],
            ],
            'labels' => $months->map(fn ($month) => $month->format('M Y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
