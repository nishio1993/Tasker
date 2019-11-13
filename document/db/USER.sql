DROP TABLE `USER`;

CREATE TABLE `USER`
(
    `USER_ID`           VARCHAR(16)     NOT NULL    COMMENT '半角英字・半角数字・半角記号のみ',
    `PASSWORD`          VARCHAR(256)    NOT NULL    COMMENT 'パスワード',
    `NAME`              VARCHAR(32)     NOT NULL    COMMENT 'ユーザー名',
    `MAIL`              VARCHAR(256)                COMMENT 'メールアドレス',
    `CREATE_DATETIME`   DATETIME                    COMMENT '作成日時',
    `UPDATE_DATETIME`   DATETIME                    COMMENT '更新日時'
);

ALTER TABLE `USER` ADD CONSTRAINT PK_USER PRIMARY KEY (
    `USER_ID`
);

ALTER TABLE `USER` COMMENT 'ユーザーテーブル';
