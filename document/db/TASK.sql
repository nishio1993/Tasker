DROP TABLE `TASK`;

CREATE TABLE `TASK`
(
    `TASK_CODE`         CHAR(78)            NOT NULL    COMMENT '"TASKLIST_CODE"+"_"+"YmdHisu"',
    `TASKLIST_CODE`     CHAR(57)            NOT NULL    COMMENT '所属タスク一覧',
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
    `UPDATE_DATETIME`   DATETIME            NOT NULL    COMMENT '更新日時'
);

ALTER TABLE `TASK` ADD CONSTRAINT PK_TASK PRIMARY KEY (
    `TASK_CODE`
);

ALTER TABLE `TASK` COMMENT 'タスクテーブル';
