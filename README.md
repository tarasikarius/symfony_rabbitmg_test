rabbit
======

A Symfony project created on September 19, 2017, 11:28 am.


to send url:
 php bin/console rabbitmq:url-parser:emit http://www.rollingstone.com/music/rss
 
recieve and parse:
php bin/console rabbitmq:consumer parse_url