
DB_CONTAINER_NAME = assignment_backend_0825_db

create-docker-env:
	@rm -f docker/.env
	@echo "UID=$$(id -u)" > docker/.env
	@echo "GID=$$(id -g)" >> docker/.env
	@echo ".env file recreated."

copy-www-env:
	@cp www/.env.sample www/.env
	@echo "Copied .env.sample to .env"

install_dependencies:
	@echo "Installing Composer dependencies..."
	cd docker && docker compose build 
	cd docker && docker compose up -d --force-recreate --remove-orphans
	cd docker && docker compose exec  -u www-data backend composer install

wait-for-db:
	@echo "Checking if $(DB_CONTAINER_NAME) is healthy..."
	@attempts=0; \
	while [ $$attempts -lt 20 ]; do \
		STATUS=$$(docker inspect -f '{{.State.Health.Status}}' $(DB_CONTAINER_NAME) 2>/dev/null || echo "not-found"); \
		if [ "$$STATUS" = "healthy" ]; then \
			echo "$(DB_CONTAINER_NAME) is healthy!"; \
			exit 0; \
		elif [ "$$STATUS" = "unhealthy" ]; then \
			echo "$(DB_CONTAINER_NAME) is unhealthy. Aborting."; \
			exit 1; \
		elif [ "$$STATUS" = "running" ] || [ "$$STATUS" = "starting" ]; then \
			echo "Waiting for $(DB_CONTAINER_NAME) to become healthy..."; \
		else \
			echo "Container status: $$STATUS"; \
		fi; \
		sleep 2; \
		attempts=`expr $$attempts + 1`; \
	done; \
	echo "Timeout waiting for $(DB_CONTAINER_NAME) to become healthy."; \
	exit 1

install_db: wait-for-db	
	@echo "Installing database..."
	cd docker && docker compose exec  -u www-data backend php bin/console migrations:migrate --no-interaction

setup: create-docker-env copy-www-env install_dependencies install_db
	@echo "Setup completed successfully."
