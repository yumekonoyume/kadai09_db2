<?php
include("funcs.php");
$pdo = db_conn();

// 並び替えの条件を決める
$order_by = "created_at DESC"; // 初期は最新順（登録順）
if (isset($_GET['order']) && $_GET['order'] === 'furigana') {
    $order_by = "furigana ASC"; // フリガナ順に変更
}

// 2．データ登録SQL作成 (created_at でソートまたはフリガナ順)
$sql = "SELECT * FROM profiles ORDER BY $order_by";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// 3．データ表示
$values = []; // 初期化
if ($status == false) {
    sql_error($stmt);
} else {
    $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 全データ取得後の表示処理
$json = json_encode($values, JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>メンバー 一覧</title>
    <link href="css/style.css" rel="stylesheet"> <!-- 共通スタイルの読み込み -->
</head>
<body id="main">

<!-- Head[Start] -->
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">◀ Myプロフィール登録へ</a>
            </div>
        </div>
    </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div class="container">
    <div class="sort-buttons">
        <!-- ソートボタン: フリガナ順と登録順 -->
        <a href="select.php" class="btn btn-primary">登録順</a>
        <a href="select.php?order=furigana" class="btn btn-primary">フリガナ順</a>
    </div>

    <!-- メンバー一覧表示 -->
    <?php if (!empty($values)) { ?>
        <?php foreach ($values as $v) { ?>
            <div class="member-card">
                <div>
                    <?php if ($v["profile_image"]) { ?>
                        <img src="<?= h($v["profile_image"]) ?>" alt="プロフィール画像">
                    <?php } else { ?>
                        <img src="default-profile.png" alt="画像なし"> <!-- デフォルト画像 -->
                    <?php } ?>
                </div>
                <div class="member-details">
                    <!-- 名前とフリガナの表示 -->
                    <div class="member-header">
                        <h3><?= h($v["name"]) ?></h3>
                        <p class="furigana"><?= h($v["furigana"]) ?></p> <!-- フリガナを小さく表示 -->
                    </div>
                    
                    <!-- ニックネーム -->
                    <p class="member-bio-heading">ニックネーム</p>
                    <p><?= h($v["nickname"]) ?></p>
                    
                    <!-- 自己紹介とその他の情報 -->
                    <div class="member-bio">
                        <p class="member-bio-heading">自己紹介</p>
                        <p><?= h($v["introduction"]) ?></p>

                        <p class="member-bio-heading">趣味・特技</p>
                        <p><?= h($v["hobby_skill"]) ?></p>

                        <p class="member-bio-heading">メッセージ</p>
                        <p><?= h($v["message"]) ?></p>
                    </div>

                    <!-- ボタン -->
                    <div class="member-footer">
                        <a href="detail.php?id=<?= h($v["id"]) ?>" class="btn btn-primary">更新</a>
                        <a href="delete.php?id=<?= h($v["id"]) ?>" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>メンバーがまだ登録されていません。</p>
    <?php } ?>
</div>
<!-- Main[End] -->

</body>
</html>
