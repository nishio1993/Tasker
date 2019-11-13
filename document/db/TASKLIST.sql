DROP TABLE `TASKLIST`;

CREATE TABLE `TASKLIST`
(
    `PROJECT_CODE`      CHAR(36)            NOT NULL    COMMENT '所属プロジェクト',
    `TASK_CODE`         CHAR(57)            NOT NULL    COMMENT '"PROJECT_CODE"+"_"+"YmdHidu"'
    `INDEX`             TINYINT UNSIGNED    NOT NULL    COMMENT '番号',
    `NAME`              VARCHAR(64)         NOT NULL    COMMENT '一覧名',
    `CREATE_ID`         VARCHAR(16)         NOT NULL    COMMENT '作成ユーザーID',
    `CREATE_DATETIME`   DATETIME            NOT NULL    COMMENT '作成日時',
    `UPDATE_ID`         VARCHAR(16)         NOT NULL    COMMENT '更新ユーザーID',
    `UPDATE_DATETIME`   DATETIME            NOT NULL    COMMENT '更新日時'
);

ALTER TABLE `TASKLIST` COMMENT 'タスク一覧テーブル';
