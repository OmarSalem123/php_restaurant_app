<?php
/**
 * config/database.php
 * -------------------------------------------------------------
 * This file has ONE job: open a connection to MySQL and return it.
 * Every API file includes it, so the connection settings live in
 * a single place. If your password changes, you edit only this file.
 */

// ----- Connection settings (change these to match your MySQL) -----
define('DB_HOST', 'localhost');   // MySQL server address (XAMPP/WAMP = localhost)
define('DB_NAME', 'restaurant');   // The database you create with the SQL commands
define('DB_USER', 'root');        // MySQL username (XAMPP default = root)
define('DB_PASS', '');            // MySQL password (XAMPP default = empty)

/**
 * getConnection()
 * Creates and returns a PDO object (PDO = PHP Data Objects).
 * PDO is PHP's built-in, safe way to talk to databases.
 *
 * @return PDO  An open connection we can run queries on.
 */
function db(): PDO
{
    // DSN = "Data Source Name": tells PDO which driver, host, and database to use.
    // charset=utf8mb4 lets us store any character (Arabic, emojis, etc.).
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $options = [
        // Throw an exception when a query fails, instead of failing silently.
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // When we fetch a row, return it as ['column' => value] arrays.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Use REAL prepared statements in MySQL (important for SQL-injection safety).
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // We never show the real error to the client (it can leak passwords/paths).
        // sendResponse() is defined in helpers/response.php.
        sendResponse(500, false, 'Database connection failed');
    }
}
