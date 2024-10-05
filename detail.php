<?php
// 1. IDの取得
$id = $_GET["id"];

include("funcs.php");
$pdo = db_conn();

// 2．データ取得SQL作成
$sql = "SELECT * FROM profiles WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  // Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

// 3．データ表示
if ($status == false) {
    sql_error($stmt);
}

// 取得データを変数に格納
$v = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Ｍｙプロフィール修正</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/form-update.css" rel="stylesheet">
</head>

<body>

    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="select.php">◀ メンバー 一覧へ</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <div class="form-container">
        <form method="POST" action="update.php" enctype="multipart/form-data">
            <fieldset>
                <legend>修正箇所を上書きしてください😌</legend>

                <label>名前：</label>
                <input type="text" name="name" value="<?= h($v['name']); ?>">

                <label>ニックネーム：</label>
                <input type="text" name="nickname" value="<?= h($v['nickname']); ?>"> <!-- ニックネームを追加 -->

                <label>フリガナ：</label>
                <input type="text" name="furigana" value="<?= h($v['furigana']); ?>">

                <label>Email：</label>
                <input type="email" name="email" value="<?= h($v['email']); ?>">

                <label>自己紹介文：</label>
                <textarea name="introduction" rows="4"><?= h($v['introduction']); ?></textarea>

                <label>一言メッセージ：</label>
                <textarea name="message" rows="2"><?= h($v['message']); ?></textarea> <!-- 一言メッセージを追加 -->

                <label>趣味・特技：</label>
                <textarea name="hobby_skill" rows="2"><?= h($v['hobby_skill']); ?></textarea>

                <label>プロフィール画像：</label>
                <input type="file" name="profile_image">
                
                <?php if ($v['profile_image']): ?>
                    <p>現在の画像：</p>
                    <img src="<?= h($v['profile_image']); ?>" alt="プロフィール画像">
                <?php else: ?>
                    <p>画像は登録されていません。</p>
                <?php endif; ?>

                <!-- <label><input type="checkbox" name="consent" value="1" <?= $v['consent'] == 1 ? "checked" : "" ?>> 個人情報の利用に同意する</label> -->

                <input type="hidden" name="id" value="<?= h($v['id']); ?>"> <!-- IDをhiddenで渡す -->

                <input type="submit" value="更新する">
            </fieldset>
        </form>
    </div>
    <!-- Main[End] -->

</body>

</html>
