<?php
require_once 'views/layout/header.php';
?>

<h2>CHỈNH SỬA DANH MỤC: <?= htmlspecialchars($danhmuc['tendanhmuc']) ?></h2>
<p><a href="index.php?controller=danhmuc&action=index">&laquo; Quay lại danh sách</a></p>

<form action="index.php?controller=danhmuc&action=edit&id=<?= $danhmuc['id'] ?>" method="POST">
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <td><b>Mã danh mục:</b></td>
            <td><b><?= $danhmuc['id'] ?></b></td>
        </tr>
        <tr>
            <td><b>Tên danh mục (*):</b></td>
            <td><input type="text" name="tendanhmuc" size="40" value="<?= htmlspecialchars($danhmuc['tendanhmuc']) ?>" required></td>
        </tr>
        <tr>
            <td><b>Danh mục cha:</b></td>
            <td>
                <select name="id_danhmuc_cha">
                    <option value="">-- Không có danh mục cha --</option>
                    <?php foreach ($danhmucs as $dm): ?>
                        <?php if ($dm['id'] !== $danhmuc['id']): ?>
                            <option value="<?= $dm['id'] ?>" <?= $danhmuc['id_danhmuc_cha'] == $dm['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dm['tendanhmuc']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><b>Mô tả:</b></td>
            <td><textarea name="mota" rows="4" cols="40"><?= htmlspecialchars($danhmuc['mota'] ?? '') ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Cập nhật danh mục">
                <a href="index.php?controller=danhmuc&action=index">Hủy</a>
            </td>
        </tr>
    </table>
</form>
