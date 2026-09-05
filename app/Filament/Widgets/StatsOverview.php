<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Business;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Published Businesses', Business::where('status', 'published')->count())
                ->description('Active in directory')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),
            Stat::make('Pending Businesses', Business::where('status', 'pending_review')->count())
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Published Articles', Article::where('status', 'published')->count())
                ->description('Live news posts')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('info'),
        ];
    }
}
