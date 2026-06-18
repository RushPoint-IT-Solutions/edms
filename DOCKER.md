# EDMS Docker Setup Guide

## Requirements

| Tool | Minimum Version |
|------|-----------------|
| Docker | 24.x |
| Docker Compose | v2, bundled with Docker |
| Git | any |

---

## 1. Install Docker on the Ubuntu Server

```bash
# Ubuntu / Debian
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
newgrp docker

# Verify
docker --version
docker compose version
```

---

## 2. Create a GitHub Personal Access Token

The repository is private, so the server needs a GitHub token to clone and pull updates.

1. Go to GitHub Settings, Developer settings, Personal access tokens, Tokens classic.
   Direct link: `https://github.com/settings/tokens`
2. Click Generate new token classic.
3. Set a name, for example `edms-server-deploy`.
4. Set expiration. Use your preferred expiry policy.
5. Select the `repo` scope for private repository access.
6. Click Generate token and copy it immediately.

Keep this token private. Do not commit it to Git.

---

## 3. Clone the Repository

Create the server project directory:

```bash
sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www
cd /var/www
```

Clone with HTTPS using your GitHub username and token:

```bash
git clone https://YOUR_USERNAME:YOUR_TOKEN@github.com/YOUR_USERNAME/YOUR_PRIVATE_REPO.git edms
cd /var/www/edms
```

Or clone with SSH if your server already has GitHub SSH access:

```bash
git clone git@github.com:YOUR_USERNAME/YOUR_PRIVATE_REPO.git edms
cd /var/www/edms
```

---

## 4. Configure Environment

Copy the production environment template:

```bash
cp .env.production.example .env
nano .env
```

Update these required values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_KEY=

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=dms
DB_USERNAME=edms
DB_PASSWORD=change_this_database_password

DOCKER_DB_DATABASE=dms
DOCKER_DB_USERNAME=edms
DOCKER_DB_PASSWORD=change_this_database_password
DOCKER_DB_ROOT_PASSWORD=change_this_root_password

