DROP TABLE IF EXISTS `USER`;

CREATE TABLE `USER`
(
    `MAIL`              VARCHAR(256)    NOT NULL    COMMENT 'メールアドレス、ユーザー特定、セキュリティに使用',
    `USER_NAME`         VARCHAR(32)     NOT NULL    COMMENT 'ユーザー名',
    `PASSWORD`          VARCHAR(256)    NOT NULL    COMMENT 'パスワード',
    `CREATE_DATETIME`   DATETIME                    COMMENT '作成日時',
    `UPDATE_DATETIME`   DATETIME                    COMMENT '更新日時',
    PRIMARY KEY (`MAIL`)
);

ALTER TABLE `USER` COMMENT 'ユーザーテーブル';
