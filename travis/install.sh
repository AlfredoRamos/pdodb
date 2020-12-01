#!/bin/bash --

set -e

# Install composer dependencies
travis_retry composer update --prefer-dist --no-interaction
