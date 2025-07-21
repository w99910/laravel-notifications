## Why Another Notification Package?

Laravel built-in notifications are great, but they can be a bit cumbersome for more complex use cases.

The big drawback is that you cannot update the Laravel notification except the `read` status. This means that if you want to update the content of a notification, you have to create a new one and delete the old one. This can lead to a lot of unnecessary notifications cluttering up your system.

This package uses Laravel built-in notification system but only for broadcasting. To store and update the notification, it uses custom Model and service. It aims to provide a more flexible and powerful notification system that integrates seamlessly with Laravel's existing features.

This library comes with frontend support for Vue.js where you can easily display notifications in your application. It also includes a backend service that allows you to manage notifications efficiently. It automatically registers the routes and provides a simple API for creating, updating, and deleting notifications.
