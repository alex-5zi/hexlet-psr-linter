install:
	composer install

autoload:
	composer dump-autoload

lint:
	composer exec 'phpcs --standard=PSR2 src tests'
    ./bin/hexlet-psr-linter src/

test:
	composer exec 'phpunit tests'
    
beauty:
    composer exec 'phpcbf --standard=PSR2 src bin'
