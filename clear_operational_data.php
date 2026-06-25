<?php
$host = '127.0.0.1';
$port = 3306;
$db   = 'farmwealth';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE journal_entry_lines");
    $pdo->exec("TRUNCATE TABLE journal_entries");
    $pdo->exec("TRUNCATE TABLE inventory_transfers");
    $pdo->exec("TRUNCATE TABLE cycle_dispensations");
    $pdo->exec("TRUNCATE TABLE purchase_invoice_items");
    $pdo->exec("TRUNCATE TABLE purchase_invoices");
    $pdo->exec("TRUNCATE TABLE sale_invoice_items");
    $pdo->exec("TRUNCATE TABLE sale_invoices");
    $pdo->exec("TRUNCATE TABLE financial_records");
    $pdo->exec("TRUNCATE TABLE medicine_dispensations");
    $pdo->exec("TRUNCATE TABLE mortality_records");
    $pdo->exec("TRUNCATE TABLE medicine_entries");
    $pdo->exec("TRUNCATE TABLE shed_inventories");
    $pdo->exec("TRUNCATE TABLE cycles");
    $pdo->exec("TRUNCATE TABLE sheds");
    $pdo->exec("TRUNCATE TABLE clients");
    $pdo->exec("TRUNCATE TABLE suppliers");
    $pdo->exec("UPDATE items SET quantity_in_stock = 0, last_purchase_price = 0");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "Operational data cleared successfully.\n\n";

    echo "Verification:\n";
    $counts = [
        'sheds' => 'SELECT COUNT(*) FROM sheds',
        'cycles' => 'SELECT COUNT(*) FROM cycles',
        'clients' => 'SELECT COUNT(*) FROM clients',
        'suppliers' => 'SELECT COUNT(*) FROM suppliers',
        'users' => 'SELECT COUNT(*) FROM users',
    ];

    foreach ($counts as $label => $sql) {
        $stmt = $pdo->query($sql);
        echo $label . ': ' . $stmt->fetchColumn() . "\n";
    }

    echo "\nSample items (quantity_in_stock):\n";
    $stmt = $pdo->query("SELECT quantity_in_stock FROM items LIMIT 5");
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        echo $row['quantity_in_stock'] . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
