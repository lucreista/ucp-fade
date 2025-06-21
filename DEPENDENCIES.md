# Dependencies Documentation

## Node.js Dependencies

### Slide Directory

#### Production Dependencies
- **dotenv**: ^16.3.1 - For loading environment variables from .env files
- **escape-html**: ^1.0.3 - For escaping HTML in user input
- **mysql2**: ^3.6.1 - MySQL client for Node.js with promises support
- **nodemon**: ^3.0.1 - For automatically restarting the server during development
- **socket.io**: ^4.7.2 - For real-time bidirectional event-based communication

#### Development Dependencies
- None

### Chat Directory

#### Production Dependencies
- **socket.io-client**: ^4.7.4 - Client library for socket.io

#### Development Dependencies
- None

## PHP Dependencies

### Dashboard/skins Directory

#### Production Dependencies
- **vlucas/phpdotenv**: For loading environment variables from .env files
- **thedudeguy/rcon**: For RCON protocol implementation
- **symfony/polyfill** packages: For PHP polyfills

#### Development Dependencies
- None

## Environment Variables

### Required Environment Variables for Slide
- **DB_HOST**: Database host (default: localhost)
- **DB_USER**: Database username
- **DB_PASSWORD**: Database password
- **DB_NAME**: Database name
- **NODE_ENV**: Environment (development or production)

### Required Environment Variables for Dashboard
- **DB_HOST**: Database host (default: localhost)
- **DB_USER**: Database username
- **DB_PASSWORD**: Database password
- **DB_NAME**: Database name

## Running the Application

### Development Mode
```bash
cd /workspace/ucp-fade/slide
NODE_ENV=development node server.js
```

### Production Mode
```bash
cd /workspace/ucp-fade/slide
NODE_ENV=production node server.js
```

## Testing
The application can run in test mode without a database connection. It will generate mock data for testing purposes.