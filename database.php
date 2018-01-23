<?php
// Uninstall this tool
$DATABASE_UNINSTALL = array(
  "DROP TABLE IF EXISTS {$CFG->dbprefix}wisdomOfCrowdAnswers"
);
$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}wisdomOfCrowdAnswers",
  "CREATE TABLE {$CFG->dbprefix}wisdomOfCrowdAnswers (
    link_id         INTEGER NOT NULL,
    user_id         INTEGER NOT NULL,
    question_id     INTEGER NOT NULL,
    answer_text     TEXT NOT NULL,
    CONSTRAINT U_id UNIQUE(link_id, user_id, question_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);
?>
