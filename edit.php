<?php
require 'inc/functions.php';

if (isset($_POST['id'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $timeSpent = filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING);
    $whatILearned = filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING);
    $resources = filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $tags = trim(filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING));

    $success = updateEntry($title, $date, $timeSpent, $whatILearned, $resources, $id, $tags);
    if ($success) {
        header('Location: index.php');
        exit;
    }

    echo 'Could not add entry';
}

if (! empty($_GET['id'])) {
    $entry = getEntry(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
}

require 'header.php'
?>

<section>
    <div class="container">
        <div class="edit-entry">
            <?php if (empty($entry)): ?>
                <p>Entry not found</p>
            <?php else: ?>
                <h2>Edit Entry</h2>
                <form action="edit.php" method="post">
                    <div>
                        <label for="title">Title</label>
                        <input id="title" type="text" name="title" value="<?= $entry['title'] ?>">
                    </div>

                    <div>
                        <label for="date">Date</label>
                        <input id="date" type="date" name="date" value="<?= $entry['date'] ?>">
                    </div>

                    <div>
                        <label for="time-spent">Time Spent</label>
                        <input id="time-spent" type="text" name="timeSpent" value="<?= $entry['time_spent'] ?>">
                    </div>

                    <div>
                        <label for="what-i-learned">What I Learned</label>
                        <textarea id="what-i-learned" rows="5" name="whatILearned"><?= $entry['learned'] ?></textarea>
                    </div>

                    <div>
                        <label for="resources-to-remember">Resources to Remember</label>
                        <textarea id="resources-to-remember" rows="5" name="resourcesToRemember"><?= $entry['resources'] ?></textarea>
                    </div>

                    <div>
                        <label for="tags">Tags</label>
                        <input id="tags" type="text" name="tags" value="<?= implode(',', array_column(getTagsForEntry($entry['id']), 'tag')); ?>">
                    </div>

                    <div>
                        <input type="submit" value="Save" class="button">
                        <a href="detail.php?id=<?= $entry['id'] ?>" class="button button-secondary">Cancel</a>
                    </div>

                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require 'footer.php' ?>
