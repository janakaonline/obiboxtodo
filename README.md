## Prerequesits
- Docker desktop

## Setup instructions
- In the backend directory, copy `.env.example` to `.env`
- At the root directory, spring up the docker containers by executing `docker compose up --build -d`
- Run migrations in the backend `docker compose exec -it api php artisan migrate`
- If you want to seed sample data, run `docker compose exec -it api php artisan db:seed`
- To run BE tests, execute the command `docker compose exec -it api php artisan test`
- You can access the frontend on 'http://localhost:8000'
