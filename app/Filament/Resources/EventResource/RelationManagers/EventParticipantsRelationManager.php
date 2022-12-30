<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EventParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'eventParticipants';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'participatedEvents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Participants')
                    ->required()
                    ->searchable()
                    ->options(User::getUserOption()),    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                ->preloadRecordSelect(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }    

    public static function canViewForRecord(Model $ownerRecord): bool
    {
    // 
    $user=auth()->user();
    if ($user->isAdmin()){

        return true;
    }else if($user->isOrganizer()){

        return $ownerRecord->isApproved();
    }else {
        return false; 
    }

    
    }
}
