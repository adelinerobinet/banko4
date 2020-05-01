## Installation du projet
composer.lock: composer.json
	composer update
vendor: composer.lock
	composer install
install: vendor

.PHONY: composer encore-dev encore-prod encore-watch apache php yarn watch
.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' \
		| sed -e 's/\[32m##/[33m/'
.PHONY: help

##
## --------------------------------------------------
##   Docker
## --------------------------------------------------
##

## Adeline
OS := $(shell uname)

start_dev:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f docker-compose-dev.yml up -d
	docker-sync start
else
	docker-compose up -d
endif

stop_dev:
ifeq ($(OS),Darwin)
	docker-compose stop
	docker-sync stop
else
	docker-compose stop
endif

php_bash:
	docker exec -it -u root banko4_php bash

## Pierre
start:
	docker-compose up -d

stop:
	docker-compose stop
