<?php
/*
Plugin Name: iProxy Image Plugin
Description: A WordPress plugin that provides a REST API endpoint for iProxy image functionality.
             ex) http://your-wordpress-site.com/iproxy/image?q=http://example.com/image.jpg
             thank you Wordpress!
Version: 1.0
Author: Your Name
*/

// REST APIエンドポイントを登録
add_action('rest_api_init', function () {
    register_rest_route('iproxy', '/image', array(
        'methods' => 'GET',
        'callback' => function($request){
          // リクエストからパラメータを取得
          // iProxy関数を呼び出して結果を返す
          echo iProxy($request->get_param('q'));          
        },
    ));
});


/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

// iProxy関数の定義
function iProxy($url)
{
    // 入力がNULLの場合にエラーメッセージを返す
    if ($url === NULL) {
        return "エラー: q=urlパラメータが存在しません";
    }

    // ターゲットURLのレスポンスを取得
    $response = file_get_contents($url);

    // エラーハンドリング
    if ($response === false) {
        return "エラー: ターゲットURLへのリクエストに失敗しました。";
    }

    // レスポンスヘッダーを設定
    // Content-Type を画像の汎用的なファイル形式に設定
    header("Content-Type: image/*");

    // クロスオリジンリソースシェアリング（CORS）のために、Access-Control-Allow-Origin を設定
    header("Access-Control-Allow-Origin: *");

    // クライアントにレスポンスを返す
    return $response;
}

