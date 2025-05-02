<?php

namespace App\Filament\Pages;

// Base Filament Page class for guest routes
use Filament\Pages\SimplePage;

// Form Components & Contracts
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

// Action Components & Contracts
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

// Models
use App\Models\Tenant;
use App\Models\TenantAdmin;

// Laravel Facades & Helpers
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

// Notifications
use Filament\Notifications\Notification;

//Log
use Illuminate\Support\Facades\Log;


// Implement HasForms and HasActions
class RegisterTenant extends SimplePage implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions; // Add Action trait

    // Set the view for this page
    protected static string $view = 'filament.pages.register-tenant';

    // We don't want this page to require authentication or show in navigation
    protected static bool $shouldRegisterNavigation = false;
    protected static bool $requiresTenant = false;

    // Form data properties
    public ?array $data = [];

    public function mount(): void
    {
        // Initialize the form with empty data or defaults
        $this->form->fill();
    }

    // Define the registration form (remains the same)
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Organization Details')
                    ->schema([
                        TextInput::make('tenant_name')
                            ->label('Organization/Museum Name')
                            ->required()->maxLength(255)->live(debounce: 500)
                            ->afterStateUpdated(fn ($set, $state) => $set('tenant_slug', Str::slug($state))),
                        TextInput::make('tenant_slug')
                            ->label('URL Slug (e.g., your-museum-name)')
                            ->required()->maxLength(255)->unique(table: Tenant::class, column: 'slug')
                            ->helperText('This will be part of the URL. Use lowercase letters, numbers, and hyphens.'),
                        TextInput::make('tenant_email')
                            ->label('Organization Contact Email')
                            ->email()->required()->maxLength(255)->unique(table: Tenant::class, column: 'email'),
                    ]),
                Section::make('Administrator Details')
                    ->schema([
                        TextInput::make('admin_name')
                            ->label('Your Full Name')
                            ->required()->maxLength(255),
                        TextInput::make('admin_email')
                            ->label('Your Login Email')
                            ->email()->required()->maxLength(255)->unique(table: TenantAdmin::class, column: 'email'),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()->required()->minLength(8)->confirmed(),
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()->required(),
                    ])
            ])
            ->statePath('data');
    }

    // Define the Filament Action for registration (remains the same)
    public function registerAction(): Action
    {
        return Action::make('register')
            ->label('Register')
            ->submit('register');
    }

    // --- CORRECTED Register Method for SHARED DATABASE ---
        // Method to handle the registration logic
        public function register(): void
        {
            // Validate the form data (throws ValidationException on failure)
            $formData = $this->form->getState();
    
            $admin = null; // Initialize admin variable outside the transaction scope
    
            try {
                // Use a database transaction for atomicity OF DATABASE OPERATIONS ONLY
                DB::transaction(function () use ($formData, &$admin) { // Pass $admin by reference
                    // 1. Create the Tenant
                    $tenant = Tenant::create([
                        'name' => $formData['tenant_name'],
                        'slug' => $formData['tenant_slug'],
                        'email' => $formData['tenant_email'],
                        // ID is auto-generated
                    ]);
                    Log::debug('Tenant created', ['tenant_id' => $tenant->id]); // Use debug for finer logging
    
                    // 2. Create the Tenant Admin and assign it to the outer scope variable
                    $admin = TenantAdmin::create([
                        'tenant_id' => $tenant->id,
                        'name' => $formData['admin_name'],
                        'email' => $formData['admin_email'],
                        'password' => Hash::make($formData['password']),
                        'email_verified_at' => now(), // Consider verification flow later
                        // ID is auto-generated
                    ]);
                    Log::debug('TenantAdmin created', ['admin_id' => $admin->id]); // Use debug
    
                     // Log success *within* transaction if needed
                     Log::info('Tenant and Admin created successfully within transaction.', ['tenant_id' => $tenant->id, 'admin_id' => $admin->id]);
    
                    // DO NOT LOGIN OR REDIRECT INSIDE THE TRANSACTION
                }); // <-- Transaction ends here
    
                // --- Actions AFTER successful transaction ---
    
                // Check if admin was successfully created
                if (!$admin) {
                    throw new \Exception('Admin user creation failed unexpectedly after transaction.');
                }
    
                // 3. Log in the new admin user using the correct guard
                Auth::guard('tenant_admin')->login($admin); // <-- UNCOMMENT THIS LINE
                Log::info('User logged in successfully after transaction.', ['admin_id' => $admin->id]);
    
                // 4. Redirect to the Filament dashboard for the correct panel
                $panel = Filament::getPanel('admin'); // Get panel by ID
                $url = $panel->getUrl();
                Log::info('Redirecting user to dashboard.', ['url' => $panel->getUrl()]);
    
                redirect()->intended($panel->getUrl()); // <-- UNCOMMENT THIS LINE
                // NOTE: Code execution stops here on successful redirect.
    
            } catch (\Illuminate\Validation\ValidationException $e) {
                 // ... (keep existing validation catch block) ...
                 Notification::make()
                    ->title('Validation Failed')
                    ->body('Please check the form for errors.')
                    ->danger()
                    ->send();
                Log::warning('Tenant Registration Validation Failed: ', $e->errors());
    
            } catch (\Exception $e) {
                 // ... (keep existing generic exception catch block) ...
                 Notification::make()
                    ->title('Registration Failed')
                    ->body('An unexpected error occurred. Please try again or contact support.')
                    ->danger()
                    ->send();
                Log::error('Tenant Registration Failed: ' . $e->getMessage(), ['exception' => $e]);
            }
        }
}