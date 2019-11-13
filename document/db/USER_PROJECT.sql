DROP TABLE `USER_PROJECT`;

CREATE TABLE `USER_PROJECT`
(
    `USER_ID`       VARCHAR(16)         NOT NULL    COMMENT 'ユーザーID',
    `PROJECT_CODE`  VARCHAR(36)         NOT NULL    COMMENT '所属中プロジェクトコード',
    `USER_INDEX`    TINYINT UNSIGNED                COMMENT 'ユーザー表示順',
    `PROJECT_INDEX` TINYINT UNSIGNED                COMMENT 'プロジェクト表示順'
);

ALTER TABLE `USER_PROJECT` COMMENT 'ユーザー・プロジェクト紐付テーブル';
