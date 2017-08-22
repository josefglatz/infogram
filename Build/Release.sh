#!/bin/bash
EXT_VERSION="$(git describe --tags $(git rev-list --tags --max-count=1))"

echo "Cleanup"
rm -rf .build

echo "Build ext_emconf.php"
EXT_VERSION="$EXT_VERSION" ./Build/GenerateExtEmConf.php

if [ ! -d "./../Resources/Private/Contrib/Libraries/infogram" ]; then
  echo "Dependencies for non-composer mode are missing!"
  echo "Please run \"composer install\" in ./Resources/Private/Contrib/Libraries/ and re-run the release script."
  echo ""
  echo "Or simply run \"composer release\" in the root of the extension instead of directly using this shell script."
  exit 1
fi

echo "Package as zip"
mkdir Export; zip -9 -r --exclude=".editorconfig" --exclude="*.git*" --exclude=".build/*" --exclude=".idea/*" --exclude="Tests/*" --exclude="Export/*" Export/html_compress_$EXT_VERSION.zip .
