Behat Workshop
===

Slides: https://speakerdeck.com/naxhh/behat-and-mink-workshop

Main objectives:
---
- Learn main concepts
- Understand how behat parses features
- Understand what suites are
- Learn what Mink is and how to use it with behat

Requirements
---
- PHP 5.5
- Composer
- [Selenium 2 RC Server](http://docs.seleniumhq.org/download/)

How to run it
---
*Edit behat.yml and add your server ip*

Start server:
```
/var/www/kittylove $ php -S <your-server-ip> -t web web/index.php

```

Start selenium (JS tests only):
```
/var/www/kittylove $ java -jar selenium-server-standalone-2.42.2.jar
```
