#!/bin/bash --

set -e

# Select test suite
case "${DB}" in
	mariadb|mysql)
		suite=MySQL
		;;
	postgresql)
		suite=PostgreSQL
		;;
	*)
		suite=
		;;
esac

# Run phpunit
if [[ ! -z "${suite}" ]]; then
	vendor/bin/phpunit --testsuite "${suite}"
else
	vendor/bin/phpunit
fi
