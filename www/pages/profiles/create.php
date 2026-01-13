<?php 

require_once (dirname(__DIR__, 2) . '/index.php'); 


echo '<h1>New Profile</h1> <p>Inserting new profile into profile table</p>'; 

if (!empty($_POST)) {
    $profileSubmit = \App\Profile\Units\ProfileSubmit::new();
    $profileSubmit->loadData($_POST);
    $profileSubmit->handle();
    $errors = $profileSubmit->errors(); 
    if (!empty($errors)) {
        echo '<div class="error">' . implode('<br>', $errors) . '</div>';
        print_r($errors);
    } else {
        echo '<div class="success">Profile saved successfully!</div>';
        echo "<script>
            window.location.href = 'view.php?id=' + " . $profileSubmit->getProfile()['id'] . ";
        </script>";
    }
}

$profileForm = \App\Profile\Units\ProfileForm::new();

$blocks = $profileForm->getBlocks();

echo "<form method='post' action='' name='profile_form'>";

foreach ($blocks as $block) {
    echo '<p><strong class="text-muted" >' . $block->title() . '</strong></p>';
    // Assuming each block has a render method
    if (method_exists($block, 'render')) {
        echo $block->render();
        echo '<hr>';
    } else {
        echo 'No render method available for this block.';
    }
}

echo "<button type='submit' name='save_profile_btn' value='1' class='btn btn-primary' >Save Profile</button>";

echo "</form>";

include __DIR__ . '/../page_end.php';