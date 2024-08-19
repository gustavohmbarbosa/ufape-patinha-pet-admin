# Stage 1: Use a temporary image with an empty base (alpine in this case)
FROM alpine as builder

# Copy files from the local directory into this stage
COPY /docker /app

# Stage 2: Final image
FROM alpine

# Copy the files from the builder stage into the final image
COPY --from=builder /app /app

# Continue with the rest of the setup
COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
