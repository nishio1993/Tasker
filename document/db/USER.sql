DROP TABLE `USER`;

CREATE TABLE `USER`
(
    `MAIL`              VARCHAR(256)    NOT NULL    COMMENT 'メールアドレス、ユーザー特定、セキュリティに使用',
    `NAME`              VARCHAR(32)     NOT NULL    COMMENT 'ユーザー名',
    `PASSWORD`          VARCHAR(256)    NOT NULL    COMMENT 'パスワード',
    `CREATE_DATETIME`   DATETIME                    COMMENT '作成日時',
    `UPDATE_DATETIME`   DATETIME                    COMMENT '更新日時'
);

ALTER TABLE `USER` ADD CONSTRAINT PK_USER PRIMARY KEY (
    `MAIL`
);

ALTER TABLE `USER` COMMENT 'ユーザーテーブル';
