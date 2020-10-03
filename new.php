<?php
require 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $timeSpent = filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING);
    $whatILearned = filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING);
    $resources = filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING);
    $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);

    $success = addEntry($title, $date, $timeSpent, $whatILearned, $resources, $tags);
    if ($success) {
        header('Location: index.php');
        exit;
    }

    echo 'Could not add entry';
}

require 'header.php';
?>

<section>
    <div class="container">
        <div class="new-entry">
            <h2>New Entry</h2>
            <form action="new.php" method="post">
                <div>
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title">
                </div>

                <div>
                    <label for="date">Date</label>
                    <input id="date" type="date" name="date">
                </div>

                <div>
                    <label for="time-spent">Time Spent</label>
                    <input id="time-spent" type="text" name="timeSpent">
                </div>

                <div>
                    <label for="what-i-learned">What I Learned</label>
                    <textarea id="what-i-learned" rows="5" name="whatILearned"></textarea>
                </div>

                <div>
                    <label for="resources-to-remember">Resources to Remember</label>
                    <textarea id="resources-to-remember" rows="5" name="resourcesToRemember"></textarea>
                </div>

                <div>
                    <label for="tags">Tags</label>
                    <input id="tags" type="text" name="tags" placeholder="e.g. tag1,tag2,tag3">
                </div>

                <div>
                    <input type="submit" value="Publish Entry" class="button">
                    <a href="index.php" class="button button-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require 'footer.php' ?>
