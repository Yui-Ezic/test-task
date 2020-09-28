## Start working
1. Clone the repository
```sh
git clone https://github.com/Yui-Ezic/test-task.git
```
2. Install dependancies
```sh
cd test-task
```
```sh
composer install
```
3. Create .env.local and .env.test.local files from .env and replace connetion to database
4. Configure your server to run public\index.php for all requests
5. Run migrations
```sh
php ./bin/console doctrine:migrations:migrate
```

## Testing
1. Load fixtures
```sh
php ./bin/console doctrine:fixtures:load
```
2. Run tests
```sh
php ./bin/phpunit
```
