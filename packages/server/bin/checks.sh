#!/bin/bash

# Run PHP-CS-Fixer
./vendor/bin/php-cs-fixer fix --dry-run --config=.php-cs-fixer.dist.php || exit 1

# Run PHPMD
./vendor/bin/phpmd src text pmd-ruleset.xml || exit 1

# Run PHPStan
./vendor/bin/phpstan analyse --configuration=phpstan.neon || exit 1

# Run Psalm
./vendor/bin/psalm || exit 1

# If we're here, all checks passed
echo "All checks passed."
exit 0
