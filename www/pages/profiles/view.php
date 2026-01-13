<?php 

require_once (dirname(__DIR__, 2) . '/index.php'); 

echo '<h1>View Profile</h1> <p>View new profile into profile table</p>'; 


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<p>Error: Profile ID is required.</p>';
    exit;
}
$id = (int)$_GET['id'];
$profileView = \App\Profile\Units\ProfileView::of($id);

$blocks = $profileView->getBlocks();

echo "<a href='update.php?id=" . $id . "' class='btn btn-info btn-sm' >Edit Profile</a>";
echo "<br />";
echo "<br />";

foreach ($blocks as $block) {
    echo '<h4>' . $block->title() . '</h4>';
    // Assuming each block has a render method
    $block->read($profileView->getProfile());
    if (method_exists($block, 'view')) {
        echo $block->view();
        echo '<hr>';
    } else {
        echo 'No view method available for this block.';
    }
}

include __DIR__ . '/../page_end.php';