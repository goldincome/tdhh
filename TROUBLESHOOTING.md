# Troubleshooting Guide - TD Holiday Homes SEO Deployment

## Common Issues & Solutions

### 🔴 Critical Issues

#### `/telescope` Still Accessible (Status 200)
**Symptom:** `curl https://tdhh.co.uk/telescope` returns HTTP 200  
**Root Cause:** Route cache not cleared properly  
**Solution:**
```bash
php artisan route:clear
php artisan route:cache
sudo systemctl reload nginx
# Verify: curl -I https://tdhh.co.uk/telescope  # Should be 404 or 301
```

#### `/_debugbar/open` Still Returning Data
**Symptom:** `curl https://tdhh.co.uk/_debugbar/open` returns JSON  
**Root Cause:** Config cache stale  
**Solution:**
```bash
php artisan config:clear
php artisan config:cache
sudo systemctl reload nginx
# Verify: curl https://tdhh.co.uk/_debugbar/open | head -c 100
```

#### Migration Fails with "Table Already Exists"
**Symptom:** `SQLSTATE[42S01]: Table 'tdhh.media' already exists`  
**Root Cause:** Migration already ran, trying to run again  
**Solution:**
```bash
php artisan migrate:status
# If already migrated, safely skip: nothing to do
# If stuck: php artisan migrate:reset --force && php artisan migrate --force
```

#### Property Slugs Not Backfilled
**Symptom:** `php artisan seo:diagnose` shows `rows_with_empty_slug: 5`  
**Root Cause:** Properties don't have names or backfill didn't run  
**Solution:**
```bash
# Check if properties exist:
php artisan tinker --execute "echo \App\Models\Property\Property::count();"

# If 0: no data to backfill (expected for fresh install)
# If >0: run backfill
php artisan seo:backfill-slugs

# Verify:
php artisan tinker --execute "echo \App\Models\Property\Property::whereNull('slug')->count();"
# Should return: 0
```

---

### 🟡 Deployment Issues

#### Pages Show Old Metadata
**Symptom:** Home page title still shows "Welcome | TD Holiday Homes" (old format)  
**Root Cause:** View cache stale  
**Solution:**
```bash
php artisan view:clear
# Pages should update immediately on next request
```

#### Database Migration Hangs
**Symptom:** `php artisan migrate --force` hangs for >5 minutes  
**Root Cause:** Slow I/O or locked database  
**Solution:**
```bash
# Check if other processes are using database:
mysql -u root -p tdhh -e "SHOW PROCESSLIST;"

# Kill long-running queries if stuck:
mysql -u root -p tdhh -e "KILL QUERY <id>;"

# Retry migration with verbose output:
php artisan migrate --force -v
```

#### Deploy Script Fails on `deploy.sh` Execution
**Symptom:** `bash: deploy.sh: Permission denied`  
**Root Cause:** Script not executable  
**Solution:**
```bash
chmod +x deploy.sh
bash deploy.sh
```

---

### 🟠 Configuration Issues

#### APP_DEBUG Still True in Production
**Symptom:** `php artisan config:get app.debug` returns `true`  
**Root Cause:** .env not updated or config not cached  
**Solution:**
```bash
# Update .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env

# Rebuild config cache
php artisan config:clear
php artisan config:cache

# Verify:
php artisan config:get app.debug  # Should be: false
```

#### Database Connection Fails
**Symptom:** `SQLSTATE[HY000]: General error: 1030 Got error 1 from storage engine`  
**Root Cause:** Database credentials wrong or database down  
**Solution:**
```bash
# Test connection:
php artisan tinker --execute "echo DB::connection()->getPdo() ? 'OK' : 'FAIL';"

# If FAIL:
# 1. Verify .env credentials: grep "DB_" .env
# 2. Test MySQL directly: mysql -u root -p -e "USE tdhh; SELECT 1;"
# 3. Check MySQL is running: systemctl status mysql
```

#### Cache Driver Not Working
**Symptom:** `cache:clear` fails or cache not persisting  
**Root Cause:** Redis down or file permissions  
**Solution:**
```bash
# If using Redis:
redis-cli ping  # Should return: PONG

# If using file driver:
ls -la bootstrap/cache/  # Should be writable
chmod 755 bootstrap/cache/

# Rebuild caches:
php artisan cache:clear
php artisan cache:forget cache_key
```

---

### 🟡 Performance Issues

#### Slow Page Loads After Deploy
**Symptom:** Home page takes >3 seconds to load  
**Root Cause:** Caches not rebuilt, queries not optimized  
**Solution:**
```bash
# Ensure all caches are rebuilt:
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check query performance:
php artisan tinker
>>> DB::enableQueryLog();
>>> \App\Models\Property\Property::limit(10)->get();
>>> dd(DB::getQueryLog());

# If slow: add indexes
php artisan migrate --force
```

#### High Memory Usage
**Symptom:** `php-fpm` process uses >500MB RAM  
**Root Cause:** Memory leak or large collection operations  
**Solution:**
```bash
# Check memory usage:
ps aux | grep php-fpm

# Restart PHP-FPM:
sudo systemctl restart php-fpm

# Check for memory leaks in logs:
tail -100 storage/logs/laravel.log | grep -i "memory\|fatal"
```

---

### 🔵 SEO & Content Issues

#### Sitemap Not Generating
**Symptom:** `curl https://tdhh.co.uk/sitemap.xml` returns 404  
**Root Cause:** Command not run or file not created  
**Solution:**
```bash
# Generate sitemap:
php artisan seo:sitemap-generate

# Verify file exists:
ls -la public/sitemap.xml

# If still 404: check nginx config allows .xml files
```

