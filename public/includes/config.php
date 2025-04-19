<?php
$base_url = '/nhomh/public/';
?>

<?php
/** The name of the database*/
define('DB_NAME', 'onlineshop_h');
/** MySQL database username */
define('DB_USER', 'root');
/** MySQL database password */
define('DB_PASSWORD', '');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/** port number of DB */
define('PORT', 3306);
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');
?>

<?php
$dsn = 'mysql:host=localhost;dbname=onlineshop_h';
$username = 'root';
$password = '';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>