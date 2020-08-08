.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: it
it: coding-standards ## Runs the coding-standards

.PHONY: application-start
application-start: ## Start docker containers
	@echo "+ $@"
	@docker-compose up -d --force-recreate

.PHONY: application-stop
application-stop: ## Stop docker containers
	@echo "+ $@"
	@docker-compose down

.PHONY: application-clean
application-clean: application-stop ## Clean docker runtime folders
application-clean:
	@echo "+ $@"
	@rm -rf ./.runtime/logs/

.PHONY: vendor
vendor: composer.json ## Install vendor with composer inside application's docker container
	@echo "+ $@"
	@docker exec -it application composer validate --strict
	@docker exec -it application composer install --no-interaction --no-progress --no-suggest

.PHONY: local-vendor
local-vendor: composer.json ## Install vendor with composer locally
	@echo "+ $@"
	@composer validate --strict
	@composer install --no-interaction --no-progress --no-suggest

.PHONY: docker-image-vendor
docker-image-vendor: composer.json ## Install vendor with docker composer image
	@echo "+ $@"
	@docker run --rm -v $(PWD):/app composer validate --strict
	@docker run --rm -v $(PWD):/app composer install --no-interaction --no-progress --no-suggest

.PHONY: coding-standards
coding-standards: vendor ## Normalizes composer.json with ergebnis/composer-normalize
	@echo "+ $@"
	@docker exec -it application composer normalize
	@docker exec -it application mkdir -p .build/php-cs-fixer
	@docker exec -it application vendor/bin/php-cs-fixer fix --config=.php_cs.dist --diff --diff-format=udiff --verbose
