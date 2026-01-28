# AGENTS.md

This file provides guidance to agents when working with code in this repository.

## Project

- Console CLI application
- PHP 8.4
- Nette/Contributte bootstrap
- Symfony Console
- Doctrine ORM/DBAL
- Nette Tester

## Commands

### Setup
```bash
make init        # Copy config/local.neon.example → config/local.neon
make project     # composer install + create var/tmp and var/log directories
```

### Development
```bash
make console     # Run CLI entrypoint
```

### Quality Assurance
```bash
make qa          # Run both cs and phpstan
make cs          # CodeSniffer check (app + tests)
make csf         # CodeSniffer auto-fix
make phpstan     # Static analysis (level 9, PHP 8.4)
```

### Testing
```bash
make tests                                              # Run all tests with Nette Tester
vendor/bin/tester -s -p php --colors 1 -C tests/Unit    # Run only unit tests
vendor/bin/tester -s -p php --colors 1 -C tests/path/to/TestFile.phpt  # Run single test
make coverage                                           # Generate coverage report
```

## Architecture

```
├── bin/
│   └── console.php          — CLI entry point (bootstraps container, runs Symfony Console)
├── app/
│   ├── Bootstrap.php         — Configures DI container, temp/log dirs, loads config
│   ├── Console/              — Symfony Console commands
│   └── Domain/               — Doctrine entities and repositories (PHP 8 ORM attributes)
├── config/
│   ├── config.neon           — Main config: imports doctrine.neon, registers services
│   ├── doctrine.neon         — Doctrine DBAL/ORM, migrations, fixtures extensions (SQLite at db/db.sqlite)
│   └── local.neon            — Local overrides (gitignored, created from local.neon.example via make init)
├── db/
│   └── db.sqlite             — SQLite database
└── tests/                    — Nette Tester (.phpt)
    ├── bootstrap.php         — Loads autoloader and Contributte\Tester\Environment::setup()
    └── Toolkit/Tests.php     — Shared constants (ROOT_PATH, APP_PATH)
```
