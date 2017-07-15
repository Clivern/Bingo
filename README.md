
```
                                               Bingo

> git clone https://github.com/Clivern/Bingo.git
# the add database credentials in app/config/parameters.yml (I removed it from .gitignore) and create your database
> composer install
> php app/console doctrine:schema:validate
> php app/console doctrine:schema:update --dump-sql
> php app/console doctrine:schema:update --force
> php app/console server:run

# Let's Play with our API
> curl -d "name=clivern1&username=clivern1&email=hello1@clivern.com&password=1234567" "http://127.0.0.1:8000/api/user/add"
> curl -d "name=clivern2&username=clivern2&email=hello2@clivern.com&password=1234567" "http://127.0.0.1:8000/api/user/add"
> curl -d "id=2" "http://127.0.0.1:8000/api/user/delete"
> curl -d "name=admins1&description=bla1&user_id=1" "http://127.0.0.1:8000/api/group/add"
> curl -d "name=admins2&description=bla2&user_id=1" "http://127.0.0.1:8000/api/group/add"
> curl -d "name=admins3&description=bla3&user_id=1" "http://127.0.0.1:8000/api/group/add"
> curl -d "name=admins4&description=bla4&user_id=1" "http://127.0.0.1:8000/api/group/add"
> curl -d "id=4" "http://127.0.0.1:8000/api/group/delete"
> curl -d "user_id=1&group_id=1" "http://127.0.0.1:8000/api/user/assign/group"
> curl -d "user_id=1&group_id=2" "http://127.0.0.1:8000/api/user/assign/group"
> curl -d "user_id=1&group_id=3" "http://127.0.0.1:8000/api/user/assign/group"
> curl -d "user_id=1&group_id=1" "http://127.0.0.1:8000/api/user/remove/group"
```