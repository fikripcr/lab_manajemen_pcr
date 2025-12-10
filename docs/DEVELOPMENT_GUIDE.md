# Development Guide

Core patterns dan best practices untuk development.

## Service Pattern

**Rule:** Business logic di Service, bukan di Controller.

### Flow
```
Request → Controller → Service → Model → Database
```

### Example

**Controller** (thin - hanya handle HTTP):
```php
// app/Http/Controllers/Sys/RoleController.php
public function store(RoleRequest $request)
{
    try {
        $data = $request->validated();
        $data['permissions'] = $request->input('permissions', []);
        
        $this->roleService->createRole($data);  // Call service
        
        return redirect()->route('sys.roles.index')
            ->with('success', 'Role created');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', $e->getMessage());
    }
}
```

**Service** (thick - semua business logic):
```php
// app/Services/Sys/RoleService.php
public function createRole(array $data): Role
{
    return DB::transaction(function() use ($data) {
        $role = Role::create(['name' => $data['name']]);
        
        if (!empty($data['permissions'])) {
            $role->givePermissionTo($data['permissions']);
        }
        
        logActivity('role_management', "Created role: {$role->name}");
        
        return $role;
    });
}
```

**Why?**
- ✅ Service reusable (UI, API, CLI, Export)
- ✅ Easy to test
- ✅ Business logic centralized

## CRUD Pattern

### Standard Resource Controller

```php
class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
        $this->middleware('can:view users')->only(['index', 'show']);
        $this->middleware('can:create users')->only(['create', 'store']);
        $this->middleware('can:edit users')->only(['edit', 'update']);
        $this->middleware('can:delete users')->only(['destroy']);
    }

    public function index()
    {
        return view('pages.admin.users.index');
    }

    public function store(CreateUserRequest $request)
    {
        $this->userService->createUser($request->validated());
        return redirect()->route('admin.users.index');
    }
}
```

### DataTables Pagination

```php
public function paginate(Request $request)
{
    return DataTables::of($this->userService->getFilteredQuery($request->all()))
        ->addColumn('action', function ($user) {
            return view('components.action-buttons', compact('user'));
        })
        ->make(true);
}
```

## Authorization

### Route Level
```php
Route::resource('users', UserController::class)
    ->middleware('permission:manage users');
```

### Controller Constructor (Recommended)
```php
public function __construct()
{
    $this->middleware('can:view users')->only(['index', 'show']);
    $this->middleware('can:create users')->only(['create', 'store']);
}
```

### Blade Templates
```blade
@can('edit users')
    <a href="{{ route('admin.users.edit', $user->encrypted_id) }}">Edit</a>
@endcan
```

## Database Best Practices

### N+1 Query Prevention
```php
// ❌ Bad - N+1 queries
$users = User::all();
foreach($users as $user) {
    echo $user->profile->name;  // Extra query per user!
}

// ✅ Good - Eager loading
$users = User::with('profile')->get();
foreach($users as $user) {
    echo $user->profile->name;  // No extra queries
}
```

### Transactions
```php
DB::transaction(function() {
    $user = User::create($data);
    $user->profile()->create($profileData);
    $user->assignRole('admin');
});
```

## Helper Functions

### Sys Helpers (`app/Helpers/Sys.php`)
**⚠️ DO NOT MODIFY** - Core system functions

```php
encryptId($id)           // Encrypt ID for URLs
decryptId($encrypted)    // Decrypt ID from URLs
logActivity($type, $msg) // Log user activity
```

### Global Helpers (`app/Helpers/Global.php`)
**✅ Safe to modify** - Custom business logic

```php
formatTanggalIndo($date)        // Format date to Indonesian
formatTanggalWaktuIndo($date)   // Format datetime to Indonesian
```

## Form Validation

### Form Request Classes
```php
// app/Http/Requests/CreateUserRequest.php
class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create users');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
```

## Common Patterns

### ID Encryption in URLs
```php
// In Model
public function getEncryptedIdAttribute()
{
    return encryptId($this->id);
}

// In Route
Route::get('/users/{id}', [UserController::class, 'show']);

// In Controller
public function show($encryptedId)
{
    $id = decryptId($encryptedId);
    $user = User::findOrFail($id);
}
```

### Soft Deletes
```php
// In Migration
$table->softDeletes();

// In Model
use SoftDeletes;

// Usage
$user->delete();           // Soft delete
$user->forceDelete();      // Permanent delete
$user->restore();          // Restore
User::withTrashed()->get(); // Include deleted
```

### Activity Logging
```php
logActivity('user_management', 'Created user: ' . $user->name);
```

## Code Quality

### Naming Conventions
```php
// Variables
$userList (not $data)
$isExpired (not $status)

// Functions
calculateTotalRevenue()
uploadProfileImage()

// Classes
UserController
UserService
CreateUserRequest
```

### Documentation
```php
/**
 * Create a new user with profile
 *
 * @param array $data User data
 * @return User Created user instance
 */
public function createUser(array $data): User
{
    // Implementation
}
```

## Performance Tips

```bash
# Cache config/routes (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache (development)
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Quick Reference

**Find Examples:**
- Controllers: `app/Http/Controllers/Sys/RoleController.php`
- Services: `app/Services/Sys/RoleService.php`
- Requests: `app/Http/Requests/Sys/RoleRequest.php`
- Views: `resources/views/pages/sys/roles/`

**Common Issues:**
- Permission denied → Check `can:permission-name` middleware
- N+1 queries → Add `with()` eager loading
- Validation errors → Check FormRequest rules
