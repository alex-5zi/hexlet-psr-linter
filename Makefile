install:
	composer install

autoload:
	composer dump-autoload

lint:
	./vendor/bin/phpcs --standard=PSR2 src tests 
    
test:
	./vendor/bin/phpunit tests
    
beauty:	
	./vendor/bin/phpcbf --standard=PSR2 src bin tests
