<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;

class Settings extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static ?string $navigationLabel = 'Setting';



    // This page should not appear in the navigation
    protected static bool $shouldRegisterNavigation = false;



    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $current_password;
    public $change_password = false;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function getFormSchema(): array
    {
        return [
            Group::make()
                ->schema([
                    Section::make()
                        ->schema([

                            TextInput::make('name')
                                ->label('Name')
                                ->required()->columnSpan(2),

                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()->columnSpan(2),

                            Checkbox::make('change_password')
                                ->label("Change Password")
                                ->live()
                                ->columnSpan(1),

                            TextInput::make('current_password')
                                ->label('Current Password')
                                ->password()
                                ->hidden(fn(Get $get) => !$get('change_password'))
                                ->required()->columnSpan(2),

                            TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->hidden(fn(Get $get) => !$get('change_password'))
                                ->minLength(8)
                                ->same('password_confirmation')
                                ->dehydrated(fn($state) => filled($state)),

                            TextInput::make('password_confirmation')
                                ->label('Confirm Password')
                                ->password()
                                ->hidden(fn(Get $get) => !$get('change_password'))
                                ->dehydrated(false),

                        ])->columns(2)->columnSpan(1)
                        ->heading('Profile Settings')


                ])->columns(2)

        ];
    }

    public function save()
    {
        $user = Auth::user();

        $user->name = $this->name;
        $user->email = $this->email;

        $updates = [];

        if ($this->password) {

            if (!Hash::check($this->current_password, Auth::user()->password)) {
                dd('Current password is incorrect');
            }

            $updates['password'] = Hash::make($this->password);
        }

        $updates['name'] = $this->name;
        $updates['email'] = $this->email;



        User::where('id', $user->id)->update($updates);

        Notification::make()
            ->title('Account updated successfully')
            ->body('Your profile has been updated successfully.')
            ->color("success")
            ->success()
            ->send();
    }




    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
            ])
            ->filters([
                //
            ]);
    }
}
