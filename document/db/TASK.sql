DROP TABLE IF EXISTS  `TASK`;

CREATE TABLE `TASK`
(
    `PROJECT_CODE`      CHAR(36)            NOT NULL    COMMENT 'UUID',
    `INDEX`             INTEGER UNSIGNED    NOT NULL    COMMENT '6桁数値を100区切り',
    `TASK_NAME`         VARCHAR(64)         NOT NULL    COMMENT 'タスク名',
    `TEXT`              VARCHAR(1024)       NOT NULL    COMMENT '本文',
    `PERSON_IN_CHARGE`  VARCHAR(256)                    COMMENT '担当者ユーザー',
    `LABEL`             CHAR(1)                         COMMENT 'ラベル',
    `PROGRESS`          TINYINT UNSIGNED                COMMENT '進捗度',
    `START_DATETIME`    DATETIME                        COMMENT '開始日',
    `END_DATETIME`      DATETIME                        COMMENT '終了日',
    `CREATE_USER`       VARCHAR(256)        NOT NULL    COMMENT '作成ユーザー',
    `CREATE_DATETIME`   DATETIME            NOT NULL    COMMENT '作成日時',
    `UPDATE_USER`       VARCHAR(256)        NOT NULL    COMMENT '更新ユーザー',
    `UPDATE_DATETIME`   DATETIME            NOT NULL    COMMENT '更新日時',
    PRIMARY KEY(`PROJECT_CODE`, `INDEX`)
);

ALTER TABLE `TASK` COMMENT 'タスクテーブル';
