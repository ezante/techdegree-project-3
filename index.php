<?php
require 'inc/functions.php';
require 'header.php';

if (isset($_GET['tag_id'])) {
    $tagId = filter_input(INPUT_GET, 'tag_id', FILTER_SANITIZE_NUMBER_INT);
    $tag = getTag($tagId);
}

$entries = ! empty($tag) ? getEntriesForTag($tag['id']) : getEntries();
?>

<section>
    <div class="container">
        <?php if (! empty($tag)): ?>
            <h1 class="text-center">Showing entries for tag "<?= $tag['tag']; ?>" <a href="index.php">x</a></h1>
        <?php endif; ?>
        <div class="entry-list">
            <?php foreach ($entries as $entry): ?>
                <article>
                    <h2><a href="detail.php?id=<?= $entry['id'] ?>"><?= $entry['title'] ?></a></h2>
                    <p><time datetime="<?= $entry['date'] ?>"><?= $entry['date'] ?></time></p>
                    <p>
                        <?php foreach (getTagsForEntry($entry['id']) as $tag): ?>
                            <a href="index.php?tag_id=<?= $tag['tag_id'] ?>"><?= $tag['tag'] ?></a>
                        <?php endforeach; ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require 'footer.php' ?>
