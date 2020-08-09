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
coding-standards: .php_cs.dist ## Normalizes composer.json with ergebnis/composer-normalize
coding-standards: vendor
	@echo "+ $@"
	@docker exec -it application composer normalize
	@docker exec -it application mkdir -p .build/php-cs-fixer
	@docker exec -it application vendor/bin/php-cs-fixer fix --config=.php_cs.dist --diff --diff-format=udiff --verbose

.PHONY: dependency-analysis
dependency-analysis: composer-require-checker.json ## Runs a dependency analysis with maglnet/composer-require-checker
dependency-analysis: vendor
	@docker run --interactive --rm --tty --volume ${PWD}:/app webfactory/composer-require-checker:2.1.0 check --config-file=composer-require-checker.json

.PHONY: code-tests
code-tests: phpunit.xml ## Runs unit tests with phpunit/phpunit
code-tests: vendor
	@docker exec -it application mkdir -p .build/phpunit
	@docker exec -it application vendor/bin/phpunit --configuration=phpunit.xml

.PHONY: code-coverage
code-coverage: phpunit.xml ## Collects coverage from running unit tests with phpunit/phpunit
code-coverage: vendor
	@docker exec -it application mkdir -p .build/phpunit
	@docker exec -it application vendor/bin/phpunit --configuration=phpunit.xml --coverage-text

.PHONY: mutation-tests
mutation-tests: infection.json.dist  ## Runs mutation tests with infection/infection
mutation-tests: vendor
	@docker exec -it application mkdir -p .build/infection
	@docker exec -it application vendor/bin/infection --configuration=infection.json.dist

.PHONY: static-code-analysis
static-code-analysis: phpstan.neon phpstan-baseline.neon ## Runs a static code analysis with phpstan/phpstan and vimeo/psalm
static-code-analysis: vendor
	@docker exec -it application mkdir -p .build/phpstan
	@docker exec -it application vendor/bin/phpstan analyse --configuration=phpstan.neon

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: phpstan.neon phpstan-baseline.neon ## Generates a baseline for static code analysis with phpstan/phpstan
static-code-analysis-baseline: vendor
	@docker exec -it application mkdir -p .build/phpstan
	@docker exec -it application vendor/bin/phpstan analyze --configuration=phpstan.neon --generate-baseline=phpstan-baseline.neon
