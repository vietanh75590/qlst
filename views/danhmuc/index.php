<?php
require_once 'views/layout/header.php';
$userRole = strtolower($_SESSION['user_role'] ?? 'guest');
$isAdmin = ($userRole === 'admin');
?>

<h2><?= ($userRole === 'customer') ? 'DANH MỤC NGÀNH HÀNG' : 'QUẢN LÝ DANH MỤC HÀNG HÓA' ?></h2>

<table border="0" width="100%">
    <tr>
        <td>Danh sách các ngành hàng đang kinh doanh</td>
        <td align="right">
            <?php if ($isAdmin): ?>
                <a href="index.php?controller=danhmuc&action=create"><b>+ Thêm danh mục mới</b></a>
            <?php endif; ?>
        </td>
    </tr>
</table>

<br>

<style>
.danhmuc-parent {
    background-color: #f9f9f9;
    cursor: pointer;
    user-select: none;
}
.danhmuc-parent:hover {
    background-color: #f0f0f0;
}
.danhmuc-child {
    background-color: #ffffff;
    padding-left: 40px !important;
    display: none;
}
.danhmuc-child.show {
    display: table-row;
}
.danhmuc-child-clickable:hover {
    background-color: #ffffcc;
}
.expand-icon {
    display: inline-block;
    width: 20px;
    text-align: center;
    font-weight: bold;
    color: #0066cc;
}
</style>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr bgcolor="#f2f2f2">
        <th width="15%">Mã danh mục</th>
        <th width="35%">Tên danh mục</th>
        <th>Mô tả</th>
        <?php if ($isAdmin): ?>
            <th width="15%">Thao tác</th>
        <?php endif; ?>
    </tr>
    <?php if (!empty($danhmucs)): ?>
        <?php foreach ($danhmucs as $dm): ?>
            <tr class="danhmuc-parent" onclick="toggleChildren(this, event)">
                <td align="center"><b>
                    <?php if (!empty($dm['children'])): ?>
                        <span class="expand-icon">▶</span>
                    <?php endif; ?>
                    <?= $dm['id'] ?>
                </b></td>
                <td><b><?= htmlspecialchars($dm['tendanhmuc']) ?></b></td>
                <td><?= htmlspecialchars($dm['mota'] ?? '') ?></td>
                <?php if ($isAdmin): ?>
                    <td align="center">
                        <a href="index.php?controller=danhmuc&action=edit&id=<?= $dm['id'] ?>" onclick="event.stopPropagation()">Sửa</a> |
                        <a href="index.php?controller=danhmuc&action=delete&id=<?= $dm['id'] ?>" onclick="event.stopPropagation()">Xóa</a>
                    </td>
                <?php endif; ?>
            </tr>

            <?php if (!empty($dm['children'])): ?>
                <?php foreach ($dm['children'] as $child): ?>
                    <tr class="danhmuc-child danhmuc-child-clickable" data-parent="<?= $dm['id'] ?>" data-category-id="<?= $child['id'] ?>" style="cursor: pointer;">
                        <td align="center"><b><?= $child['id'] ?></b></td>
                        <td style="padding-left: 40px;"><b><?= htmlspecialchars($child['tendanhmuc']) ?></b></td>
                        <td><?= htmlspecialchars($child['mota'] ?? '') ?></td>
                        <?php if ($isAdmin): ?>
                            <td align="center">
                                <a href="index.php?controller=danhmuc&action=edit&id=<?= $child['id'] ?>" onclick="event.stopPropagation()">Sửa</a> |
                                <a href="index.php?controller=danhmuc&action=delete&id=<?= $child['id'] ?>" onclick="event.stopPropagation()">Xóa</a>
                            </td>
                        <?php else: ?>
                            <td align="center" style="cursor: pointer;">Xem sản phẩm →</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="<?= $isAdmin ? 4 : 3 ?>" align="center">Không có danh mục nào.</td>
        </tr>
    <?php endif; ?>
</table>

<script>
function toggleChildren(parentRow, event) {
    const parentId = parentRow.querySelector('b').textContent.match(/DM\d+/)[0];
    const children = document.querySelectorAll(`tr.danhmuc-child[data-parent="${parentId}"]`);
    const icon = parentRow.querySelector('.expand-icon');

    if (children.length === 0) return;

    const isShowing = children[0].classList.contains('show');
    children.forEach(child => {
        if (isShowing) {
            child.classList.remove('show');
        } else {
            child.classList.add('show');
        }
    });

    if (icon) {
        icon.textContent = isShowing ? '▶' : '▼';
    }
}

document.querySelectorAll('.danhmuc-child-clickable').forEach(row => {
    row.addEventListener('click', function(e) {
        if (e.target.tagName === 'A') return;
        const categoryId = this.getAttribute('data-category-id');
        window.location.href = 'index.php?controller=sanpham&action=index&id_danhmuc=' + categoryId;
    });
});
</script>

