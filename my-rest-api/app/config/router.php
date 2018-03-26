<?php
use Phalcon\Mvc\Router;

$router = $di->getRouter(false);

$router->setDefaultNamespace("Modules\Controllers");

//初期表示
$router->addGet(
    "/",
    [
        "controller" => "session",
        "action" => "index",
    ]
);

//ログイン処理
$router->addGet(
    "/login",
    [
        "controller" => "session",
        "action" => "login",
    ]
);

//ログインユーザー情報表示処理
$router->addGet(
    "/login/user",
    [
        "controller" => "session",
        "action" => "user",
    ]
);

//集計情報検索処理
$router->addPost(
    "/login/user/search",
    [
        "controller" => "session",
        "action" => "search",
    ]
);

//ログアウト処理
$router->addGet(
    "/logout",
    [
        "controller" => "session",
        "action" => "logout",
    ]
);

// 登録済みの商品データを全件取得
$router->addGet(
    "/api/products",
    [
        "controller" => "products",
        "action" => "search",
    ]
);

// 商品タイトルによる商品データ検索処理
$router->addGet(
    "/api/products/search/{title}",
    [
        "controller" => "products",
        "action" => "searchByTitle",
    ]
);

// // idによる商品データ検索処理
$router->addGet(
    "/api/products/{id:[0-9]+}",
    [
        "controller" => "products",
        "action" => "searchById",
    ]
);

// // 商品データ新規登録処理
$router->addPost(
    "/api/products",
    [
        "controller" => "products",
        "action" => "register",
    ]
);

// // idによる商品データ更新処理
$router->addPut(
    "/api/products/{id:[0-9]+}",
    [
        "controller" => "products",
        "action" => "update",
    ]
);

// // idによる商品データ削除処理
$router->addDelete(
    "/api/products/{id:[0-9]+}",
    [
        "controller" => "products",
        "action" => "delete",
    ]
);

// 定義されていないルートの場合
$router->notFound(
    [
        "controller" => "error",
        "action"     => "show404",
    ]
);

$router->handle();