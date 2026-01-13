<?php

use App\Connection;

require_once (dirname(__DIR__, 2) . '/index.php'); 

echo '<h1>Profile List</h1> '; 

$pdo = Connection::instance()->pdo();

$stmt = $pdo->query("SELECT profile.*, user.surname, user.name
                    FROM `profile` 
                    LEFT JOIN user ON user.id = profile.user_id
                    ORDER BY profile.id;");
$rows = $stmt->fetchAll();

if (empty($rows)) {
    echo '<p>No profiles found.</p>';
} else {
    echo '<table class="table table-striped" >';
    echo '<tr><th>ID</th>
        <th>FIO</th>
        <th>FIO block</th>
        <th>Birthday</th>
        <th>Facultative Block</th>
        <th>Sport Block</th>
        <th>Olimpic Block</th>
        <th>Chosen Predmet</th>
        <th>Actions</th></tr>';
    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['surname']) . " " . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . $row['fio_block'] . '</td>';
        echo '<td>' . $row['birthdate_block'] . '</td>';
        echo '<td>' . $row['facult_block'] . '</td>';
        echo '<td>' . $row['sport_block'] . '</td>';
        echo '<td>' . $row['olimpic_block'] . '</td>';
        echo '<td>' . $row['chosen_predmet_block'] . '</td>';
        echo '<td>
            <a href="/pages/profiles/update.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-primary btn-sm" >Edit</a>
            <a href="/pages/profiles/view.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-info btn-sm"  >View</a>
            </td>';
        echo '</tr>';
    }
    echo '</table>';
}


include __DIR__ . '/../page_end.php';