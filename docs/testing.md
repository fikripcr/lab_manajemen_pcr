# Testing & Quality Assurance

## Overview

This section covers the testing strategies, methodologies, and tools used in the system to ensure code quality, functionality, and reliability. The testing approach includes unit tests, feature tests, integration tests, and debugging tools.

## Testing Philosophy

The system follows a comprehensive testing approach:

- **Unit Tests**: Test individual components and functions in isolation
- **Feature Tests**: Test user flows and system integration
- **Integration Tests**: Test interactions between multiple components
- **API Tests**: Test API endpoints and responses
- **Browser Tests**: Test UI components and user interactions (if applicable)

## PHPUnit Configuration

The system uses PHPUnit for testing, configured in `phpunit.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         processIsolation="false"
         stopOnFailure="false"
         beStrictAboutTestsThatDoNotTestAnything="false"
         beStrictAboutOutputDuringTests="true"
         beStrictAboutTodoAnnotatedTests="true"
         verbose="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </coverage>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

## Unit Testing

Unit tests focus on individual functions and methods in isolation.

### Example Unit Test for Helper Functions

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelperFunctionTest extends TestCase
{
    public function test_format_tanggal_indo_works_correctly()
    {
        $inputDate = '2023-05-15';
        $result = formatTanggalIndo($inputDate);
        
        $this->assertEquals('15 Mei 2023', $result);
    }
    
    public function test_encrypt_decrypt_id_roundtrip()
    {
        $originalId = 123;
        $encrypted = encryptId($originalId);
        $decrypted = decryptId($encrypted);
        
        $this->assertEquals($originalId, $decrypted);
    }
    
    public function test_format_rupiah_works_correctly()
    {
        $amount = 1000000;
        $result = formatRupiah($amount);
        
        $this->assertEquals('Rp 1.000.000,00', $result);
    }
}
```

### Example Unit Test for Business Logic

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BillingService;
use App\Models\User;

class BillingServiceTest extends TestCase
{
    public function test_calculate_prorated_amount()
    {
        $billingService = new BillingService();
        
        // Monthly rate is 100 for a 30-day period
        // User joins on day 15, so 15 days remain = 50% of monthly rate
        $result = $billingService->calculateProratedAmount(100, '2023-01-15', '2023-01-31');
        
        $this->assertEquals(50, $result);
    }
    
    public function test_calculate_prorated_amount_with_different_periods()
    {
        $billingService = new BillingService();
        
        // Monthly rate is 100 for a 31-day period (January)
        // User joins on day 1, so 31 days remain = 100% of monthly rate
        $result = $billingService->calculateProratedAmount(100, '2023-01-01', '2023-01-31');
        
        $this->assertEquals(100, $result);
    }
}
```

## Feature Testing

Feature tests examine user flows and system integration.

### Example Feature Test for User Registration

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }
    
    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->post('/register', [
            'name' => '', // Invalid: empty name
            'email' => 'invalid-email', // Invalid: not an email
            'password' => '123', // Invalid: too short
            'password_confirmation' => 'different', // Invalid: doesn't match
        ]);
        
        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email'
        ]);
    }
}
```

### Example Feature Test for Authentication

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_protected_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get('/dashboard');
        
        $response->assertStatus(200);
    }
    
    public function test_guest_cannot_access_protected_page()
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }
    
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/logout');
        
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
```

### Example Feature Test for CRUD Operations

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user with necessary permissions
        $this->adminUser = User::factory()->create();
        $adminRole = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'manage-users']);
        
        $this->adminUser->assignRole('admin');
        $this->adminUser->givePermissionTo('manage-users');
    }

    public function test_admin_can_view_users_list()
    {
        // Create test users
        User::factory()->count(3)->create();
        
        $response = $this->actingAs($this->adminUser)
                         ->get('/admin/users');
        
        $response->assertStatus(200)
                 ->assertSee('Users List');
    }
    
    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->adminUser)
                         ->post('/admin/users', [
                             'name' => 'New User',
                             'email' => 'newuser@example.com',
                             'password' => 'password123',
                             'status' => 'active',
                         ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com'
        ]);
    }
    
    public function test_admin_can_update_user()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($this->adminUser)
                         ->put("/admin/users/{$user->encrypted_id}", [
                             'name' => 'Updated Name',
                             'email' => $user->email,
                             'status' => 'inactive',
                         ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'status' => 'inactive',
        ]);
    }
    
    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($this->adminUser)
                         ->delete("/admin/users/{$user->encrypted_id}");
        
        $response->assertRedirect();
        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }
}
```

## Database Testing

### RefreshDatabase Trait

The `RefreshDatabase` trait is used to reset the database between tests:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        // Database is fresh for each test
    }
}
```

### Database Transactions

For faster tests without full database refresh:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_example()
    {
        // Database changes are rolled back after each test
    }
}
```

### Factory Usage

Use Laravel factories for creating test data:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status' => 'active',
        ];
    }
    
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
    
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'inactive',
            ];
        });
    }
}
```

## API Testing

### Example API Test

```php
<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_users_list()
    {
        User::factory()->count(3)->create();
        
        $response = $this->getJson('/api/users');
        
        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }
    
    public function test_api_can_create_user()
    {
        $userData = [
            'name' => 'API User',
            'email' => 'apiuser@example.com',
            'password' => 'password123',
        ];
        
        $response = $this->postJson('/api/users', $userData);
        
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'email' => 'apiuser@example.com'
                 ]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'apiuser@example.com'
        ]);
    }
    
    public function test_api_returns_error_for_invalid_data()
    {
        $response = $this->postJson('/api/users', [
            'name' => '', // Invalid: empty name
            'email' => 'invalid-email', // Invalid: not an email
        ]);
        
        $response->assertStatus(422)
                 ->assertJson([
                     'message' => 'The name field is required.'
                 ]);
    }
}
```

## Testing Authentication

### Acting As User

```php
public function test_authenticated_user_can_access_profile()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
                     ->get('/profile');
    
    $response->assertStatus(200);
}
```

### Testing with Different Roles

```php
public function test_admin_can_access_admin_panel()
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    
    $response = $this->actingAs($admin)
                     ->get('/admin/dashboard');
    
    $response->assertStatus(200);
}

