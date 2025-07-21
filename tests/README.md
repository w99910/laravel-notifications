# Notification Package Test Suite

This test suite provides comprehensive coverage for the Laravel Notification package using Pest PHP v1 testing framework.

## Test Structure

### Feature Tests

Feature tests cover the main functionality of the notification system:

- **NotificationServiceTest.php** - Tests the main service class constructor and WebSocket functionality
- **CreateNotificationTest.php** - Tests notification creation with validation
- **SendNotificationTest.php** - Tests notification sending and event dispatching
- **GetNotificationsTest.php** - Tests notification retrieval and filtering
- **ReadNotificationTest.php** - Tests marking notifications as read
- **UpdateNotificationTest.php** - Tests notification updates and progress tracking
- **DeleteNotificationTest.php** - Tests notification deletion
- **IntegrationTest.php** - Tests complete notification lifecycle and edge cases

### Unit Tests

Unit tests focus on individual components:

- **NotificationEventTest.php** - Tests the notification event class
- **NotificationModelTest.php** - Tests the notification model
- **ValidatorTest.php** - Tests data validation

### Example Tests

- **ExampleTest.php** - Example tests showing basic usage patterns

## Test Coverage

### NotificationService

- ✅ Constructor with different parameter types
- ✅ WebSocket channel generation
- ✅ Notification creation with validation
- ✅ Notification sending with event dispatching
- ✅ Notification retrieval with filtering
- ✅ Notification updates and progress tracking
- ✅ Notification deletion
- ✅ Read status management

### Events

- ✅ NotificationEvent broadcasting
- ✅ WebSocket channel generation
- ✅ Event data serialization

### Models

- ✅ Notification model interface compliance
- ✅ Fillable attributes
- ✅ Getter methods
- ✅ Data type handling

### Validation

- ✅ Required field validation
- ✅ Data type validation
- ✅ Range validation (priority, progress)
- ✅ Enum validation (type, order)
- ✅ Error handling

## Running Tests

### Run All Tests

```bash
./vendor/bin/pest
```

### Run Specific Test Files

```bash
./vendor/bin/pest tests/Feature/CreateNotificationTest.php
./vendor/bin/pest tests/Unit/NotificationModelTest.php
```

### Run Tests with Coverage

```bash
./vendor/bin/pest --coverage
```

## Pest v1 Syntax

### Test Functions

Since Pest v1 doesn't have `describe()` and `it()` functions, all tests use the `test()` function:

```php
test('creates notification with valid data', function () {
    // Test implementation
});
```

### Setup

Common setup is done within each test function since `beforeEach()` is not available in grouped contexts:

```php
test('my test', function () {
    Event::fake();
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // Test implementation
});
```

### Fluent Assertions

Tests use Pest's fluent assertion syntax:

```php
expect($result)
    ->toBeInstanceOf(NotificationInterface::class)
    ->and($result->title)->toBe('Test Title')
    ->and($result->type)->toBe('info');
```

### Event Testing

Events are tested using Laravel's Event facade:

```php
Event::fake();
$service->sendNotification($notification);
Event::assertDispatched(NotificationEvent::class);
```

### Exception Testing

Exception handling is tested using closures:

```php
expect(fn() => $service->createNotification($invalidData))
    ->toThrow(\Exception::class);
```

## Database Testing Considerations

Many tests are marked as skipped with `->skip('Requires database mock')` because they would require:

- Database setup and migration
- Model factory setup
- Proper Laravel testing environment

To enable these tests:

1. Set up a testing database
2. Run migrations
3. Create model factories
4. Remove the `->skip()` calls

## Mock Usage

The test suite uses minimal mocking:

- `Event::fake()` for event testing
- `Log::fake()` for logging tests
- Simple `User` model for testing without database

## Test Organization

Since Pest v1 doesn't support `describe()` blocks, tests are organized by:

1. **Clear test names** - Each test clearly describes what it's testing
2. **File organization** - Related tests are grouped in the same file
3. **Prefixed test names** - Tests use descriptive prefixes like "validation fails when..."

## Best Practices Demonstrated

1. **Descriptive Test Names** - Each test clearly describes what it's testing
2. **Arrange-Act-Assert** - Tests follow the AAA pattern
3. **Single Responsibility** - Each test focuses on one specific behavior
4. **Edge Case Testing** - Tests cover boundary conditions and error cases
5. **Integration Testing** - Tests verify complete workflows work together

## Differences from Pest v2

- No `describe()` or `it()` functions
- No `beforeEach()` in grouped contexts
- Setup must be done within each test function
- All tests use the `test()` function

## Adding New Tests

When adding new functionality:

1. Create feature tests for the main functionality using `test()` function
2. Add unit tests for individual components
3. Include validation tests for any new rules
4. Add integration tests for complete workflows
5. Test error conditions and edge cases
6. Use descriptive test names since grouping isn't available

## Maintenance

- Keep tests updated when adding new features
- Ensure tests remain independent and can run in any order
- Regular review of test coverage
- Update mocks when underlying implementations change
- Consider upgrading to Pest v2 for better test organization in the future
