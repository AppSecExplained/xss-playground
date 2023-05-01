# xss-playground
A basic webapp to test XSS payloads.

This app is obviously not secure, don't do things like expose it to the internet or run on production systems. 

# How to run

```
git clone https://github.com/AppSecExplained/xss-playground.git
cd xss-playground
sudo docker-compose up --build
```
Browse to http://localhost:3000

Note: The first time you run it, you'll prob need to click the link to /reset.php that sets up the DB.
