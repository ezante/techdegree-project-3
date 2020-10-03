<?php
/**
 * Returns the array of all entries
 */
function getEntries() {
    include 'connection.php';

    try {
        return $db->query('SELECT * FROM entries ORDER BY id DESC');
    } catch (Exception $e) {
        echoError($e->getMessage());

        return [];
    }
}

/**
 * Returns the array of all entries for a specific tag
 */
function getEntriesForTag($tagId) {
    include 'connection.php';

    try {
        $stmt = $db->prepare('SELECT * FROM entries_tags JOIN entries ON entries_tags.entry_id = entries.id WHERE entries_tags.tag_id = ?');
        $stmt->bindValue(1, $_GET['tag_id'], PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        echoError($e->getMessage());

        return [];
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Adds an entry and it's tags into the database
 */
function addEntry($title, $date, $timeSpent, $whatILearned, $resources, $tags) {
    include 'connection.php';

    try {
        $db->beginTransaction();

        // Adds an entry
        $stmt = $db->prepare('INSERT INTO entries (title, date, time_spent, learned, resources) VALUES(?, ?, ?, ?, ?)');
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $date, PDO::PARAM_STR);
        $stmt->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $stmt->bindValue(4, $whatILearned, PDO::PARAM_STR);
        $stmt->bindValue(5, $resources, PDO::PARAM_STR);
        $stmt->execute();
        $entryId = $db->lastInsertId();

        // Inserts tags and attaches them to the entry
        foreach (explode(',', $tags) as $tag) {
            $stmt = $db->prepare('INSERT OR IGNORE INTO tags (tag) VALUES(?)');
            $stmt->bindValue(1, trim($tag));
            $stmt->execute();

            $stmt = $db->prepare('INSERT INTO entries_tags (entry_id, tag_id) VALUES(?, (SELECT id FROM tags WHERE tag = ? LIMIT 1))');
            $stmt->bindValue(1, $entryId);
            $stmt->bindValue(2, trim($tag));
            $stmt->execute();
        }

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        echoError($e->getMessage());

        return false;
    }

    return true;
}

/**
 * Updates an entry and it's tags
 */
function updateEntry($title, $date, $timeSpent, $whatILearned, $resources, $id, $tags) {
    include 'connection.php';

    try {
        $db->beginTransaction();

        // Updates the entry
        $stmt = $db->prepare('UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?');
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        $stmt->bindValue(2, $date, PDO::PARAM_STR);
        $stmt->bindValue(3, $timeSpent, PDO::PARAM_STR);
        $stmt->bindValue(4, $whatILearned, PDO::PARAM_STR);
        $stmt->bindValue(5, $resources, PDO::PARAM_STR);
        $stmt->bindValue(6, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Deattaches tags currently attached to the entry
        $stmt = $db->prepare('DELETE FROM entries_tags WHERE entry_id = ?');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Inserts updated tags and attaches them to the entry
        foreach (explode(',', $tags) as $tag) {
            $stmt = $db->prepare('INSERT OR IGNORE INTO tags (tag) VALUES(?)');
            $stmt->bindValue(1, trim($tag));
            $stmt->execute();

            $stmt = $db->prepare("INSERT INTO entries_tags (entry_id, tag_id) VALUES(?, (SELECT id FROM tags WHERE tag = ? LIMIT 1))");
            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, trim($tag));
            $stmt->execute();
        }

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        echoError($e->getMessage());

        return false;
    }

    return true;
}

/**
 * Returns a specific entry
 */
function getEntry($id) {
      include 'connection.php';

      try {
        $stmt = $db->prepare('SELECT * FROM entries WHERE id = ?');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
      } catch (Exception $e) {
        echoError($e->getMessage());

        return false;
      }

      return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Deletes an entry from the database
 */
function deleteEntry($id) {
    include 'connection.php';

    try {
        // Deletes an entry
        $stmt = $db->prepare('DELETE FROM entries WHERE id = ?');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Deletes tag attachments
        $stmt = $db->prepare('DELETE FROM entries_tags WHERE entry_id = ?');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        echoError($e->getMessage());

        return false;
    }

    return true;
}

/**
 * Returns all tags for a specific entry
 */
function getTagsForEntry($entryId) {
      include 'connection.php';

      try {
        $stmt = $db->prepare('SELECT * FROM entries_tags JOIN tags ON entries_tags.tag_id = tags.id WHERE entries_tags.entry_id = ?');
        $stmt->bindValue(1, $entryId, PDO::PARAM_INT);
        $stmt->execute();
      } catch (Exception $e) {
        echoError($e->getMessage());

        return false;
      }

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Returns a specific tag
 */
function getTag($id) {
    include 'connection.php';

    try {
        $stmt = $db->prepare('SELECT * FROM tags WHERE id = ?');
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        echoError($e->getMessage());

        return false;
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Echoes an error message
 */
function echoError($message) {
    echo "Error! {$message} <br>";
}
