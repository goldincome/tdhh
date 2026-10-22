# Post-Deployment Monitoring Checklist

**Deployment Date:** ________________  
**Monitoring Period:** 24-72 hours post-deployment  
**Responsible:** Operations / DevOps Team  

---

## 📊 Hour 1-4: Immediate Verification

### Security Checks (CRITICAL)
- [ ] `/telescope` returns 404 or 301/302: `curl -I https://tdhh.co.uk/telescope`
- [ ] `/_debugbar/open` returns 404 or 301/302: `curl -I https://tdhh.co.uk/_debugbar/open`
- [ ] No DebugBar in page source: `curl https://tdhh.co.uk/ | grep -i debugbar | grep -v noindex`
- [ ] No Telescope routes in route list: `php artisan route:list | grep telescope`

### Application Health
- [ ] Home page loads (200): `curl -I https://tdhh.co.uk/`
- [ ] Listing page loads (200): `curl -I https://tdhh.co.uk/property`
- [ ] Contact page loads (200): `curl -I https://tdhh.co.uk/contact`
- [ ] Partner landing loads (200): `curl -I https://tdhh.co.uk/partners/property`
- [ ] 301 redirect works: `curl -I https://tdhh.co.uk/property/1` (should show 301)

### Metadata Verification
- [ ] Home has `og:title`: `curl https://tdhh.co.uk/ | grep 'og:title'`
- [ ] Home has `og:description`: `curl https://tdhh.co.uk/ | grep 'og:description'`
- [ ] Home has `rel=canonical`: `curl https://tdhh.co.uk/ | grep 'rel=canonical'`
- [ ] Home has JSON-LD: `curl https://tdhh.co.uk/ | grep 'schema.org'`
- [ ] Robots meta set correctly: `curl https://tdhh.co.uk/ | grep 'robots'`

### Configuration Verification
- [ ] `APP_DEBUG=false`: `php artisan config:get app.debug`
- [ ] `DebugBar disabled`: `php artisan config:get debugbar.enabled`
- [ ] `Telescope disabled`: `php artisan config:get telescope.enabled`
- [ ] Database accessible: `php artisan tinker --execute "echo DB::connection()->getPdo() ? 'OK' : 'FAIL';"`

### Log Check (First 4 Hours)
```bash
tail -50 storage/logs/laravel.log
```
- [ ] No ERROR or FATAL entries
- [ ] No "500" errors
- [ ] No authentication failures
- [ ] No database connection errors

**Status:** ✅ Pass / ❌ Fail  
**Notes:** ________________________________

---

## 📈 Hour 4-24: Performance & Functionality

### Page Performance (Lighthouse)
Test with: https://web.dev/measure/
- [ ] Home page Lighthouse score: ___/100
- [ ] Listing page Lighthouse score: ___/100
- [ ] Largest Contentful Paint (LCP): ___ seconds (target: <2.5s)
- [ ] Cumulative Layout Shift (CLS): ___ (target: <0.1)

### Core Web Vitals
- [ ] First Input Delay (FID) acceptable
- [ ] LCP < 2.5 seconds
- [ ] CLS < 0.1
- [ ] Overall CWV status: GREEN / YELLOW / RED

### Traffic & Error Monitoring
```bash
# Check for error spikes
grep -c "ERROR\|FATAL" storage/logs/laravel.log
```
- [ ] Error rate normal (no spike)
- [ ] Response time normal (compare to pre-deployment)
- [ ] CPU usage normal (<70%)
- [ ] Memory usage normal (<80%)
- [ ] Disk space adequate (>10% free)

### Database Health
```bash
php artisan seo:diagnose
```
- [ ] Media table dimensions correct: `media_has_width_column = YES`
- [ ] Slug columns exist: `slug_column_exists = YES`
- [ ] No missing slugs: `rows_with_empty_slug = 0`
- [ ] No duplicate slugs: `duplicate_slugs = 0`

### SEO Verification
- [ ] Sitemap generated: `curl -I https://tdhh.co.uk/sitemap.xml` (should be 200)
- [ ] robots.txt correct: `curl https://tdhh.co.uk/robots.txt | head -20`
- [ ] No crawl errors: Check Google Search Console
- [ ] Indexing status normal: Check Google Search Console

### User Functionality
- [ ] Property listing page displays correctly
- [ ] Search works (if applicable)
- [ ] Filters work (if applicable)
- [ ] Pagination works: Check `/property?page=2`
- [ ] Contact form works
- [ ] 301 redirects preserve query string: Test `/property/1?check_in_date=2026-11-01`

**Status:** ✅ Pass / ❌ Fail  
**Notes:** ________________________________

---

## 📊 Hour 24-72: Extended Monitoring

### Search Console Checks
- [ ] No increase in "Crawl errors"
- [ ] No "Mobile usability" issues
- [ ] Sitemap processed: Compare before/after
- [ ] Indexing rate stable or improving

### Analytics (If Available)
- [ ] Session count: ___ (compare to baseline)
- [ ] Bounce rate: ___% (should not spike)
- [ ] Average session duration: ___ (should not drop)
- [ ] Page load time: ___ seconds (should improve or stay same)

### Email/Support Monitoring
- [ ] No increase in support tickets about broken links
- [ ] No increase in 404 errors reported by users
- [ ] No complaints about slow pages

