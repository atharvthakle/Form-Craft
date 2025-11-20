# Form Craft

A modern, secure PHP-based contact form with JSON storage, real-time validation, and an aesthetic admin dashboard. Built with vanilla PHP, HTML, CSS, and JavaScript - no frameworks required!

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.4-purple)
![License](https://img.shields.io/badge/license-MIT-green)

## Preview

## Features

### Security
- **CSRF Protection** - Token-based authentication for form submissions
- **XSS Prevention** - Input sanitization using PHP's `htmlspecialchars()`
- **DNS Email Validation** - Verifies email domain existence
- **Session Management** - Secure token expiration (1-hour validity)

### User Interface
- **Glassmorphism Design** - Modern, aesthetic UI with blur effects
- **Dark/Light Mode Toggle** - Theme switcher with localStorage persistence
- **Animated Particles Background** - Floating particle effects
- **Toast Notifications** - Success/error messages without page refresh
- **Fully Responsive** - Mobile-friendly design

### Functionality
- **Real-time Validation** - Frontend and backend validation
- **AJAX Form Submission** - No page refresh on submit
- **Character Counter** - Live message length indicator
- **Admin Dashboard** - View, search, and manage submissions
- **JSON Storage** - Lightweight data persistence without database

### Admin Panel Features
- View all submissions in a responsive table
- Search by name, email, phone, or message
- Delete individual submissions
- Bulk delete all submissions
- Statistics dashboard (total, today's count, latest entry)
- Real-time data refresh

---

## Technical Details

### Form Validation Rules

    | Field       | Validation Rules                  |
    |-------------|-----------------------------------|
    | Name        | Required, letters and spaces only |
    | Email       | Required, valid format, DNS check |
    | Phone       | Required, exactly 10 digits       |
    | Message     | Required, minimum 10 characters   |

### Data Structure

Submissions are stored in JSON format:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "message": "Sample message",
  "timestamp": "2025-11-20 10:30:45",
  "id": "entry_uniqueid123"
}
```
---

## Technologies Used

- **Backend:** PHP 8.4
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Storage:** JSON file-based
- **Design:** Glassmorphism, CSS Gradients, Animations
- **Security:** CSRF tokens, XSS prevention, DNS validation

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

### NOTE

The data in 'data/submissions.json' is sample data that was tested by me.
