# UCP Fade

A web application for managing user control panel and games.

## Components

- **Slide**: A game server that handles the slide game logic
- **Chat**: A chat client that connects to the slide server
- **Dashboard**: A PHP-based dashboard for user management

## Setup

### Prerequisites

- Node.js 16+
- PHP 7.4+
- MySQL 5.7+
- Composer

### Installation

1. Clone the repository:
```bash
git clone https://github.com/lucreista/ucp-fade.git
cd ucp-fade
```

2. Install Node.js dependencies:
```bash
cd slide
npm install
cd ../chat
npm install
```

3. Install PHP dependencies:
```bash
cd ../dashboard/skins
composer install
```

4. Create .env files:

For slide:
```bash
cd ../../slide
cp .env.example .env
```

For dashboard:
```bash
cd ../dashboard
cp .env.example .env
```

5. Edit the .env files with your database credentials and other configuration options.

## Running the Application

### Development Mode

```bash
cd slide
NODE_ENV=development node server.js
```

### Production Mode

```bash
cd slide
NODE_ENV=production node server.js
```

## Testing

The application can run in test mode without a database connection. It will generate mock data for testing purposes.

## Documentation

- [Changes](CHANGES.md): Documentation of changes made to the codebase
- [Dependencies](DEPENDENCIES.md): Documentation of dependencies and their versions

## License

This project is licensed under the MIT License - see the LICENSE file for details.