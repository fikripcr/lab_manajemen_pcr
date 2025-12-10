# Notification API Documentation

## Overview
This document describes the API endpoints for managing user notifications in the system. The API uses Laravel Sanctum for authentication and follows RESTful design principles.

## Base URL
```
https://your-domain.com/api
```

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the authentication token in the request headers:
```
Authorization: Bearer {your_sanctum_token}
```
Or use Laravel's session-based authentication for in-app requests.

## Endpoints

### 1. Get Notification Count
Returns the count of unread notifications for the authenticated user.

- **Method**: `GET`
- **URL**: `/api/notifications/count`
- **Headers**:
  - `Authorization: Bearer {your_token}` (for Sanctum)
  - OR use session authentication (for in-app requests)
  - `Accept: application/json`
- **Parameters**: None
- **Response**:
```json
{
    "count": 5
}
```

### 2. Get Notification List
Returns a paginated list of notifications for the authenticated user.

- **Method**: `GET`
- **URL**: `/api/notifications/list`
- **Headers**:
  - `Authorization: Bearer {your_token}` (for Sanctum)
  - OR use session authentication (for in-app requests)
  - `Accept: application/json`
- **Query Parameters**:
  - `read_status` (optional): Filter by read status (`read`, `unread`, or omit for all)
  - `per_page` (optional): Number of notifications per page (default: 10)
  - `page` (optional): Page number (default: 1)
- **Response**:
```json
{
    "data": [
        {
            "id": "notification-id-1",
            "type": "App\\Notifications\\ExampleNotification",
            "data": {
                "title": "Notification Title",
                "body": "Notification body content"
            },
            "read_at": null,
            "created_at": "2024-11-23T10:30:00.000000Z",
            "updated_at": "2024-11-23T10:30:00.000000Z",
            "is_read": false,
            "formatted_date": "2 hours ago"
        }
    ],
    "links": {
        "first": "https://your-domain.com/api/notifications/list?page=1",
        "last": "https://your-domain.com/api/notifications/list?page=3",
        "prev": null,
        "next": "https://your-domain.com/api/notifications/list?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "path": "https://your-domain.com/api/notifications/list",
        "per_page": 10,
        "to": 10,
        "total": 25
    }
}
```

## Example Usage

### Get Notification Count (Using Axios with Sanctum Token)
```javascript
// Using Sanctum token
axios.get('/api/notifications/count', {
    headers: {
        'Authorization': 'Bearer ' + sanctumToken,
        'Accept': 'application/json'
    }
})
.then(response => {
    console.log('Unread notification count:', response.data.count);
})
.catch(error => {
    console.error('Error fetching count:', error);
});
```

### Get Notification Count (Using Axios with Session Authentication - In-app)
```javascript
// Using session authentication (for in-app calls)
axios.get('/api/notifications/count')
    .then(response => {
        console.log('Unread notification count:', response.data.count);
    })
    .catch(error => {
        console.error('Error fetching count:', error);
    });
```

### Get Notification List (Using Axios)
```javascript
axios.get('/api/notifications/list', {
    params: {
        read_status: 'unread',  // Optional: 'read', 'unread', or omit for all
        per_page: 5             // Optional: number of items per page
    }
})
.then(response => {
    console.log('Notifications:', response.data.data);
    console.log('Total notifications:', response.data.meta.total);
})
.catch(error => {
    console.error('Error fetching list:', error);
});
```

## Using in Application Headers

### In Header Component (Using Axios)
```javascript
// Load notification count
function loadNotificationCount() {
    axios.get('/api/notifications/count')
        .then(response => {
            document.getElementById('notification-count').textContent = response.data.count;
        })
        .catch(error => {
            console.error('Error loading notification count:', error);
        });
}

// Load notifications list
function loadNotifications() {
    axios.get('/api/notifications/list', {
        params: {
            per_page: 5
        }
    })
        .then(response => {
            // Process response.data.data to update UI
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
        });
}
```

## Testing the API

To test the Notification API endpoints, navigate to "Test Features" in the sidebar, then click on the "Test Notification API" card. This provides an interface to test both the count and list endpoints.

The API test interface allows you to:
- Test the count endpoint to get unread notification count
- Test the list endpoint to retrieve notification data
- See the raw JSON response from the API

## Error Responses

In case of an error, the API returns appropriate HTTP status codes and error details in the response:

```json
{
    "message": "Unauthenticated.",
    "exception": "Illuminate\\Auth\\AuthenticationException"
}
```

## Creating Sanctum Tokens

### Server-side Token Creation
```php
// In a controller or service
$user = auth()->user();
$token = $user->createToken('API Token')->plainTextToken;
```

### Client-side Token Usage
Tokens created on the server should be securely passed to the frontend, either via meta tags or secure JavaScript variables.

## Notes
- All timestamps are returned in ISO 8601 format
- The `formatted_date` field uses relative time formatting (e.g., "2 hours ago")
- The `is_read` field is a boolean indicating if the notification has been read
- Pagination follows Laravel's standard format
- For in-application API calls, session authentication is sufficient
- Sanctum tokens are recommended for external API access or mobile applications