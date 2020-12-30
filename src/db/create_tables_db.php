<?php

# Create new SQLite database file
$db = new SQLite3('../../user_chats_db.db');

/*
 * sender_id and recipient_id are user_id of sender and recipient
 * status: 0 sent; 1 received by server; 2 received by recipient
 * timestamps: sent time by sender
 */

$db->exec("CREATE TABLE IF NOT EXISTS chats( 
    chat_id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id TEXT NOT NULL,
    recipient_id TEXT NOT NULL,
    chat TEXT NOT NULL,
    status INT NOT NULL,
    timestamps TEXT NOT NULL )");


/*
 * user name:  init same as user_id, will be revised after register
 * online_status: 0 offline; 1 online
 * timestamps: user last login time
 */

$db->exec("CREATE TABLE IF NOT EXISTS user( 
    user_id TEXT PRIMARY KEY,
    user_name TEXT NOT NULL,
    online_status INT NOT NULL,
    timestamps TEXT NOT NULL)");


