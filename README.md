

<h1 align="center">
<b>Bunq Chat application💯</b>
</h1>

A very simple chat application backend in PHP.


# ✨Overview

Let's get shit done.
This application realized that a user could be able to send a simple message to another user and a user shoule be able to get and see the author.

## Requirements

 - The users and messages should be stored in SQLite database, 
 - all communication should happen over a simple RESTful JSON API over HTTPs.
 - Users should be identified by some token.  
 - Slim microframwork required.
 - Secure and structured code.

## 📺Watch the demo from YouTube

The final demo could be viewed by following video link: https://youtu.be/F5uPp3DE-IQ




#  📦Getting started

Unzip the file or Clone the project repository.
Tech requirements:

 - PHP 7.3 or newer
 - Slim microframework 4.X
 - JQuery 3.5.1 or newer
 - SQLite 3

##   Setup

 1. Install PHP 
To install PHP locally, refer to the download page: https://www.php.net/manual/en/install.php

 2. Install Slim framework
 To install Slim, composer is required which is the dependency manager of PHP. Please refer to the page link: https://getcomposer.org/download/
 After installed the composer, Slim could be installed by: 
 
	```
	composer require slim/slim:"4.*"
	```
	 Before runing the slim, PSR-7 Implementation and ServerRequest Creator are required to be installed.
	 

	```
	composer require slim/psr7
	```
3. Ensure dictionary containing SQLite3 .db file (src/db) is writable by adjust the roots.
4. Web server is based on PHP built-in server. Please make sure `index.php` as your entry point otherwise change appropriately.


## Running the program

 - Navigate to project root and run  ```php -S localhost:8888```
 - Now, php server is listerning to the link: http://localhost:8888
 - Please use one browser to send message via (Ignore the warnning if exists or refresh the page at first): http://localhost:8888/send_msg.php
 - Make sure send message to user called "u1".
 - Open another browser to recieve message as user "u1" via: http://localhost:8888/u1_recieve_msg.php

Here we use two browser to keep two different sessions as different token of users.

# 🔨Structure

This section contains file structures and their usages, database design which can provide a general insight of code implementation.



## File structure

 - /src
 | /db --> create_tables.php  ==> create user and chats table
 | action.js ==> Required JS functions
 | session.php ==> create and check user tokens 
 | SQLite_connection.php ==> connect with SQLite database
 - /vendor ==> Slim third-party dependencies
 - index.php ==> Slim router file to register the requests and responses
 - send_msg.php ==> User can send message to another user
 - u1_recieve_msg.php ==> One user recieve message from the other users
 - user_chat_db.db ==> database file contains two tables and their structure

## Dataset Design

Based on the requirements, two tables user and chats are created as follow:
#### User

| Attributes|Description     |
|---------|----------|
|user_id |user identification|
|user_name | user name (init same as user_id, will be revised after register)
| online_status| 0 offline; 1 online
|timestamps | user last login time

#### Chats

| Attributes|Description     |
|---------|----------|
| chat_id  |  chat identification
|sender_id | user identification of sender
|recipient_id    | user identification of recipient
|chat| message content
|status| 0 sent; 1 recieved by server; 2 recieved by recipient
|timestamps| sent time by sender


# 🏷️Security

In order to keep the program secure, two injections are raised to be handled includes SQL injection and XSS injection. 

## SQL injection

SQL injection occurs when you ask a user for input and they give SQL statement that you will **unknowingly** run on your database. In this case, one uses prepare() function and bind input parameters only.

## XSS injection

XSS injection is that you ask user for input and they give vulnerabilities target scripts embedded in a page which are executed on the client-side. In this case, one uses php htmlspecialchars() function to filter the special characters.

#  🤝Feedback

Please contact me by Email: leonardo.duuuu@gmail.com

Wenhao DU
Dec. 30th 2020
