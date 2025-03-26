docker compose down
rm -Rf vendor
rm -f composer.lock
git pull
docker compose up -d