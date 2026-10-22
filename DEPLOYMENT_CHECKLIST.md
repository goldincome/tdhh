# Pre-Deployment Checklist - TD Holiday Homes Phase 0-2

**Deployment Date:** ________________  
**Operator Name:** ________________  
**Environment:** Production  

---

## ✓ Pre-Deployment Checks

### Code & Git
- [ ] All code pulled from main branch: `git log --oneline -1`
- [ ] No uncommitted changes: `git status`
- [ ] Verify Phase 0-6 commits present: `git log --oneline | grep -i "slug\|media\|seo\|telemetry"`
- [ ] Review `.env.example` matches production requirements
- [ ] No secrets in git history: `git log --all -S "password\|token\|key" --oneline`

### Environment Configuration
- [ ] `.env` file created with production values
- [ ] `APP_ENV=production` (not local)
- [ ] `APP_DEBUG=false`
- [ ] `DEBUGBAR_ENABLED=false`
- [ ] `TELESCOPE_ENABLED=false`
- [ ] `TELESCOPE_ALLOWED_EMAILS=` (empty to deny all)
- [ ] Database credentials verified and working

### Database
- [ ] Database backup created: `mysqldump tdhh > backup_$(date +%s).sql`
- [ ] Backup file stored safely (at least 2 locations)
- [ ] Database connection tested: `php artisan tinker --execute "echo DB::connection()->getDatabaseName();"`

### Permissions
- [ ] Web server user owns application directory
- [ ] `storage/` directory is writable: `ls -la storage/`
- [ ] `bootstrap/cache/` directory exists and is writable

---

## ✓ Deployment Steps

### Run Deployment Script (Recommended)
```bash
bash deploy.sh
```

Or manual steps below:

### Step 1: Pull Code
```bash
git fetch origin main
git reset --hard origin/main
```
- [ ] Code pulled successfully

### Step 2: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install --production
```
- [ ] Dependencies installed

### Step 3: Run Migrations
```bash
php artisan migrate --force
```
- [ ] All migrations completed
- [ ] No migration errors in logs

### Step 4: Backfill Data
```bash
php artisan seo:backfill-media-dimensions
php artisan seo:backfill-slugs
```
- [ ] Media dimensions backfilled
- [ ] Property slugs backfilled

### Step 5: Clear Caches (CRITICAL FOR SECURITY)
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```
- [ ] All caches cleared

### Step 6: Rebuild Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
- [ ] Config cached
- [ ] Routes cached
- [ ] Views cached

### Step 7: Generate Sitemap
```bash
php artisan seo:sitemap-generate
```
- [ ] Sitemap generated at `public/sitemap.xml`

### Step 8: Reload Web Server
```bash
sudo systemctl reload nginx
# or: sudo systemctl reload apache2
```
- [ ] Web server reloaded without errors

---

## ✓ Post-Deployment Verification

### Security Verification (CRITICAL)
```bash
curl -I https://tdhh.co.uk/telescope
curl -I https://tdhh.co.uk/_debugbar/open
```
- [ ] `/telescope` returns 404 or 301/302 (NOT 200)
- [ ] `/_debugbar/open` returns 404 or 301/302 (NOT 200)

### Page Load Verification
```bash
curl -I https://tdhh.co.uk/
curl -I https://tdhh.co.uk/property
curl -I https://tdhh.co.uk/contact
curl -I https://tdhh.co.uk/partners/property
```
- [ ] Home page: 200
- [ ] Property listing: 200
- [ ] Contact page: 200
- [ ] Partner landing: 200

### Metadata Verification
```bash
curl https://tdhh.co.uk/ | grep -i "og:title"
curl https://tdhh.co.uk/ | grep -i "og:description"
curl https://tdhh.co.uk/ | grep -i "schema.org"
```
- [ ] OG title present
- [ ] OG description present
- [ ] JSON-LD schema present

### Configuration Verification
```bash
php artisan config:get app.debug              # Should be: false
php artisan config:get debugbar.enabled       # Should be: false
php artisan config:get telescope.enabled      # Should be: false
```
- [ ] APP_DEBUG is false
- [ ] DebugBar disabled
- [ ] Telescope disabled

### Diagnostics
```bash
php artisan seo:diagnose
```
- [ ] All FLAGS pass (no CRITICAL warnings)
- [ ] Zero properties expected if database empty
- [ ] Media columns present
- [ ] Slug columns present

### Log Check
```bash
tail -50 storage/logs/laravel.log
```
- [ ] No ERROR or ALERT entries
- [ ] No "500" error codes
- [ ] No authentication failures

---

## ✓ Rollback Plan

If critical issues occur, rollback is straightforward:

```bash
# 1. Restore database
mysql tdhh < backups/tdhh_backup_YYYYMMDD_HHMMSS.sql

# 2. Restore previous code
git reset --hard HEAD~6

# 3. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Reload web server
sudo systemctl reload nginx
```

- [ ] Rollback documented and accessible to operator
- [ ] Previous code backup available
- [ ] Database backup accessible

---

## ✓ Post-Deployment Tasks

### Monitoring (24-48 hours)
- [ ] Error logs monitored for new issues
- [ ] Uptime monitored (check monitoring service)
- [ ] 404 error rate normal (no spike from broken redirects)
- [ ] Response times acceptable (cache should improve performance)

### Search Console
- [ ] Submit new sitemap: https://search.google.com/search-console
- [ ] Check for crawl errors related to 301 redirects
- [ ] Monitor indexing status

### Analytics
- [ ] Verify tracking still working
- [ ] Check traffic patterns normal
- [ ] Monitor bounce rate

### Team Notification
- [ ] Development team notified of successful deployment
- [ ] Diagnostics output shared: `php artisan seo:diagnose --json`
- [ ] Any issues logged with team

---

## Sign-Off

**Deployment Operator:** ________________________  
**Date/Time:** ________________________  
**All Checks Passed:** [ ] YES  [ ] NO  

**Issues Encountered (if any):**
```
(Describe any issues or special notes)
```

**Notes for Future Reference:**
```
(Any observations for next deployment)
```

---

**Approved for Production:** [ ] YES  [ ] NO  

**Approval By:** ________________________  
**Date:** ________________________
