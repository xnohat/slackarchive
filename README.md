# SlackArchiveBot
Slack Messages Archive System for bypass limit 10.000 recent messages only of free Slack

# Feature

1. Auto crawl and archive all messages from all channel of free plan Slack
2. Slash Command to work inside Slack
3. Efficiency search engine backed by Elastic Search for search thousand of messages instantly
4. Security mechanism to protect only user have right to see messages can see messages, privately for team
5. Free and Open Source

# System Requirement
1. PHP 7
2. Elasticsearch
3. Redis
4. Set PHP.ini memory_limit = 512M or memory_limit = 1G

# Installation Guide 

1. Create a Admin User for Workspace, this user is dedicated for App. Suggest name for user is SlackArchiveBot . This user is use only for app dont use it for chatting.

2. Login with this user

3. Go to https://api.slack.com/apps

4. Create an Slack App with 

    App Name:
    SlackArchiveBot
    
    Development Workspace:
    Your slack workspace

5. Go to your app -> install app or URL below
https://api.slack.com/apps/<APP_ID>/install-on-team
Doing install app to your Workspace
Copy "OAuth Access Token"

	set this token to "token" in file config/services.php (clone file from services.example.php)

    'slack' => [
            'token' => 'xoxp-110218693525-110132216082-xxxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxx',
        ],

6. Go to file config/slackarchive.php (clone from file slackarchive.example.php)
edit line 

>   'secret_key' => '7e79d196fe0797xxxxxxxxxxx',

with any random string secret_key (format a-z,0-9) . Copy this secret_key and use as below.

> 'team_name' => 'Team Name', // is your Workspace name    
> 'full_domain' => 'slack.yourdomain.com', // your domain for Slack
> Archive

7. Go to your app -> Slash Commands or URL
https://api.slack.com/apps/<APP_ID>/slash-commands
Create new command

> Command: /find 
> Request URL: http://slack.yourdomain.com/slack?cmd=find&secret=<secret_key>
> Short Description: Search old messages in this channel 
> Usage Hint: \<query> limit:\<number>
> Escape channels, users, and links sent to your app: Checked
> 
> Click Save
>
>-----
> Command: /history 
> Request URL: http://slack.yourdomain.com/viewhistory?secret=<secret_key> 
> Short Description: View history of this channel
> Usage Hint:
> Escape channels, users, and links sent to your app: Checked
> 
> Click Save

Done for Slack App


--------------------------------------------------------

Go to server shell

$ nano /etc/crontab

add line 

    * * * * * root php /var/www/html/artisan schedule:run >> /dev/null 2>&1

$ crontab /etc/crontab

Go to /var/www/html

php artisan clear-all-cache


Some php artisan command you need

test Slack Archive Bot crawler. Run this for first time

$ php artisan bot:crawl

add all messages to ElasticSearch. Run this for first time to create ES index

$ php artisan es:addallmessagetoindex

reindex all message. Run this every time cannot search any thing on slack archive

$ php artisan es:reindexallmessages

--------------------------------------------------------

**Usage:**

in slack channel you need to view history type

/history

Click a link returned by bot to view history

/find \<query> limit:\<number>

to find message

**REMEMBER:**

Invite user @SlackArchiveBot to every PRIVATE channel your need to save history

Direct Message cannot save history

**TIPS:**

OAuth Access Token of SlackArchiveBot user only can get messages from public Channels and ONLY JOINED Private Group

# Contribution
Feel free to fork and pull request your fixs, your improvements

# Thanks
Slack Development team for great product and Slack Sale team for expensive price


