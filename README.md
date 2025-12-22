# Task Management System

A simple and intuitive Task Management System built with Laravel, Vue.js, and Tailwind CSS. This system helps users manage their tasks efficiently with features like creating, editing, filtering, and tracking task progress.

## 🚀 Technologies Used

### Frontend
- **Vue.js 3** - Progressive JavaScript framework
- **TypeScript** - Type-safe JavaScript
- **Inertia.js** - Modern monolith approach (SPA without API)
- **Tailwind CSS 4** - Utility-first CSS framework
- **Vite** - Next generation frontend tooling

### Backend
- **Laravel 12** - PHP web application framework
- **PHP 8.4** - Server-side scripting language

### Database
- **MySQL 8** - Relational database management system

### Development Tools
- **Laravel Sail** - Docker development environment
- **ESLint** - JavaScript linting
- **Prettier** - Code formatting

## 📋 Features

### Task Management
- ✅ Create new tasks with title, description, status, priority, and due date
- ✅ Edit existing tasks
- ✅ Delete tasks with confirmation dialog
- ✅ View task details
- ✅ Quick status change from task list

### Filtering & Sorting
- ✅ Filter tasks by status (Todo, In Progress, Done)
- ✅ Filter tasks by priority (Low, Medium, High)
- ✅ Sort by title, status, priority, due date, or created date
- ✅ Sort in ascending or descending order

### UI/UX
- ✅ Responsive design (mobile-friendly)
- ✅ Dark mode support
- ✅ Loading states and spinners
- ✅ Toast notifications for user feedback
- ✅ Form validation with error messages
- ✅ Calendar date picker for due dates
- ✅ Confirmation dialogs for destructive actions

## 🗄️ Database Schema

### Tasks Table

| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT |
| title | VARCHAR(255) | NOT NULL |
| description | TEXT | NULLABLE |
| status | ENUM('todo', 'in_progress', 'done') | DEFAULT 'todo' |
| priority | ENUM('low', 'medium', 'high') | DEFAULT 'medium' |
| due_date | DATE | NULLABLE |
| created_at | TIMESTAMP | AUTO |
| updated_at | TIMESTAMP | AUTO |

### Indexes
- Primary index on `id`
- Index on `status` - for filtering by task status
- Index on `priority` - for filtering by priority level
- Composite index on `(status, priority)` - for queries filtering by both fields

## 🔌 API Endpoints

### RESTful API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/tasks` | List all tasks (supports filtering & sorting) |
| POST | `/api/tasks` | Create a new task |
| GET | `/api/tasks/{id}` | Get a specific task |
| PUT | `/api/tasks/{id}` | Update a task |
| DELETE | `/api/tasks/{id}` | Delete a task |
| PATCH | `/api/tasks/{id}/status` | Quick status update |

### Query Parameters for GET /api/tasks

| Parameter | Type | Description |
|-----------|------|-------------|
| status | string | Filter by status: `todo`, `in_progress`, `done` |
| priority | string | Filter by priority: `low`, `medium`, `high` |
| sort | string | Sort field: `title`, `status`, `priority`, `due_date`, `created_at` |
| direction | string | Sort direction: `asc`, `desc` |

### Example API Responses

**GET /api/tasks**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Complete project documentation",
            "description": "Write comprehensive README file",
            "status": "in_progress",
            "priority": "high",
            "due_date": "2025-12-25",
            "created_at": "2025-12-22T10:00:00.000000Z",
            "updated_at": "2025-12-22T10:00:00.000000Z"
        }
    ],
    "meta": {
        "total": 1,
        "filters": {
            "status": null,
            "priority": null
        },
        "sort": {
            "field": "created_at",
            "direction": "desc"
        }
    }
}
```

**POST /api/tasks**
```json
// Request
{
    "title": "New Task",
    "description": "Task description",
    "status": "todo",
    "priority": "medium",
    "due_date": "2025-12-30"
}

