.PHONY: help

# Colors
CYAN = \033[0;36m
GREEN = \033[0;32m
YELLOW = \033[0;33m
NC = \033[0m # No Color

help: ## Display this help message
	@grep -E '^[a-zA-Z_-\.]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## First time setup (copy env and build containers)
	@echo "$(YELLOW)Building containers...$(NC)"
	docker compose build
	@echo "$(GREEN)Installation complete!$(NC)"

up: ## Start all containers
	docker compose up -d

down: ## Stop all containers
	docker compose down

www.connect: ## Open www container shell
	docker compose exec www zsh

tests: ## Run all tests
	docker compose exec www eslint . --fix && \
	echo "$(GREEN)www eslint passed!$(NC)" && \
	docker compose exec www npx prettier --write src && \
	echo "$(GREEN)www prettier passed!$(NC)"

destroy-docker: ## Remove all containers and volumes
	@echo "$(YELLOW)Removing all containers and volumes...$(NC)"
	docker compose down -v --remove-orphans
	@echo "$(GREEN)Cleanup complete!$(NC)" 

provision-server: ## Provision server
	ansible-playbook -i infrastructure/ansible/hosts infrastructure/ansible/provision-server.yml --extra-vars="@infrastructure/ansible/ohdit-vars.yml"

deploy: ## Deploy main to server
	ansible-playbook -i infrastructure/ansible/hosts infrastructure/ansible/deploy.yml --extra-vars="@infrastructure/ansible/ohdit-vars.yml"
