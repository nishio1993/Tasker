DROP TABLE IF EXISTS  `TASK`;

CREATE TABLE `TASK`
(
    `COLUMN_CODE`       CHAR(54)            NOT NULL    COMMENT '所属タスク一覧',
    `TASK_CODE`         CHAR(72)            NOT NULL    COMMENT '"LIST_CODE"+"_"+"YmdHisu"',
    `INDEX`             TINYINT UNSIGNED    NOT NULL    COMMENT '表示順',
    `TASK_NAME`         VARCHAR(64)         NOT NULL    COMMENT 'タスク名',
    `TEXT`              VARCHAR(1024)       NOT NULL    COMMENT '本文',
    `REPRESENTATIVE`    VARCHAR(16)                     COMMENT '担当者ユーザー',
    `LABEL`             CHAR(1)                         COMMENT 'ラベル',
    `PROGRESS`          TINYINT UNSIGNED                COMMENT '進捗度',
    `START_DATE`        DATE                            COMMENT '開始日',
    `END_DATE`          DATE                            COMMENT '終了日',
    `CREATE_USER`       VARCHAR(16)         NOT NULL    COMMENT '作成ユーザー',
    `CREATE_DATETIME`   DATETIME            NOT NULL    COMMENT '作成日時',
    `UPDATE_USER`       VARCHAR(16)         NOT NULL    COMMENT '更新ユーザー',
    `UPDATE_DATETIME`   DATETIME            NOT NULL    COMMENT '更新日時',
    PRIMARY KEY(`COLUMN_CODE`, `TASK_CODE`)
);

ALTER TABLE `TASK` COMMENT 'タスクテーブル';
