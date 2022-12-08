.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-50s\033[0m %s\n", $$1, $$2}'

open_tableplus_mysql: ## table plus mysql
	open "mysql://root:root@127.0.0.1:6661/?statusColor=007F3D&enviroment=local&name=&tLSMode=1&usePrivateKey=false&safeModeLevel=0&advancedSafeModeLevel=0"

start_project: ## start project
	mysql.server start && open http://127.0.0.1:8585 && ./rr serve

rr_serve: ## rr_serve
	./rr serve

crawl: ## crawl
	/usr/local/Cellar/php/8.1.13/bin/php app.php crawl -vvv

console: ## crawl
	/usr/local/Cellar/php/8.1.13/bin/php app.php

docker_up: ## docker up
	docker-compose up --force-recreate -d

docker_down: ##
	docker-compose down

run_elasticvue:
	docker run -p 8085:8080 -d cars10/elasticvue && echo "http://localhost:8085/cluster/0/search"
