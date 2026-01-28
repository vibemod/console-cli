.PHONY: project
project: install setup

.PHONY: init
init:
	cp config/local.neon.example config/local.neon

.PHONY: install
install:
	composer install

.PHONY: update
update:
	composer update

.PHONY: setup
setup:
	mkdir -p var/tmp var/log
	chmod 0777 var/tmp var/log

.PHONY: clean
clean:
	find var/tmp -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +
	find var/log -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +

############################################################
# DEVELOPMENT ##############################################
############################################################
.PHONY: qa
qa: cs phpstan

.PHONY: cs
cs:
	vendor/bin/codesniffer app tests

.PHONY: csf
csf:
	vendor/bin/codefixer app tests

.PHONY: phpstan
phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M

.PHONY: tests
tests:
	vendor/bin/tester -s -p php --colors 1 -C tests

.PHONY: coverage
coverage:
	vendor/bin/tester -s -p phpdbg --colors 1 -C --coverage ./coverage.xml --coverage-src ./app tests

.PHONY: build
build:
	echo "OK"

.PHONY: console
console:
	php bin/console.php

.PHONY: schema-create
schema-create:
	php bin/console.php orm:schema-tool:create

.PHONY: schema-update
schema-update:
	php bin/console.php orm:schema-tool:update --force

.PHONY: user-list
user-list:
	php bin/console.php user:list

.PHONY: user-new
user-new:
	php bin/console.php user:new

.PHONY: user-show
user-show:
	php bin/console.php user:show

.PHONY: user-delete
user-delete:
	php bin/console.php user:delete