public function test_regular_user_cannot_access_admin_panel()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
                     ->get('/admin/dashboard');
    
    $response->assertStatus(403); // Forbidden
}
```

## Testing Authorization

### Testing Permissions

```php
public function test_user_with_permission_can_manage_users()
{
    $user = User::factory()->create();
    $user->givePermissionTo('manage-users');
    
    $response = $this->actingAs($user)
                     ->get('/admin/users');
    
    $response->assertStatus(200);
}

public function test_user_without_permission_cannot_manage_users()
{
    $user = User::factory()->create();
    // No permissions assigned
    
    $response = $this->actingAs($user)
                     ->get('/admin/users');
    
    $response->assertStatus(403);
}
```

## Testing File Uploads

### Example File Upload Test

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_profile_picture()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $response = $this->actingAs($user)
                         ->post('/profile/upload-avatar', [
                             'avatar' => $file
                         ]);
        
        $response->assertStatus(302);
        
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }
}
```

## Testing with Custom Assertions

### Creating Custom Assertions

```php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertUserHasRole($user, $role)
    {
        $this->assertTrue(
            $user->hasRole($role),
            "Failed asserting that user has role '{$role}'."
        );
    }
    
    protected function assertNotificationSent($user, $notificationClass)
    {
        $this->assertDatabaseHas('sys_notifications', [
            'user_id' => $user->id,
            'type' => $notificationClass,
        ]);
    }
}
```

## Test Coverage

### Running Tests with Coverage

```bash
# Run tests with coverage report
php artisan test --coverage

# Generate HTML coverage report
php artisan test --coverage-html=tests/coverage

# Generate coverage report in different formats
php artisan test --coverage-clover=tests/coverage/clover.xml
```

### Analyzing Coverage

- Aim for high test coverage but focus on testing critical paths
- Don't just aim for numbers; ensure meaningful tests
- Pay attention to branches and paths in complex logic

## Debugging Tools

### Laravel Debugbar

The system includes Laravel Debugbar for development debugging:

```php
// Enable in .env for local development
APP_DEBUG=true

// Use in controllers for debugging
\Debugbar::info('Processing user data:', $user->toArray());
\Debugbar::error('Something went wrong');
\Debugbar::addMessage('Custom message', 'label');
```

### Logging for Debugging

```php
use Illuminate\Support\Facades\Log;

// Log messages at different levels
Log::info('User logged in', ['user_id' => $user->id]);
Log::warning('Something unusual happened');
Log::error('An error occurred', ['exception' => $e]);
```

### dd() and dump() Functions

```php
// In controllers or anywhere in your code
dd($variable); // Dumps and dies
dump($variable); // Dumps without dying
```

## Testing Best Practices

### Test Naming Conventions

- Use descriptive names that clearly state what is being tested
- Follow the pattern: `test_[scenario]_[expected_result]`

```php
public function test_user_can_register_with_valid_data()
{
    // Implementation
}

public function test_user_cannot_login_with_invalid_credentials()
{
    // Implementation
}

public function test_admin_can_delete_user()
{
    // Implementation
}
```

### Test Structure (AAA Pattern)

- **Arrange**: Set up test data and dependencies
- **Act**: Execute the action being tested
- **Assert**: Verify the expected outcome

```php
public function test_user_can_update_profile()
{
    // Arrange
    $user = User::factory()->create();
    $newData = ['name' => 'Updated Name', 'email' => 'updated@example.com'];
    
    // Act
    $response = $this->actingAs($user)
                     ->put('/profile', $newData);
    
    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name'
    ]);
}
```

### Test Isolation

- Keep tests independent from each other
- Don't rely on test execution order
- Clean up after tests if necessary

### Performance Considerations

- Use in-memory SQLite for tests when possible
- Use `RefreshDatabase` instead of `DatabaseMigrations` for better performance
- Use `withoutExceptionHandling()` only when needed
- Group related tests in the same class

### Testing Protected Routes

```php
public function test_protected_route_requires_authentication()
{
    $response = $this->get('/protected-route');
    
    $response->assertRedirect('/login');
}

public function test_authenticated_user_can_access_protected_route()
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
                     ->get('/protected-route');
    
    $response->assertStatus(200);
}
```

## Continuous Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    strategy:
      fail-fast: false
      matrix:
        php: [8.2, 8.3]
        laravel: [12.*]
        include:
          - laravel: 12.*
            testbench: 9.*

    name: P${{ matrix.php }} - L${{ matrix.laravel }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, bcmath, soap, intl, gd, exif, iconv, imagick
          coverage: xdebug

      - name: Install dependencies
        run: |
          composer require "laravel/framework:${{ matrix.laravel }}" --dev --no-interaction --no-update
          composer update --${{ matrix.stability }} --prefer-dist --no-interaction

      - name: Execute tests
        run: vendor/bin/phpunit
```

Following these testing and quality assurance practices helps ensure a robust, reliable, and maintainable application that meets its requirements and functions correctly in various scenarios.