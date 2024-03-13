.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Setup project
	composer install --no-interaction
	php artisan key:generate
	php artisan optimize:clear
	yarn
	yarn run dev

test: ## Run all tests
	bin/pest
	bin/phpspec run -fpretty
	bin/phpcbf | true
	bin/phpcs
	bin/phpstan --memory-limit=1G

provision-server: ## Setup server
	ansible-playbook -i ansible/hosts ansible/provision-server.yml --extra-vars="@ansible/server-vars.yml"
