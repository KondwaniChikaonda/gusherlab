FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Install required extensions (if needed)
RUN docker-php-ext-install pdo pdo_mysql

# Copy project files
COPY . .

# Expose the correct port
EXPOSE 10000

# Start the PHP server
CMD ["php", "-S", "0.0.0.0:10000"]
