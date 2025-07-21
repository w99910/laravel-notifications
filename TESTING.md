# Laravel Notification Package Tests

This document describes the comprehensive test suite for the Laravel Notification package, compatible with Pest 2 and 3.

## Test Coverage

The test suite covers the following areas:

### Unit Tests

1. **Models Tests** (`tests/Unit/Models/`)

   - `NotificationTest.php` - Tests for the Notification model
   - Covers all model methods and attributes
   - Tests data type conversions and null handling

2. **Services Tests** (`tests/Unit/Services/`)

   - `NotificationServiceTest.php` - Tests for the NotificationService
   - Tests service initialization with different user types
   - Tests WebSocket channel generation
   - Tests service configuration

3. **Events Tests** (`tests/Unit/Events/`)

   - `NotificationEventTest.php` - Tests for the NotificationEvent
   - Tests event creation and broadcasting
   - Tests channel configuration
   - Tests custom event names

4. **Support Tests** (`tests/Unit/Support/`)
   - `ValidatorTest.php` - Tests for the Validator class
   - Tests validation success and failure scenarios
   - Tests custom error messages
   - Tests data filtering

### Feature Tests

1. **Controllers Tests** (`tests/Feature/Controllers/`)

   - `NotificationControllerTest.php` - Tests for the NotificationController
   - Tests all CRUD endpoints
   - Tests authentication requirements
   - Tests request/response handling

2. **Broadcasting Tests** (`tests/Feature/Broadcasting/`)

   - `NotificationBroadcastingTest.php` - Tests for event broadcasting
   - Tests mocked broadcasting scenarios
   - Tests different notification types
   - Tests event serialization

3. **Integration Tests** (`tests/Feature/Integration/`)
   - `NotificationCRUDTest.php` - Complete CRUD integration tests
   - Tests end-to-end notification workflows
   - Tests user isolation
   - Tests data persistence

## CRUD Operations Tested

### Create

- ✅ Create notifications with all required fields
- ✅ Create notifications with optional fields
- ✅ Create notifications with default values
- ✅ Validate input data
- ✅ Handle validation errors

### Read

- ✅ Get all notifications for a user
- ✅ Get notifications with filters (type, read status, etc.)
- ✅ Get unread notifications
- ✅ Get read notifications
- ✅ Get notification count
- ✅ Pagination support
- ✅ Sorting support

### Update

- ✅ Mark notification as read
- ✅ Mark all notifications as read
- ✅ Update notification progress
- ✅ Update notification status

### Delete

- ✅ Delete single notification
- ✅ Delete all notifications for a user
- ✅ User isolation (users can't access other users' notifications)

## Broadcasting Tests

### Mock Broadcasting

- ✅ Test event dispatch without actual broadcasting
- ✅ Test event serialization
- ✅ Test channel authorization
- ✅ Test custom event names
- ✅ Test different notification types

### WebSocket Channel Tests

- ✅ Channel name generation
- ✅ User-specific channels
- ✅ Encrypted channel names
- ✅ Channel authorization callbacks

## Running Tests

### Prerequisites

- PHP 8.1+
- Laravel 9.0+
- Pest 2.0+ or 3.0+

### Run All Tests

```bash
vendor/bin/pest
```

### Run Specific Test Suites

```bash
# Run only unit tests
vendor/bin/pest tests/Unit

# Run only feature tests
vendor/bin/pest tests/Feature

# Run specific test file
vendor/bin/pest tests/Unit/Models/NotificationTest.php
```

### Run Tests with Coverage

```bash
vendor/bin/pest --coverage
```

### Run Tests in Parallel

```bash
vendor/bin/pest --parallel
```

### Run Tests with Specific Filters

```bash
# Run tests matching a pattern
vendor/bin/pest --filter="can create"

# Run tests in specific group
vendor/bin/pest --group=crud
```

## Test Database

The tests use SQLite in-memory database for speed and isolation. The database is automatically created and destroyed for each test run.

## Mocking and Faking

### Event Mocking

```php
Event::fake([NotificationEvent::class]);
// ... test code
Event::assertDispatched(NotificationEvent::class);
```

### Queue Mocking

```php
Queue::fake();
// ... test code
Queue::assertPushed(NotificationEvent::class);
```

### Broadcast Mocking

```php
Broadcast::shouldReceive('channel')->once();
```

## Test Environment Setup

The tests automatically:

- Set up test database
- Load package service provider
- Configure authentication
- Set up broadcasting channels
- Handle migrations

## Continuous Integration

The package includes GitHub Actions workflow for:

- Testing across multiple PHP versions (8.1, 8.2, 8.3)
- Testing across multiple Laravel versions (9._, 10._, 11.\*)
- Code coverage reporting
- Static analysis with PHPStan
- Code style checking with PHP CS Fixer
- Security vulnerability scanning

## Test Data Factories

The tests use inline data creation for better test isolation and readability. Each test creates its own test data to avoid dependencies between tests.

## Assertion Examples

### Model Assertions

```php
expect($notification)->toBeInstanceOf(Notification::class);
expect($notification->getTitle())->toBe('Test Title');
expect($notification->getReadAt())->toBeNull();
```

### Service Assertions

```php
expect($result)->toBeTrue();
expect($notifications)->toHaveCount(2);
expect($channel)->toContain('users.');
```

### Event Assertions

```php
Event::assertDispatched(NotificationEvent::class, function ($event) {
    return $event->notification->id === $notification->id;
});
```

### HTTP Assertions

```php
$response->assertStatus(200);
$response->assertJsonStructure(['data', 'meta']);
```

## Debugging Tests

### Verbose Output

```bash
vendor/bin/pest --verbose
```

### Stop on Failure

```bash
vendor/bin/pest --stop-on-failure
```

### Debug Specific Test

```php
it('can debug test', function () {
    dump($data); // Use dump() for debugging
    expect($data)->toBeArray();
});
```

## Best Practices

1. **Test Isolation**: Each test is independent and doesn't rely on other tests
2. **Descriptive Names**: Test names clearly describe what is being tested
3. **Arrange-Act-Assert**: Tests follow the AAA pattern
4. **Mock External Dependencies**: Broadcasting, queues, and external services are mocked
5. **Test Edge Cases**: Tests cover both success and failure scenarios
6. **Use Factories**: Create test data consistently
7. **Clean Up**: Tests clean up after themselves automatically