### Cache Effectiveness
- [ ] HTTP 200 with cache headers: `curl -I https://tdhh.co.uk/ | grep Cache-Control`
- [ ] Cache-Control shows: `public, max-age=3600, s-maxage=86400` or similar
- [ ] No "Cache hit ratio" decrease

### Availability Monitoring
- [ ] Uptime: ___ % (target: 99.9%)
- [ ] Response time p95: ___ ms (baseline: ___ ms)
- [ ] Response time p99: ___ ms (baseline: ___ ms)

**Status:** ✅ Pass / ❌ Fail  
**Notes:** ________________________________

---

## 🔍 Issue Tracking

### Issues Found

| # | Issue | Severity | Status | Resolution |
|---|-------|----------|--------|-----------|
| 1 | | HIGH/MED/LOW | New/In Progress/Resolved | |
| 2 | | | | |
| 3 | | | | |

---

## 🚨 Incident Response

If CRITICAL issues found:

### Immediate Action (Within 1 Hour)
1. [ ] Document issue in detail
2. [ ] Capture logs and diagnostics
3. [ ] Notify team
4. [ ] Decide: Fix in-place or rollback
5. [ ] If rollback: Follow rollback procedure in TROUBLESHOOTING.md

### Fix in-place:
- [ ] Apply hotfix
- [ ] Test thoroughly
- [ ] Deploy to production
- [ ] Re-run critical checks

### Rollback:
- [ ] Run rollback script
- [ ] Verify previous version works
- [ ] Investigate root cause
- [ ] Plan fix for re-deployment

---

## ✅ Sign-Off

### After 72 Hours Monitoring

**Overall Status:**
- [ ] All critical checks PASS
- [ ] No ERROR or FATAL logs
- [ ] No significant performance degradation
- [ ] User reports: None / Minimal
- [ ] Ready for business-as-usual operations

**Performance Summary:**
- Baseline load time: ___ ms
- Current load time: ___ ms
- Change: ___ % (positive = faster)

**SEO Status:**
- Sitemap submitted: YES / NO
- Search Console crawl errors: ___ (baseline: ___)
- Indexing status: ___
- Top search keywords: ___

**Deployment Verified By:** ________________________  
**Date/Time:** ________________________  
**Approval:** ✅ APPROVED / ❌ NEEDS ATTENTION  

---

## 📋 Ongoing Monitoring (Daily)

After the 72-hour intensive monitoring period:

### Daily Checks (Automated if possible)
```bash
#!/bin/bash
# Save as: /usr/local/bin/tdhh-daily-check.sh

LOG="/var/log/tdhh-daily-check.log"
DATE=$(date '+%Y-%m-%d %H:%M:%S')

# 1. Check /telescope blocked
TELESCOPE=$(curl -s -o /dev/null -w "%{http_code}" https://tdhh.co.uk/telescope)
echo "[$DATE] Telescope endpoint: HTTP $TELESCOPE" >> $LOG
[ "$TELESCOPE" != "200" ] || echo "[$DATE] WARNING: Telescope returns 200!" >> $LOG

# 2. Check home page
HOME=$(curl -s -o /dev/null -w "%{http_code}" https://tdhh.co.uk/)
echo "[$DATE] Home page: HTTP $HOME" >> $LOG
[ "$HOME" = "200" ] || echo "[$DATE] ERROR: Home page not 200!" >> $LOG

# 3. Check error count
ERRORS=$(grep -c "ERROR\|FATAL" /var/log/laravel.log 2>/dev/null || echo "0")
echo "[$DATE] Error count: $ERRORS" >> $LOG

# 4. Check database
php artisan tinker --execute "echo 'DB OK'" >> $LOG 2>&1

echo "[$DATE] Daily check complete" >> $LOG
```

Add to crontab:
```bash
0 9 * * * /usr/local/bin/tdhh-daily-check.sh
```

### Weekly Checks
- [ ] Google Search Console review
- [ ] Analytics review
- [ ] Log analysis
- [ ] Performance metrics comparison
- [ ] Security audit

### Monthly Checks
- [ ] Full `seo:diagnose` run
- [ ] SEO ranking check (Google rank tracker)
- [ ] Crawlability audit
- [ ] Schema validation
- [ ] Backlink analysis

---

## 📞 Escalation

**If monitoring reveals issues:**

| Issue | Action | Timeline |
|-------|--------|----------|
| `/telescope` returns 200 | Escalate immediately | <1 hour |
| DebugBar visible | Escalate immediately | <1 hour |
| Home page 500+ error | Escalate immediately | <1 hour |
| >5% error rate increase | Escalate to Level 2 | <4 hours |
| Performance >2x slower | Escalate to Level 2 | <8 hours |
| 301 redirects broken | Escalate to Level 2 | <8 hours |
| Metadata missing | Escalate to Level 3 | <24 hours |
| Static check failures | Escalate to Level 3 | <48 hours |

---

**Contact Info:**
- Dev Team Lead: ___________________________ Phone: ___________
- Operations: ___________________________ Phone: ___________
- On-Call: ___________________________ Phone: ___________

---

*Use this checklist to verify deployment success and catch any regressions early.*
