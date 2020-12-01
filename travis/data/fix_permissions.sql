CREATE USER IF NOT EXISTS travis@localhost;
GRANT ALL ON *.* TO travis@localhost;
FLUSH PRIVILEGES;
