<?php
require 'inc/functions.php';

if ($_GET['id']) {
    $entryId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $entry = getEntry($entryId);
}

require 'header.php'
?>

<section>
    <div class="container">
        <?php if (empty($entry)): ?>
            <p>Entry not found</p>
        <?php else: ?>
            <div class="entry-list single">
                <article>
                    <h1><?= $entry['title'] ?></h1>
                    <time datetime="<?= $entry['date'] ?>"><?= $entry['date'] ?></time>
                    <div class="entry">
                        <h3>Tags: </h3>
                        <p>
                        <ul>
                            <?php foreach (getTagsForEntry($entryId) as $tag): ?>
                                <li><a href="index.php?tag_id=<?= $tag['tag_id'] ?>"><?= $tag['tag'] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                        </p>
                    </div>
                    <div class="entry">
                        <h3>Time Spent: </h3>
                        <p><?= $entry['time_spent'] ?></p>
                    </div>
                    <div class="entry">
                        <h3>What I Learned:</h3>
                        <p><?= $entry['learned'] ?></p>
                    </div>
                    <div class="entry">
                        <h3>Resources to Remember:</h3>
                        <p><?= $entry['resources'] ?></p>
                    </div>
                </article>
            </div>
        </div>
        <div class="edit-delete">
            <p>
                <a href="edit.php?id=<?= $entry['id'] ?>">Edit Entry</a>
                <form action="delete.php" method="post">
                    <input type="hidden" name="id" value="<?= $entry['id'] ?>">
                    <input type="submit" value="Delete Entry" class="delete">
                </form>
            </p>
    <?php endif; ?>
    </div>
</section>

<?php require 'footer.php' ?>
