# Notification Package

A Laravel package for handling notifications across multiple Laravel versions.

## Compatibility

This package is compatible with:

-   **Laravel:** 8.x, 9.x, 10.x, 11.x, 12.x
-   **PHP:** 8.0, 8.1, 8.2, 8.3

### Laravel & PHP Version Matrix

| Laravel Version | PHP Version   | Testbench Version |
| --------------- | ------------- | ----------------- |
| 8.x             | 8.0, 8.1      | 6.x               |
| 9.x             | 8.0, 8.1, 8.2 | 7.x               |
| 10.x            | 8.1, 8.2, 8.3 | 8.x               |
| 11.x            | 8.2, 8.3      | 9.x               |
| 12.x            | 8.2, 8.3      | 10.x              |

The reason why I use the **ProtoBuf** format is that

-   language-agnostic
-   efficient serialization
-   widely supported across different programming languages
-   good performance for both size and speed
-   easy to define and evolve data structures
-   strong typing and schema validation
-   supports backward and forward compatibility

## Installation

```bash
composer require thomasbrillion/notification
```

## Testing

Run tests with:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

# Workflow

-
