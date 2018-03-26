**開発環境**
====
* CentOS Linux release 7.4(仮想環境※Vagrantを使用)
* PHP 7.1.12
* Phalcon 3.2.4
* MySQL 5.7.20
* Apache 2.4.6

**開発環境構築手順**
====
* **CentOS7の公式Boxを追加・Vagrantの初期化を実行**

  vagrant box add centos/7  
  vagrant init centos/7  
  Vagrantfileに下記内容を追記  
  ```
  config.vm.network "forwarded_port", guest: 80, host: 8081, host_ip: "127.0.0.1"
  config.vm.network "private_network", ip: "ホストブラウザから確認する為のIP"
  config.vm.synced_folder "ホストの共有フォルダ", "リモートの共有フォルダ", :owner => 'vagrant', :group => 'vagrant', :mount_options => ['dmode=777','fmode=777'], type: "rsync", rsync__exclude: ["logs/*"]
  ```

* **Vagrant起動・ssh接続を実行**

  vagrant up  
  vagrant ssh

* **ファイヤーウォールの無効化**

  systemctl stop firewalld  
  systemctl disable firewalld

* **SELinuxの無効化**  

  vi /etc/selinux/config  
  ```
  SELINUX=disabled
  ```

* **外部リポジトリ(EPEL,REMI)をインストール**  

  yum -y update  
  yum -y install epel-release  
  yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

* **PHP7.1をインストール**  

  yum -y install --enablerepo=remi-php71 php  
  yum -y install --enablerepo=remi-php71 php-devel php-pear  
  yum -y install --enablerepo=remi-php71 php-mysqlnd php-mbstring  

* **MySQL5.7をインストール(初期設定は割愛)**  
  
  yum -y install http://dev.mysql.com/get/mysql57-community-release-el7-9.noarch.rpm  
  yum -y install mysql-community-server  
  db.sqlに記載されたSQLを実行し、DBおよびTableを作成する

* **httpdをインストール**  
  
  yum -y install httpd  

* **Phalconのインストール**  

  yum -y install --enablerepo=remi-php71 zephir  
  yum -y install --enablerepo=remi-php71 php-phalcon3  

* **git,Composerをインストール**  

  yum -y install git

  cd /tmp && curl -s http://getcomposer.org/installer | php  
  mv /tmp/composer.phar /usr/local/bin/composer

* **ドキュメントルート配下に課題リポジトリをクローン**  

  git clone https://github.com/ShuheiOkamoto/product-rest-api.git  

* **composerを使ってライブラリをインストール**  

  composer.jsonが配置されているディレクトリに移動し、下記コマンドを実行  
  composer install  
  composer update  

* **httpd.confを自身の環境に合わせて編集**  

  .htaccess用の設定を編集
  ```
  AllowOverride all  
  ```
  ```
  <Files ".ht*">

  </Files>
  ```
  ドキュメントルートの設定(/var/www/html/product-rest-api/public等環境に合わせて編集)
  ```
  DocumentRoot "/var/www/html/XXXXXX"  
  ```
  ```
  <IfModule dir_module>
    DirectoryIndex index.html index.php
  </IfModule>
  ```

**データベース情報**
====
* データベース名: objects
* 商品テーブル: products  (id, title, description, price, image, delete_flg, created_at, updated_at)
* 日毎集計結果テーブル: summaries  (summary_date, id, delete_flg, created_at, updated_at)
* データベース・テーブル定義・初期データINSERT文は「db.sql」ファイルに記述

**商品データ取得(全件)**
----
  全ての商品データを取得

* **URL**

  /api/products

* **Method:**

  `GET`

* **Success Response:**

  * **Code:** 200
    **Content:**
    ```json
    {
      "status": "FOUND",
      "data": [
                {
                  "id": "1",
                  "title": "Phalcon Cookbook",
                  "description": "Master Phalcon by ~",
                  "price": "4864",
                  "image": "data:image/~"
                }
              ]
    }
    ```

  OR

  * **Content:** `{ "status" => "NOT-FOUND" }`※登録データなしの場合

