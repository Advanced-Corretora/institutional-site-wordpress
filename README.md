# Advanced Corretora Blog teste

This project is a WordPress-based blog environment, using Docker for local development and deployment.

## Getting Started

### 1. Start Docker Containers

Run the following command to start all necessary Docker containers:

```sh
docker-compose up -d
```

This will set up the database, web server, and WordPress environment.

### 2. Install Theme Dependencies

Navigate to the theme directory:

```sh
cd html/wp-content/themes/advanced-corretora
```

Install Node.js dependencies:

```sh
npm install
```

Install PHP dependencies:

```sh
composer install
```

### 3. Development Workflow

To start the development server (with hot reload):

```sh
npm run dev
```

To build for production:

```sh
npm run build
```

### 4. Code Quality

To check and automatically fix code quality issues:

```sh
npx eslint . --fix
```

---

Feel free to contribute or open issues if you encounter any problems.
