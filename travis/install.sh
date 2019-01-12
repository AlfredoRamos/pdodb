#!/bin/bash --

set -e

# Install composer dependencies
travis_retry composer install --prefer-dist --no-interaction
