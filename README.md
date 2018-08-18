# hlsp2p-php-tracker
## Updated For V0.2.x
       Just A SIMPLE Tracker!(Test Use Only)
       Just A SIMPLE Tracker!(Test Use Only)
       Just A SIMPLE Tracker!(Test Use Only)
       No performance guarantee!

       a tracker for HLSjs-p2p-engine written by PHP
       1.Install Nginx+PHP enviroment,whatever you are using Windows OR Linux(Better)
       2.Set the Nginx rewrite rule 

```C
	rewrite ^/v1/channel$ /index.php?command=channel last;
	rewrite ^/v1/channel/(.*)/node/(.*)/(.*)$ /index.php?command=$3&node=$2&channel=$1 last;
```
       3.Enjoy

       BTW Apache is able to serve this file, but I am not sure how to write rewrite rule,Any one help us to improve?
