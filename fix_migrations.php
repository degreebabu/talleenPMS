<?php
$files = glob("database/migrations/*.php");
$migrations = [];
foreach ($files as $file) {
    $content = file_get_contents($file);
    preg_match('/Schema::create\(\'([^\']+)\'/', $content, $m);
    if (!isset($m[1])) {
        preg_match('/Schema::table\(\'([^\']+)\'/', $content, $m);
    }
    $table = $m[1] ?? basename($file);
    
    preg_match_all('/references\(\'([^\']+)\'\)/', $content, $deps1);
    preg_match_all('/constrained\(\'([^\']+)\'\)/', $content, $deps2);
    $migrations[basename($file)] = [
        'table' => $table,
        'deps' => array_merge($deps1[1], $deps2[1])
    ];
}
// Sort by dependencies
$sorted = [];
$visited = [];
$visiting = [];
function visit($file, &$sorted, &$visited, &$visiting, $migrations) {
    if (isset($visited[$file])) return;
    if (isset($visiting[$file])) return; // Avoid circular dependency infinite loop
    $visiting[$file] = true;
    foreach ($migrations[$file]['deps'] as $dep) {
        foreach ($migrations as $f => $m) {
            if ($m['table'] === $dep && $f !== $file) {
                visit($f, $sorted, $visited, $visiting, $migrations);
            }
        }
    }
    unset($visiting[$file]);
    $visited[$file] = true;
    $sorted[] = $file;
}
foreach ($migrations as $file => $m) {
    visit($file, $sorted, $visited, $visiting, $migrations);
}

// Rename
$baseTime = strtotime("2026-07-22 14:00:00");
foreach ($sorted as $i => $file) {
    $newTime = date("Y_m_d_His", $baseTime + $i);
    $newName = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}/', $newTime, $file);
    if ($newName !== $file) {
        rename("database/migrations/" . $file, "database/migrations/" . $newName);
        echo "Renamed \$file to \$newName\n";
    }
}