// Response (201 Created)
{
    "success": true,
    "message": "Task created successfully.",
    "data": {
        "id": 2,
        "title": "New Task",
        "description": "Task description",
        "status": "todo",
        "priority": "medium",
        "due_date": "2025-12-30",
        "created_at": "2025-12-22T10:30:00.000000Z",
        "updated_at": "2025-12-22T10:30:00.000000Z"
    }
}
```

**Error Response (422 Validation Error)**
```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "title": ["The title field is required."]
    }
}
```

## ⚙️ Installation & Setup

### Prerequisites
- Docker Desktop installed and running
- Git

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd task-management-aung-htet
```

### Step 2: Copy Environment File
```bash
cp .env.example .env
```

### Step 3: Update .env for Docker/Sail
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

### Step 4: Install Dependencies
```bash
# Install PHP dependencies
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### Step 5: Start Docker Containers
```bash
./vendor/bin/sail up -d
```

### Step 6: Generate Application Key
```bash
./vendor/bin/sail artisan key:generate
```

### Step 7: Run Database Migrations
```bash
./vendor/bin/sail artisan migrate
```

### Step 8: Seed Sample Data (Optional)
```bash
./vendor/bin/sail artisan db:seed
```

### Step 9: Install Frontend Dependencies & Build
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### Step 10: Access the Application
- **Web Application**: http://localhost
- **API Endpoints**: http://localhost/api/tasks

## 🧪 Running Tests

```bash
./vendor/bin/sail artisan test
```

## 📁 Project Structure

```
task-management-aung-htet/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── TaskApiController.php   # RESTful API controller
│   │   │   └── TaskController.php          # Web controller (Inertia)
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php   # Inertia middleware
│   └── Models/
│       └── Task.php                        # Task model
├── database/
│   ├── factories/
│   │   └── TaskFactory.php                 # Task factory for seeding
│   ├── migrations/
│   │   └── 2024_12_22_000001_create_tasks_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   └── ui/                         # Reusable UI components
│   │   │       ├── Button.vue
│   │   │       ├── Input.vue
│   │   │       ├── Select.vue
│   │   │       ├── Textarea.vue
│   │   │       ├── DatePicker.vue          # Calendar date picker
│   │   │       ├── Card.vue
│   │   │       ├── Badge.vue
│   │   │       ├── Toast.vue               # Notifications
│   │   │       ├── ConfirmDialog.vue       # Delete confirmation
│   │   │       └── Spinner.vue             # Loading indicator
│   │   ├── pages/
│   │   │   ├── Welcome.vue                 # Landing page
│   │   │   └── Tasks/
│   │   │       ├── Index.vue               # Task list with filters
│   │   │       ├── Create.vue              # Create task form
│   │   │       ├── Edit.vue                # Edit task form
│   │   │       └── Show.vue                # Task details
│   │   └── types/
│   │       └── index.d.ts                  # TypeScript definitions
│   └── views/
│       └── app.blade.php                   # Main blade template
├── routes/
│   ├── api.php                             # API routes
│   └── web.php                             # Web routes
├── .env.example                            # Environment configuration template
├── docker-compose.yml                      # Docker configuration
└── README.md                               # This file
```

## 📸 Screenshots

### Welcome Page
The landing page showcases the Task Management System features with a modern, responsive design.

### Task List
- Displays all tasks in a table format
- Shows task title, status, priority, due date, and created date
- Quick status change dropdown
- Filter by status and priority
- Sort by various fields

### Create/Edit Task
- Form validation for required fields
- Calendar date picker for due date selection
- Status and priority dropdowns
- Cancel and submit buttons

### Task Details
- Full task information display
- Edit and delete actions
- Status and priority badges

## 🔧 Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| APP_NAME | Application name | Laravel |
| APP_URL | Application URL | http://localhost |
| DB_CONNECTION | Database driver | mysql |
| DB_HOST | Database host | mysql |
| DB_PORT | Database port | 3306 |
| DB_DATABASE | Database name | laravel |
| DB_USERNAME | Database username | sail |
| DB_PASSWORD | Database password | password |

## 📝 License

This project is open-sourced software.

## 👤 Author

Aung Htet

---

Built with ❤️ using Laravel, Vue.js, and Tailwind CSS
