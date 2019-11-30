DROP TABLE IF EXISTS  `TASK`;

CREATE TABLE `TASK`
(
    `LIST_CODE`         CHAR(54)            NOT NULL    COMMENT '所属タスク一覧',
    `TASK_CODE`         CHAR(72)            NOT NULL    COMMENT '"LIST_CODE"+"_"+"YmdHisu"',
    `INDEX`             TINYINT UNSIGNED    NOT NULL    COMMENT '表示順',
    `NAME`              VARCHAR(64)         NOT NULL    COMMENT 'タスク名',
    `TEXT`              VARCHAR(1024)       NOT NULL    COMMENT '本文',
    `REPRESENTATIVE`    VARCHAR(16)                     COMMENT '担当者ユーザーID',
    `LABEL`             CHAR(1)                         COMMENT 'ラベル',
    `PROGRESS`          TINYINT UNSIGNED                COMMENT '進捗度',
    `START_DATE`        DATE                            COMMENT '開始日',
    `END_DATE`          DATE                            COMMENT '終了日',
    `CREATE_ID`         VARCHAR(16)         NOT NULL    COMMENT '作成ユーザーID',
    `CREATE_DATETIME`   DATETIME            NOT NULL    COMMENT '作成日時',
    `UPDATE_ID`         VARCHAR(16)         NOT NULL    COMMENT '更新ユーザーID',
    `UPDATE_DATETIME`   DATETIME            NOT NULL    COMMENT '更新日時',
    PRIMARY KEY(`LIST_CODE`, `TASK_CODE`)
);

ALTER TABLE `TASK` COMMENT 'タスクテーブル';
