#!make
include .env_docker
export $(shell sed 's/=.*//' .env_docker)

up: ## Start the containers
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
	U_ID=${UID} docker-compose build

ssh: ## ssh's into the container
	U_ID=${UID} docker exec -it --user ${UID} ${RABBIT} bash