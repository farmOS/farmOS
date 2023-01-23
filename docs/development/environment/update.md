# Updating local environment

The following commands will update your local farmOS development environment.

This approach avoids running `composer` commands because that is already done
when the Docker image is built.

**Warning**: This will replace everything except the `profiles` and `sites`
directories. If you are developing farmOS core, this will ensure that your
farmOS Git repository (inside `profiles/farm`) will not be touched. If you
are developing a custom module, make sure that it is in `sites/all/modules`,
otherwise it will be deleted.

**PHPStorm**: If you are using PHPStorm, you will also want to make sure the
`.idea` folder is not destroyed during this process. If it is in the `www`
directory, be sure to move that out before running `rm -r www` below, and
restore it afterwards. It is recommended that you close PHPStorm during this
process to avoid any project settings corruption.

```
# Run these commands from the local directory that contains docker-compose.yml.
# The Docker containers should be running.

# Backup www volume, just in case.
sudo tar -czf www.tar.gz www

# Pull latest 2.x-dev Docker image.
docker pull farmos/farmos:2.x-dev

# Move directories.
mv www/web/profiles ./profiles
mv www/web/sites ./sites

# Update codebase.
docker compose down
rm -r www
docker compose up -d

# Restore directories.
sudo rm -rf www/web/profiles www/web/sites
mv ./profiles www/web/profiles
mv ./sites www/web/sites

# Update farmOS profile.
cd www/web/profiles/farm
git checkout 2.x && git pull origin 2.x

# Run Drupal database updates.
docker compose exec -u www-data www drush updb
```
