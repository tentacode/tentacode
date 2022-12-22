.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Setup project
	composer install
	yarn install

serve: ## Simple server
	symfony serve

watch: ## Building JavaScript and CSS with watch mode
	yarn encore dev --watch

test: ## Launching every tests
	bin/phpstan --memory-limit=1G
	bin/ecs

cc: ## Emptying caches
	composer dump-autoload
	bin/console cache:clear
