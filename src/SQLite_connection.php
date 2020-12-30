<?php

/*
 * Create SQLite3 object to connect database
 */
class MyDB extends SQLite3 {
    function __construct() {
        $this->open('user_chats_db.db');
    }
}
