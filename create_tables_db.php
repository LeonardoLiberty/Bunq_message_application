<?php

$db = new SQLite3('user_chats_db.db');

$db->exec("CREATE TABLE IF NOT EXISTS chats( 
    chat_id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id TEXT NOT NULL,
    recipient_id TEXT NOT NULL,
    chat TEXT NOT NULL,
    status INT NOT NULL,
    timestamps TEXT NOT NULL )");



$db->exec("CREATE TABLE IF NOT EXISTS user( 
    user_id TEXT PRIMARY KEY,
    user_name TEXT NOT NULL,
    online_status INT NOT NULL,
    timestamps TEXT NOT NULL)");

# online_status: 0 offline; 1 online; 2 busy