APP_PORT=8080
MYSQL_PORT=3307
```

Save and exit nano:

```text
Ctrl + O
Enter
Ctrl + X
```

Important:

- `DB_PASSWORD` and `DOCKER_DB_PASSWORD` must match.
- `APP_URL` must be your real domain or server URL.
- Leave `DB_HOST=mysql`; this is the Docker service name.
- MySQL is bound to `127.0.0.1`, so it is not publicly exposed.

---

## 5. Build and Start Containers

```bash
cd /var/www/edms
docker compose up -d --build
```

This starts:

| Container | Role | External Port |
|-----------|------|---------------|
| `edms-nginx` | Nginx | `8080` by default |
| `edms-app` | PHP 7.4 FPM | internal |
| `edms-mysql` | MySQL 8.0 | `3307`, local only |

App URL after first start:

```text
http://YOUR_SERVER_IP:8080
```

---

## 6. Build with Private GitHub Composer Packages

Use this only if Composer needs private GitHub packages during `docker compose build`.

### Option A: GitHub Token

```bash
export GITHUB_TOKEN="ghp_your_token_here"
docker compose -f docker-compose.yml -f docker-compose.github.yml build
docker compose up -d
```

### Option B: SSH Forwarding

Make sure your server SSH key can access GitHub:

```bash
ssh -T git@github.com
```

Load the SSH key:

```bash
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_rsa
```

Build with SSH forwarding:

```bash
docker compose -f docker-compose.yml -f docker-compose.github.yml build --ssh default
docker compose up -d
```

---

## 7. Run First-Time Laravel Setup

Generate the app key:

```bash
docker compose exec app php artisan key:generate
```

Run all migrations:

```bash
docker compose exec app php artisan migrate --force
```

Clear old caches:

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

Build production caches:

```bash
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
```

---

## 8. Open Firewall Ports

If you use Ubuntu UFW:

```bash
sudo ufw allow OpenSSH
sudo ufw allow 8080/tcp
sudo ufw enable
sudo ufw status
```

If you change `APP_PORT=80`, allow port 80:

```bash
sudo ufw allow 80/tcp
```

If you use HTTPS with a reverse proxy, allow 443:

```bash
sudo ufw allow 443/tcp
```

---

## 9. Expose with a Domain

### Simple HTTP on port 80

Edit `.env`:

```env
APP_PORT=80
APP_URL=http://your-domain.com
```

Restart:

```bash
docker compose up -d
```

### Reverse proxy or SSL

If you use Nginx Proxy Manager, Caddy, Traefik, Cloudflare Tunnel, or another reverse proxy, keep the app on an internal port:

```env
APP_PORT=8080
APP_URL=https://your-domain.com
```

Restart:

```bash
docker compose up -d
```

Point the reverse proxy to:

```text
http://127.0.0.1:8080
```

---

## 10. Daily Commands

| Task | Command |
|------|---------|
| Start | `docker compose up -d` |
| Stop | `docker compose down` |
| Restart | `docker compose restart` |
| Tail logs | `docker compose logs -f` |
| App shell | `docker compose exec app sh` |
| Run artisan | `docker compose exec app php artisan <cmd>` |
| Run composer | `docker compose exec app composer <cmd>` |
| Check containers | `docker compose ps` |

---

## 11. Update the App Later

Pull the latest code:

```bash
cd /var/www/edms
git pull
```

Rebuild and restart:

```bash
docker compose up -d --build
```

Run new migrations:

```bash
docker compose exec app php artisan migrate --force
```

Refresh caches:

```bash
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:clear
```

---

## 12. Database Access

The database is exposed only on the server itself:

| Field | Value |
|-------|-------|
| Host | `127.0.0.1` |
| Port | `3307` |
| Database | `dms` |
| Username | `edms` |
| Password | value of `DOCKER_DB_PASSWORD` |

To connect from your local computer, create an SSH tunnel:

```bash
ssh -L 3307:127.0.0.1:3307 your_user@YOUR_SERVER_IP
```

Then connect your MySQL client to:

```text
Host: 127.0.0.1
Port: 3307
```

---

## 13. Reset or Rebuild

Rebuild after Dockerfile changes:

```bash
docker compose up -d --build
```

Full reset, including database data:

```bash
docker compose down -v
docker compose up -d --build
docker compose exec app php artisan migrate --force
```

Warning: `docker compose down -v` deletes the MySQL database volume.

---

## 14. Troubleshooting

### Permission denied on storage or bootstrap/cache

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Laravel shows old environment values

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:cache
```

### Database connection fails after first start

MySQL can take a little time to initialize on the first run. Wait 15 to 30 seconds, then run:

```bash
docker compose exec app php artisan migrate --force
```

### Port 8080 already in use

Edit `.env`:

```env
APP_PORT=9090
```

Restart:

```bash
docker compose up -d
```

The app will be available at:

```text
http://YOUR_SERVER_IP:9090
```

### GitHub authentication failed

- Confirm your GitHub token has `repo` scope.
- Confirm the token has not expired.
- Use HTTPS clone format with username and token.
- For SSH, confirm `ssh -T git@github.com` works on the server.

### Composer install fails during build

If private packages are required, build with the GitHub overlay:

```bash
export GITHUB_TOKEN="ghp_your_token_here"
docker compose -f docker-compose.yml -f docker-compose.github.yml build
docker compose up -d
```

### Check Laravel logs

```bash
docker compose exec app tail -f storage/logs/laravel.log
```

### Clear all Laravel caches

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

---

## 15. Docker File Structure

```text
/var/www/edms/
|-- Dockerfile                  # PHP 7.4 FPM image
|-- docker-compose.yml          # Services: app, nginx, mysql
|-- docker-compose.github.yml   # Optional private GitHub build access
|-- .env                        # Production environment file
|-- .env.production.example     # Production template
|-- .env.docker.example         # Local Docker template
|-- .dockerignore
|-- docker/
|   |-- nginx.conf              # Nginx server block
|   |-- php.ini                 # PHP overrides
|   `-- entrypoint.sh           # Laravel container startup script
`-- DOCKER.md                   # This guide
```
