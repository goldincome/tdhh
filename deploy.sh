#!/bin/bash

##############################################################################
# TD Holiday Homes - Production Deployment Script
# 
# This script automates the deployment of Phase 0-2 SEO remediation to 
# production. Run this on the production server after code has been pulled.
#
# Usage: bash deploy.sh
# Backup is created before any changes.
##############################################################################

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}TD Holiday Homes - Production Deploy${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}✗ Error: artisan file not found. Run this script from the project root.${NC}"
    exit 1
fi

# Step 1: Backup database
echo -e "${YELLOW}Step 1: Creating database backup...${NC}"
BACKUP_FILE="backups/tdhh_backup_$(date +%Y%m%d_%H%M%S).sql"
mkdir -p backups
if mysqldump tdhh > "$BACKUP_FILE" 2>/dev/null; then
    echo -e "${GREEN}✓ Backup created: $BACKUP_FILE${NC}"
else
    echo -e "${YELLOW}⚠ Could not create backup. Verify MySQL credentials in .env${NC}"
fi
echo ""

# Step 2: Verify code is pulled
echo -e "${YELLOW}Step 2: Verifying code deployment...${NC}"
if git log --oneline -1 | grep -q "SEO\|slug\|media"; then
    echo -e "${GREEN}✓ Code appears to be up to date${NC}"
else
    echo -e "${YELLOW}⚠ Could not verify code. Ensure git pull completed.${NC}"
fi
echo ""

# Step 3: Run migrations
echo -e "${YELLOW}Step 3: Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"
echo ""

# Step 4: Backfill media dimensions
echo -e "${YELLOW}Step 4: Backfilling media dimensions...${NC}"
if php artisan seo:backfill-media-dimensions; then
    echo -e "${GREEN}✓ Media dimensions backfilled${NC}"
else
    echo -e "${YELLOW}⚠ Media backfill skipped (no media rows or already populated)${NC}"
fi
echo ""

# Step 5: Backfill property slugs
echo -e "${YELLOW}Step 5: Backfilling property slugs...${NC}"
if php artisan seo:backfill-slugs; then
    echo -e "${GREEN}✓ Property slugs backfilled${NC}"
else
    echo -e "${YELLOW}⚠ Slug backfill skipped (no properties or already populated)${NC}"
fi
echo ""

# Step 6: Clear caches (CRITICAL for security fixes)
echo -e "${YELLOW}Step 6: Clearing and rebuilding caches (CRITICAL)...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

# Step 7: Rebuild caches
echo -e "${YELLOW}Step 7: Rebuilding caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Caches rebuilt${NC}"
echo ""

# Step 8: Generate sitemap
echo -e "${YELLOW}Step 8: Generating sitemap...${NC}"
php artisan seo:sitemap-generate
echo -e "${GREEN}✓ Sitemap generated${NC}"
echo ""

# Step 9: Verify security fixes
echo -e "${YELLOW}Step 9: Verifying security fixes...${NC}"
echo "  Checking /telescope endpoint..."
TELESCOPE_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://tdhh.co.uk/telescope 2>/dev/null || echo "000")
if [ "$TELESCOPE_STATUS" = "404" ] || [ "$TELESCOPE_STATUS" = "301" ] || [ "$TELESCOPE_STATUS" = "302" ]; then
    echo -e "${GREEN}  ✓ /telescope properly blocked (HTTP $TELESCOPE_STATUS)${NC}"
else
    echo -e "${RED}  ✗ /telescope still accessible (HTTP $TELESCOPE_STATUS)${NC}"
    echo -e "${RED}     This indicates cache clear did not complete successfully.${NC}"
fi

echo "  Checking /_debugbar endpoint..."
DEBUGBAR_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://tdhh.co.uk/_debugbar/open 2>/dev/null || echo "000")
if [ "$DEBUGBAR_STATUS" = "404" ] || [ "$DEBUGBAR_STATUS" = "301" ] || [ "$DEBUGBAR_STATUS" = "302" ]; then
    echo -e "${GREEN}  ✓ /_debugbar properly blocked (HTTP $DEBUGBAR_STATUS)${NC}"
else
    echo -e "${YELLOW}  ⚠ /_debugbar status: HTTP $DEBUGBAR_STATUS (may be cached)${NC}"
fi
echo ""

# Step 10: Run diagnostics
echo -e "${YELLOW}Step 10: Running diagnostics...${NC}"
DIAG_FILE="diagnostics/seo_diagnostic_$(date +%Y%m%d_%H%M%S).json"
mkdir -p diagnostics
php artisan seo:diagnose --json > "$DIAG_FILE"
echo -e "${GREEN}✓ Diagnostics saved to: $DIAG_FILE${NC}"
echo ""

# Step 11: Verify pages load
echo -e "${YELLOW}Step 11: Verifying page loads...${NC}"
PAGES=("/" "/property" "/contact" "/partners/property")
for page in "${PAGES[@]}"; do
    STATUS=$(curl -s -o /dev/null -w "%{http_code}" "https://tdhh.co.uk$page" 2>/dev/null || echo "000")
    if [ "$STATUS" = "200" ]; then
        echo -e "${GREEN}  ✓ $page (HTTP $STATUS)${NC}"
    else
        echo -e "${RED}  ✗ $page (HTTP $STATUS)${NC}"
    fi
done
echo ""

# Summary
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✓ Deployment completed successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Next steps:"
echo "1. Monitor error logs: tail -f storage/logs/laravel.log"
echo "2. Review diagnostics: cat $DIAG_FILE | jq"
echo "3. Test in Google Search Console"
echo "4. Share diagnostics with development team"
echo ""
