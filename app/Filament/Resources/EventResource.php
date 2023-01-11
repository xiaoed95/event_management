<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;


class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('venue'),
                Forms\Components\DateTimePicker::make('datetime')
                    ->label('Date & Time'),
                Forms\Components\Select::make('Status')
                    ->required()
                    ->options(Event::statusOption())
                    ->default(Event::PENDING)
                    ->disabled(auth()->user()->isNotAdmin()),
                Forms\Components\FileUpload::make('poster')
                    ->image()
                    ->directory('event-poster'),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->searchable()
                    ->options(User::getUserOption())
                    ->label('Organizer')
                    ->default(auth()->id())
                    ->disabled(auth()->user()->isNotAdmin()),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('datetime'),
                Tables\Columns\TextColumn::make('status_name')->label('Status'),
                Tables\Columns\TextColumn::make('organizer_name')->label('Organizer')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\EventParticipantsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        $not_admin=auth()->user()->isNotAdmin();
        return parent::getEloquentQuery()
            ->withoutGlobalScopes()
            ->when($not_admin,function($query){

                $query->where('user_id', auth()->id());
            });
            
    }


}
