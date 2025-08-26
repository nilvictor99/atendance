<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Utils\PasswordGenerator;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('users.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('users.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('users.User_information'))
                    ->icon('heroicon-m-user')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->translateLabel()
                            ->email()
                            ->required(),
                    ]),

                Section::make(__('users.Security'))
                    ->icon('heroicon-m-adjustments-vertical')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->visibleOn('create')
                            ->revealable()
                            ->required()
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-key')
                                    ->action(function ($set) {
                                        $set('password', app(PasswordGenerator::class)->generate());
                                    })
                            )
                            ->maxLength(255),
                        Forms\Components\Select::make('roles')
                            ->translateLabel()
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->translateLabel()
                    ->searchable()
                    ->default('Sin Roles')
                    ->badge(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('changePassword')
                    ->label('Nueva Clave')
                    ->icon('heroicon-o-lock-closed')
                    ->visible(fn () => app(AuthService::class)->IsSuperUser())
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->disabled()
                            ->default(fn (User $record) => $record->name),
                        Forms\Components\TextInput::make('email')
                            ->translateLabel()
                            ->disabled()
                            ->default(fn (User $record) => $record->email),
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->revealable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('password_confirmation', $state);
                            })
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-key')
                                    ->action(function ($set) {
                                        $password = app(PasswordGenerator::class)->generate();
                                        $set('password', $password);
                                        $set('password_confirmation', $password);
                                    })
                            ),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->translateLabel()
                            ->password()
                            ->required()
                            ->revealable()
                            ->live()
                            ->maxLength(255)
                            ->same('password'),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update(['password' => \Illuminate\Support\Facades\Hash::make($data['password'])]);
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
