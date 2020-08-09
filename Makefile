.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: it
it: coding-standards static-code-analysis code-tests ## Runs the coding-standards, code-tests

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

.PHONY: code-tests
code-tests: phpunit.xml
code-tests:
	@docker exec -it application mkdir -p .build/phpunit
	@docker exec -it application vendor/bin/phpunit --configuration=phpunit.xml

.PHONY: mutation-tests
mutation-tests:  ## Runs mutation tests with infection/infection
	@docker exec -it application mkdir -p .build/infection
	@docker exec -it application vendor/bin/infection --configuration=infection.json.dist

.PHONY: static-code-analysis
static-code-analysis: vendor ## Runs a static code analysis with phpstan/phpstan and vimeo/psalm
	@docker exec -it application mkdir -p .build/phpstan
	@docker exec -it application vendor/bin/phpstan analyse --configuration=phpstan.neon

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: vendor ## Generates a baseline for static code analysis with phpstan/phpstan
	@docker exec -it application mkdir -p .build/phpstan
	@docker exec -it application vendor/bin/phpstan analyze --configuration=phpstan.neon --generate-baseline=phpstan-baseline.neon
