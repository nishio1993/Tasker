DROP TABLE IF EXISTS `USER_PROJECT`;

CREATE TABLE `USER_PROJECT`
(
    `MAIL`          VARCHAR(256)        NOT NULL    COMMENT 'ユーザーID',
    `PROJECT_CODE`  VARCHAR(36)         NOT NULL    COMMENT '所属中プロジェクトコード',
    `USER_INDEX`    TINYINT UNSIGNED                COMMENT 'ユーザー表示順',
    `PROJECT_INDEX` TINYINT UNSIGNED                COMMENT 'プロジェクト表示順',
    PRIMARY KEY (`MAIL`, `PROJECT_CODE`)
);

ALTER TABLE `USER_PROJECT` COMMENT 'ユーザー・プロジェクト紐付テーブル';