#### JSON-LD Schema Invalid
**Symptom:** Google Search Console reports schema errors  
**Root Cause:** Invalid JSON or missing required fields  
**Solution:**
```bash
# Test schema with curl:
curl https://tdhh.co.uk/ | grep -A5 "application/ld+json" | head -20

# Validate with Google's tool:
# https://search.google.com/structured-data/testing-tool

# Check Seo class for field mappings:
grep -A10 "LocalBusiness" app/Support/Seo.php
```

#### Missing Open Graph Images
**Symptom:** Social cards don't show images  
**Root Cause:** Image not found or dimensions wrong  
**Solution:**
```bash
# Check image exists and is readable:
ls -la public/img/bgs/home-sample-2.jpg
file public/img/bgs/home-sample-2.jpg

# Verify dimensions:
php artisan tinker --execute "echo json_encode(getimagesize(public_path('img/bgs/home-sample-2.jpg')));"
# Should output: [1200, 675, ...]

# Re-test with curl:
curl https://tdhh.co.uk/ | grep "og:image"
```

---

### 🟢 Monitoring & Verification

#### How to Check If Deployment Succeeded
```bash
# 1. Security (most important)
curl -I https://tdhh.co.uk/telescope
# ✅ Should be: 404 or 301/302 (NOT 200)

# 2. Pages load
curl -I https://tdhh.co.uk/
# ✅ Should be: 200

# 3. Metadata present
curl https://tdhh.co.uk/ | grep "og:title\|og:description\|schema.org"
# ✅ Should find all three

# 4. Caches built
php artisan config:get app.debug
# ✅ Should be: false

# 5. Database working
php artisan tinker --execute "echo \App\Models\Property\Property::count();"
# ✅ Should return: 0 (or count if properties exist)
```

#### Daily Health Checks (Automated)
```bash
#!/bin/bash
# Add to crontab for daily monitoring

LOG_FILE="/var/log/tdhh-health.log"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

# Check /telescope is blocked
TELESCOPE=$(curl -s -o /dev/null -w "%{http_code}" https://tdhh.co.uk/telescope)
echo "[$TIMESTAMP] /telescope: HTTP $TELESCOPE" >> $LOG_FILE

# Check home page loads
HOME_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://tdhh.co.uk/)
echo "[$TIMESTAMP] Home: HTTP $HOME_STATUS" >> $LOG_FILE

# Check error logs for spikes
ERROR_COUNT=$(grep -c "ERROR\|FATAL" /var/log/laravel.log | tail -1)
echo "[$TIMESTAMP] Errors today: $ERROR_COUNT" >> $LOG_FILE
```

---

## Escalation Matrix

### Level 1: Self-Service (Check These First)
1. Run `php artisan seo:diagnose` → look for CRITICAL flags
2. Check `storage/logs/laravel.log` → look for ERROR or FATAL
3. Verify `.env` values match production requirements
4. Run deployment verification steps from `DEPLOYMENT_CHECKLIST.md`

### Level 2: Debug Commands
```bash
php artisan config:get app.debug        # Should be: false
php artisan config:get debugbar.enabled # Should be: false
php artisan config:get telescope.enabled # Should be: false
curl -I https://tdhh.co.uk/telescope    # Should be: 404/301/302
php artisan tinker --execute "echo DB::connection()->getPdo() ? 'OK' : 'FAIL';"
```

### Level 3: Detailed Diagnostics
```bash
php artisan seo:diagnose --json > /tmp/diagnostics.json
git log --oneline -10
php artisan route:list | grep -E "telescope|debugbar|property"
tail -100 storage/logs/laravel.log
```

### Level 4: Escalate
If Level 1-3 don't resolve:
1. Capture all outputs from above
2. Include `seo:diagnose --json` output
3. Include last 50 lines of `storage/logs/laravel.log`
4. Share with development team with timestamp of issue

---

## Quick Reference

| Problem | Command | Expected Result |
|---------|---------|-----------------|
| Clear all caches | `php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear` | Output: "Cache cleared" |
| Rebuild all caches | `php artisan config:cache && php artisan route:cache && php artisan view:cache` | Output: "Cache updated" |
| Test database | `php artisan tinker --execute "echo DB::table('properties')->count();"` | Integer count |
| Generate sitemap | `php artisan seo:sitemap-generate` | "Sitemap generated: ..." |
| Check security | `curl -I https://tdhh.co.uk/telescope` | 404 or 301/302 (NOT 200) |
| Health check | `php artisan seo:diagnose` | No CRITICAL flags |
| Verify metadata | `curl https://tdhh.co.uk/ \| grep og:title` | `content="..."` |

---

## When to Rollback

Rollback if:
- ❌ `/telescope` still returns 200 (security breach)
- ❌ Home page returns 500+ errors
- ❌ Database inaccessible after migration
- ❌ >5% error rate increase in logs

Rollback procedure:
```bash
# 1. Restore previous code
git reset --hard HEAD~6

# 2. Restore database (if needed)
mysql tdhh < backups/tdhh_backup_YYYYMMDD_HHMMSS.sql

# 3. Rebuild caches
php artisan config:cache && php artisan route:cache

# 4. Reload web server
sudo systemctl reload nginx

# 5. Verify
curl -I https://tdhh.co.uk/  # Should be 200
```

---

*For additional help, consult HANDOFF.md, PRODUCTION_ENV.md, or DEPLOYMENT_CHECKLIST.md*
