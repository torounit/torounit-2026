#!/usr/bin/env bash
#
# Build the distributable theme zip and the update manifest (update.json)
# that inc/updater.php reads from the latest GitHub Release.
#
# Usage: .github/scripts/build-release.sh [expected-version] [out-dir]
#
set -euo pipefail

SLUG="torounit-2026"
REPO="torounit/torounit-2026"
EXPECTED_VERSION="${1:-}"
OUT_DIR="${2:-dist}"

cd "$(git rev-parse --show-toplevel)"

# Read a header value from style.css, e.g. `header "Requires PHP"`.
header() {
	sed -n "s/^[[:space:]]*\*[[:space:]]*$1:[[:space:]]*//p" style.css | head -n 1 | tr -d '\r'
}

VERSION="$(header "Version")"
if [[ -z "$VERSION" ]]; then
	echo "error: Version header not found in style.css" >&2
	exit 1
fi
if [[ -n "$EXPECTED_VERSION" && "$VERSION" != "$EXPECTED_VERSION" ]]; then
	echo "error: style.css Version ($VERSION) does not match release ($EXPECTED_VERSION)" >&2
	exit 1
fi

mkdir -p "$OUT_DIR"

# git archive honours export-ignore in .gitattributes.
git archive --format=zip --prefix="$SLUG/" -o "$OUT_DIR/$SLUG.zip" HEAD

jq -n \
	--arg version "$VERSION" \
	--arg package "https://github.com/$REPO/releases/download/$VERSION/$SLUG.zip" \
	--arg url "https://github.com/$REPO/releases/tag/$VERSION" \
	--arg requires "$(header "Requires at least")" \
	--arg requires_php "$(header "Requires PHP")" \
	--arg tested "$(header "Tested up to")" \
	'{version: $version, package: $package, url: $url, requires: $requires, requires_php: $requires_php, tested: $tested}' \
	> "$OUT_DIR/update.json"

echo "Built $OUT_DIR/$SLUG.zip and $OUT_DIR/update.json for $VERSION"
