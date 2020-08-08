.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: docker-clean
docker-clean: docker-down ## Clean docker runtime folders
docker-clean:
	@echo "+ $@"
	@rm -rf ./runtime/logs/nginx/

.PHONY: docker-up
docker-up: ## Start docker containers
	@echo "+ $@"
	@docker-compose up -d --force-recreate

.PHONY: docker-down
docker-down: ## Stop docker containers
	@echo "+ $@"
	@docker-compose down

.PHONY: docker-vendor
docker-vendor: composer.json ## Install vendor with composer inside application's docker container
	@docker exec -it application composer validate --strict
	@docker exec -it application composer install --no-interaction --no-progress --no-suggest

.PHONY: vendor
vendor: composer.json ## Install vendor with composer locally
	composer validate --strict
	composer install --no-interaction --no-progress --no-suggest

.PHONY: docker-image-vendor
docker-image-vendor: composer.json ## Install vendor with docker composer image
	docker run --rm -v $(PWD):/app composer validate --strict
	docker run --rm -v $(PWD):/app composer install --no-interaction --no-progress --no-suggest
