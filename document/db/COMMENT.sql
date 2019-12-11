DROP TABLE IF EXISTS  `COMMENT`;

CREATE TABLE `COMMENT`
(
    `PROJECT_CODE`      CHAR(36)            NOT NULL    COMMENT '所属タスク',
    `INDEX`             INTEGER UNSIGNED    NOT NULL    COMMENT '6桁数値を1区切り',
    `TEXT`              VARCHAR(1024)       NOT NULL    COMMENT '内容',
    `CREATE_USER`       VARCHAR(256)        NOT NULL    COMMENT '作成ユーザーID',
    `CREATE_DATETIME`   DATETIME            NOT NULL    COMMENT '作成日時',
    `UPDATE_USER`       VARCHAR(256)        NOT NULL    COMMENT '更新ユーザーID',
    `UPDATE_DATETIME`   DATETIME            NOT NULL    COMMENT '更新日時',
    PRIMARY KEY (`PROJECT_CODE`, `INDEX`)
);

ALTER TABLE `COMMENT` COMMENT 'コメントテーブル';
