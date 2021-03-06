DROP TABLE IF EXISTS  `TASK_FILE`;

CREATE TABLE `TASK_FILE`
(
    `TASK_CODE`  VARCHAR(256)    NOT NULL    COMMENT 'タスクコード',
    `FILE_PATH`  VARCHAR(256)    NOT NULL    COMMENT '添付ファイルパス'
);

ALTER TABLE `TASK_FILE` COMMENT 'タスク添付ファイルテーブル';
