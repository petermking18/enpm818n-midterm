#!/bin/bash

WORK_DIR=${1:-.}
# we need to base64 encode like base64 -i cf.sh and copy that during stack deployment
# CloudFront URL
CLOUDFRONT_URL="https://d289y04b6inta0.cloudfront.net/assets"

# we need to run at /var/www/html (from userdata in cft)
find "$WORK_DIR" -type f -name "*.php" | while read -r file; do
    # Replace ./assets and ../assets with the CloudFront URL
    sed -i -E "s|([\"'])\.{1,2}/assets|\\1$CLOUDFRONT_URL|g" "$file"
    echo "Processed: $file"
done

echo "Replacement completed!"
