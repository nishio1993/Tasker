DROP TABLE IF EXISTS  `CATEGORY`;

CREATE TABLE `CATEGORY`
(
    `PROJECT_CODE`      CHAR(36)            NOT NULL    COMMENT '所属プロジェクト',
    `CATEGORY_CODE`     CHAR(54)            NOT NULL    COMMENT '"PROJECT_CODE"+"_"+"YmdHisu"',
    `INDEX`             TINYINT UNSIGNED    NOT NULL    COMMENT '番号',
    `CATEGORY_NAME`     VARCHAR(64)         NOT NULL    COMMENT '一覧名',
    `CREATE_USER`       VARCHAR(16)                     COMMENT '作成ユーザーID',
    `CREATE_DATETIME`   DATETIME                        COMMENT '作成日時',
    `UPDATE_USER`       VARCHAR(16)                     COMMENT '更新ユーザーID',
    `UPDATE_DATETIME`   DATETIME                        COMMENT '更新日時',
    PRIMARY KEY (`PROJECT_CODE`, `CATEGORY_CODE`)
);

ALTER TABLE `CATEGORY` COMMENT 'タスク一覧テーブル';
