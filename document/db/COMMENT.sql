DROP TABLE IF EXISTS  `COMMENT`;

CREATE TABLE `COMMENT`
(
    `TASK_CODE`         CHAR(72)        NOT NULL    COMMENT '所属タスク',
    `INDEX`             TINYINT(1)      NOT NULL    COMMENT '表示順',
    `TEXT`              VARCHAR(1024)   NOT NULL    COMMENT '内容',
    `CREATE_USER`       VARCHAR(16)     NOT NULL    COMMENT '作成ユーザーID',
    `CREATE_DATETIME`   DATETIME        NOT NULL    COMMENT '作成日時',
    `UPDATE_USER`       VARCHAR(16)     NOT NULL    COMMENT '更新ユーザーID',
    `UPDATE_DATETIME`   DATETIME        NOT NULL    COMMENT '更新日時',
    PRIMARY KEY (`TASK_CODE`, `INDEX`)
);

ALTER TABLE `COMMENT` COMMENT 'コメントテーブル';
