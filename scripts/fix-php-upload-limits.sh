#!/usr/bin/env bash
# افزایش سقف آپلود PHP (آپاچی سیستم + CLI) — یک‌بار با sudo اجرا کنید.
set -euo pipefail

for INI in /etc/php/8.2/apache2/php.ini /etc/php/8.2/cli/php.ini; do
  if [[ ! -f "$INI" ]]; then
    echo "skip: $INI not found"
    continue
  fi
  sudo sed -i 's/^\s*upload_max_filesize\s*=.*/upload_max_filesize = 256M/' "$INI"
  sudo sed -i 's/^\s*post_max_size\s*=.*/post_max_size = 512M/' "$INI"
  echo "updated: $INI"
  grep -E '^\s*(upload_max_filesize|post_max_size)\s*=' "$INI"
done

echo "Restart Apache if needed: sudo systemctl restart apache2"
