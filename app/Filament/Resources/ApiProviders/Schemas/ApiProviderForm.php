<?php

namespace App\Filament\Resources\ApiProviders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApiProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات مزود الـ API الخارجي')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المزود')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('api_url')
                            ->label('رابط واجهة الـ API (Endpoint)')
                            ->url()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('api_key')
                            ->label('مفتاح الـ API (API Key / Token)')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(500),

                        Toggle::make('is_active')
                            ->label('تفعيل المزود')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
