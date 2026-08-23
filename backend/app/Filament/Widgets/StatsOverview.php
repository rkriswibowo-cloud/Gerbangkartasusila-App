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
    protected static ?int $sort = 1;
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