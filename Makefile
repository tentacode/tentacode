.PHONY: help test

# Colors
CYAN = \033[0;36m
GREEN = \033[0;32m
YELLOW = \033[0;33m
NC = \033[0m # No Color

help: ## Display this help message
	@grep -E '^[a-zA-Z_-\.]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## First time setup (copy env and build containers)
	cd www && npm install

test: ## Run astro check and ESLint (no Playwright)
	cd www && npx astro check && npx eslint src

serve: ## Start astro sever
	cd www && npm run dev -- --port=1447
