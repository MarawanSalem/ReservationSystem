# Reservation System

A modern and efficient reservation management system built with Laravel, featuring a robust API and real-time notifications for enhanced user engagement.

## Tech Stack

- **Framework**: Laravel 10.x
- **PHP Version**: 8.1+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **Role Management**: Spatie Laravel Permission
- **Push Notifications**: Firebase Cloud Messaging (FCM) via Kreait Firebase PHP SDK
- **API Documentation**: Postman Collection

## Key Features

### User Features
- User registration and authentication
- Profile management with image upload
- Browse and search services with advanced filtering
  - Category-based filtering
  - Price range filtering
  - Search by name/description
  - Sorting options
- Reservation management
  - Create new reservations
  - View reservation history
  - Cancel reservations
  - Real-time status updates
- Real-time notifications
  - Reservation confirmations
  - Status updates
  - Service announcements
  - Custom notifications from admin

### Admin Features
- Complete service management
  - Create, read, update, and delete services
  - Image upload for services
  - Category management
  - Price and duration settings
- Reservation oversight
  - View all reservations
  - Filter by date, status, and user
  - Cancel reservations if needed
- User management
  - View user profiles
  - Monitor user activity
- Notification system
  - Send custom notifications to users
  - Broadcast service updates
  - Send reservation reminders

## Enhanced User Engagement

The system includes a comprehensive notification system powered by Firebase Cloud Messaging (FCM) to enhance user engagement and satisfaction. Users receive real-time updates about:
- Reservation confirmations and changes
- Service availability updates
- Special offers and promotions
- Custom announcements from administrators

This feature ensures users stay informed and engaged with the platform, leading to better user experience and increased customer satisfaction.

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd ReservationSystem
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Firebase PHP SDK:
```bash
composer require kreait/firebase-php
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservation_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Configure Firebase credentials:
```
FIREBASE_CREDENTIALS=path/to/firebase-credentials.json
```

8. Run migrations and seeders:
```bash
php artisan migrate --seed
```

9. Start the development server:
```bash
php artisan serve
```

## API Documentation

The API documentation is available as a Postman collection. Import the `ReservationSystem.postman_collection.json` file into Postman to access all available endpoints and their documentation.

## Environment Variables

Required environment variables:
```
APP_NAME=ReservationSystem
APP_ENV=local
APP_KEY=base64:your-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservation_system
DB_USERNAME=your_username
DB_PASSWORD=your_password

FIREBASE_CREDENTIALS=path/to/firebase-credentials.json
```

## Security

- All API endpoints are protected with Laravel Sanctum authentication
- Role-based access control using Spatie Laravel Permission
- Input validation and sanitization
- CSRF protection
- Rate limiting on authentication endpoints

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
