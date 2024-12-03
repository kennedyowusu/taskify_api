Taskify API
Taskify is a Laravel-based API for managing tasks. It supports user authentication, task management (CRUD operations), and features like email notifications for task completion milestones. The application is built to follow best practices in Laravel development.
Features
- Authentication: User registration, login, logout, and logout from all devices.
- Task Management: Create, update, delete, and mark tasks as complete.
- Email Notifications: Sends milestone notifications (every 5 completed tasks) to the user.
- Role-Based Authorization: Ensures users can only manage their own tasks.
- Background Jobs: Processes email notifications using Laravel queues.
- Scalable Architecture: Utilizes policies, request validation, and resource classes for clean and maintainable code.
Table of Contents
1. Installation
2. API Endpoints
3. Project Structure
4. Environment Variables
5. Milestone Notification Logic
6. Testing
7. License
Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/kennedyowusu/taskify_api
   cd taskify_api
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up environment variables:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update `.env` file:
     - Database credentials
     - Mailtrap credentials (for email testing)
4. Run database migrations:
   ```bash
   php artisan migrate
   ```
5. Start the Laravel Sail services:
   ```bash
   ./vendor/bin/sail up -d
   ```
6. Generate the application key:
   ```bash
   php artisan key:generate
   ```
API Endpoints
Authentication
| Method | Endpoint        | Description                 |
|--------|-----------------|-----------------------------|
| POST   | `/register`     | Register a new user         |
| POST   | `/login`        | Login a user                |
| POST   | `/logout`       | Logout current session      |
| POST   | `/logout-all`   | Logout from all devices     |
Tasks
| Method | Endpoint               | Description                              |
|--------|------------------------|------------------------------------------|
| GET    | `/tasks`               | Get all tasks for the authenticated user|
| POST   | `/tasks`               | Create a new task                       |
| GET    | `/tasks/{taskify}`     | Get details of a specific task          |
| PATCH  | `/tasks/{taskify}`     | Update a task                           |
| DELETE | `/tasks/{taskify}`     | Delete a task                           |
| PATCH  | `/tasks/{taskify}/complete` | Mark a task as complete             |
Project Structure
### Controllers
- `TaskifyController`: Handles task management logic.
- `AuthenticationController`: Manages user authentication.

### Models
- `Taskify`: Represents tasks.
- `User`: Represents authenticated users.

### Policies
- `TaskifyPolicy`: Ensures users can only manage their own tasks.

### Jobs
- `SendTaskifyCompletionEmail`: Handles email notifications for milestones.

### Requests
- `StoreTaskifyRequest`: Validates input for creating a task.
- `UpdateTaskifyRequest`: Validates input for updating a task.
Environment Variables
Update the `.env` file with the following values:

```dotenv
APP_NAME=Taskify
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_APP_KEY
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=taskify
DB_USERNAME=sail
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@taskify.com
MAIL_FROM_NAME="Taskify Notifications"

QUEUE_CONNECTION=database
```
Milestone Notification Logic
When a task is marked as completed, the application checks if the user has reached a milestone (every 5 completed tasks). If so, an email is dispatched using the `SendTaskifyCompletionEmail` job. This is handled in the `update` method of the `TaskifyController`.
Testing
1. Run tests:
   ```bash
   php artisan test
   ```

2. Test the API:
   - Use tools like [Insomnia](https://insomnia.rest/) or [Postman](https://www.postman.com/) to test the endpoints.

3. Test email notifications:
   - Emails are sent to the configured Mailtrap inbox. Check [Mailtrap](https://mailtrap.io/) for received emails.
License
This project is open-source and available under the [MIT License](LICENSE).
Contact
For questions or support, contact [Kennedy Owusu](mailto:kennediowusu).
