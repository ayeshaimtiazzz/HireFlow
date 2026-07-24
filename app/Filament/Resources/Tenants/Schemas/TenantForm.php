<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('domain')
                    ->required(),
                Select::make('plan')
                    ->options(['starter' => 'Starter', 'growth' => 'Growth', 'enterprise' => 'Enterprise'])
                    ->default('starter')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'suspended' => 'Suspended', 'cancelled' => 'Cancelled'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
