#!/usr/bin/env bash
# کپی تغییرات از rentnaab به rentnaab-github (قبل از build/push/deploy از پوشه github)
set -euo pipefail

SRC="/opt/lampp/htdocs/rentnaab"
DST="/opt/lampp/htdocs/rentnaab-github"

if [[ ! -d "$SRC" || ! -d "$DST" ]]; then
  echo "مسیر SRC یا DST پیدا نشد."
  exit 1
fi

if ! touch "$DST/.write-test" 2>/dev/null; then
  echo "خطا: روی $DST دسترسی نوشتن ندارید."
  echo "  sudo chown -R \"\$(whoami):\$(whoami)\" \"$DST\""
  exit 1
fi
rm -f "$DST/.write-test"

echo "==> rsync از $SRC به $DST"
rsync -a --delete \
  --exclude '.git/' \
  --exclude '.env' \
  --exclude '.env.*' \
  --exclude 'node_modules/' \
  --exclude 'vendor/' \
  --exclude 'storage/logs/' \
  --exclude 'storage/framework/cache/' \
  --exclude 'storage/framework/sessions/' \
  --exclude 'storage/framework/views/' \
  --exclude 'storage/debugbar/' \
  --exclude 'public/files/' \
  --exclude 'public/hot/' \
  --exclude 'public/storage/' \
  --exclude 'repo-git.tar.gz' \
  --exclude 'test-write-github-sync' \
  --exclude '.idea/' \
  --exclude '.vscode/' \
  "$SRC/" "$DST/"

echo ""
echo "==> انجام شد. حالا:"
echo "  cd $DST && npm run production"
echo "  git add -A && git commit && git push github main"
