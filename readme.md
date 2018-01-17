# Aerys and MySQL tester

This is to check that you can run Aerys applications, and have a working MySQL setup.

* install PHP ^7
* install MySQL (^5.7 is fine)

Then run:

```
git clone git@github.com:asyncphp/test-aerys-mysql.git
cd test-aerys-mysql
composer install
composer start
```

Open `127.0.0.1:8888` in your browser. You should see "Aerys works", along with a form to fill in. Fill the form in and
click "test database". You should now see "And so does MySQL..." or an error signalling a problem with your setup or
credentials.