**商品データ取得(商品タイトルによる検索)**
----
  URLによって指定した商品タイトルの商品データを取得

* **URL**

  /api/products/search/{title}

* **Method:**

  `GET`

* **Success Response:**

  * **Code:** 200
    **Content:**
    ```json
    {
      "status": "FOUND",
      "data": [
              {
                "id": "1",
                "title": "Phalcon Cookbook",
                "description": "Master Phalcon by ~",
                "price": "4864",
                "image": "data:image/~"
              }
            ]
    }
    ```

  OR

  * **Content:** `{ "status" => "NOT-FOUND" }`※URLとして指定した商品タイトルに合致する商品データがない場合

**商品データ取得(idによる検索)**
----
  URLによって指定したidの商品データを取得

* **URL**

  /api/products/{id}

* **Method:**

  `GET`

* **Success Response:**

  * **Code:** 200
    **Content:** idに合致した商品データ
    ```json
    {
      "status": "FOUND",
      "data": [
              {
                "id": "1",
                "title": "Phalcon Cookbook",
                "description": "Master Phalcon by ~",
                "price": "4864",
                "image": "data:image/~"
              }
            ]
    }
    ```

  OR

  * **Content:** `{ "status" => "NOT-FOUND" }`※URLとして指定した商品idに合致する商品データがない場合

**商品データ新規登録処理**
----
  商品データを新規登録

* **URL**

  /api/products

* **Method:**

  `POST`

* **Input**

  ```json
  {
    "title": "Phalcon Cookbook",
    "description": "Master Phalcon by ~",
    "price": "4864",
    "image": "data:image/~"
  }
  ```

* **Success Response:**

  * **Code:** 201
    **Content:** 登録された商品データ
    ```json
    {
      "status": "OK",
      "data": [
              {
                "title": "Phalcon Cookbook",
                "description": "Master Phalcon by ~",
                "price": "4864",
                "image": "data:image/~",
                "id": "1"
              }
            ]
    }
    ```
* **Error Response:**

  * **Code:** 409
    **Content:** エラー内容
    ```json
    {
      "status": "ERROR",
      "messages": "エラーメッセージ"
    }
    ```

**商品データ更新処理(idによる更新)**
----
  URLによって指定したidの商品データを更新

* **URL**

  /api/products/{id}

* **Method:**

  `PUT`

* **Input**

  ```json
  {
    "title": "Phalcon Cookbook Updated",
    "description": "Master Phalcon by ~ Updated",
    "price": "7777",
    "image": ""
  }
  ```

* **Success Response:**

  * **Code:** 200
    **Content:** 通信成功メッセージ
    ```json
    {
      "status": "OK"
    }
    ```
* **Error Response:**

  * **Code:** 409
    **Content:** エラー内容
    ```json
    {
      "status": "ERROR",
      "messages": "エラーメッセージ"
    }
    ```

**商品データ削除処理(idによる削除)**
----
  URLによって指定したidの商品データを削除

* **URL**

  /api/products/{id}

* **Method:**

  `DELETE`

* **Success Response:**

  * **Code:** 200
    **Content:** 通信成功メッセージ
    ```json
    {
      "status": "OK"
    }
    ```
* **Error Response:**

  * **Code:** 409
    **Content:** エラー内容
    ```json
    {
      "status": "ERROR",
      "messages": "エラーメッセージ"
    }
    ```

**定時ジョブ実行処理 - 設計資料**
----
* **処理内容**

  毎日0時に当日登録・削除された商品データ*の集計を行う。  
  ※summariesテーブルに商品id,delete_flg,集計日(summary_date)を登録

* **cronコマンド**

  0 0 \* \* \* php <cli.phpの絶対パス> Products main  