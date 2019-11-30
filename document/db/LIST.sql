DROP TABLE IF EXISTS  `LIST`;

CREATE TABLE `LIST`
(
    `PROJECT_CODE`      CHAR(36)            NOT NULL    COMMENT '所属プロジェクト',
    `LIST_CODE`         CHAR(54)            NOT NULL    COMMENT '"PROJECT_CODE"+"_"+"YmdHisu"',
    `INDEX`             TINYINT UNSIGNED    NOT NULL    COMMENT '番号',
    `NAME`              VARCHAR(64)         NOT NULL    COMMENT '一覧名',
    `CREATE_ID`         VARCHAR(16)                     COMMENT '作成ユーザーID',
    `CREATE_DATETIME`   DATETIME                        COMMENT '作成日時',
    `UPDATE_ID`         VARCHAR(16)                     COMMENT '更新ユーザーID',
    `UPDATE_DATETIME`   DATETIME                        COMMENT '更新日時',
    PRIMARY KEY (`PROJECT_CODE`, `LIST_CODE`)
);

ALTER TABLE `LIST` COMMENT 'タスク一覧テーブル';
