
<?php
require_once __DIR__ . '/config/database.php';

// Lay tu khoa tim kiem
$keyword = trim($_GET['q'] ?? '');
$categoryId = filter_input(
    INPUT_GET,
    'danh_muc',
    FILTER_VALIDATE_INT
);

if (!$categoryId || $categoryId < 1) {
    $categoryId = null;
}

// Lay danh muc sach
$categories = [];
$books = [];
$errorMessage = '';

try {
    $categoryStmt = $conn->query(
        "SELECT id_danh_muc, ten_danh_muc
         FROM danh_muc
         WHERE trang_thai = 1
         ORDER BY ten_danh_muc"
    );
    $categories = $categoryStmt->fetchAll();

    // Lay sach dang ban, ket hop ten danh muc
    $sql = "
        SELECT
            s.id_sach,
            s.ma_sach,
            s.ten_sach,
            s.gia_ban,
            s.so_luong_ton,
            s.anh_bia,
            s.nha_xuat_ban,
            s.mo_ta,
            d.ten_danh_muc
        FROM sach s
        LEFT JOIN danh_muc d
            ON s.id_danh_muc = d.id_danh_muc
        WHERE s.trang_thai = 'dang_ban'
    ";

    $params = [];

    if ($keyword !== '') {
        $sql .= "
            AND (
                s.ten_sach LIKE :keyword
                OR s.ma_sach LIKE :keyword
            )
        ";
        $params['keyword'] = '%' . $keyword . '%';
    }

    if ($categoryId !== null) {
        $sql .= " AND s.id_danh_muc = :category_id";
        $params['category_id'] = $categoryId;
    }

    $sql .= " ORDER BY s.ngay_tao DESC, s.id_sach DESC";

    $bookStmt = $conn->prepare($sql);
    $bookStmt->execute($params);
    $books = $bookStmt->fetchAll();

} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorMessage = 'Không thể tải dữ liệu sách. Vui lòng thử lại sau.';
}

// Ham bao ve noi dung khi hien thi HTML
function e($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

// Ham hien thi gia
function formatPrice($price) {
    return number_format((float)$price, 0, ',', '.') . ' đ';
}

// Duong dan anh bia
function bookImage($image) {
    $image = trim((string)$image);

    if ($image === '') {
        return '';
    }

    // Chi chap nhan ten file, khong chap nhan URL tuy y
    if (
        preg_match('/^[a-zA-Z0-9_\-\/.]+$/', $image)
        && strpos($image, '..') === false
    ) {
        return 'uploads/books/' . ltrim($image, '/');
    }

    return '';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BookNest - Nhà sách trực tuyến</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="logo-icon">B</span>
            <span>BookNest<span class="logo-dot">.</span></span>
        </a>

        <form action="index.php" method="GET" class="search-form">
            <input
                type="text"
                name="q"
                placeholder="Tìm tên sách, mã sách..."
                value="<?= e($keyword) ?>"
            >
            <?php if ($categoryId !== null): ?>
                <input
                    type="hidden"
                    name="danh_muc"
                    value="<?= (int)$categoryId ?>"
                >
            <?php endif; ?>
            <button type="submit" aria-label="Tìm sách">⌕</button>
        </form>

        <nav class="header-actions">
            <a href="#danh-muc">Danh mục</a>
            <a href="#sach-moi">Sách mới</a>
            <a href="login.php" class="login-link">Đăng nhập</a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="hero-content">
            <span class="eyebrow">KHÔNG GIAN DÀNH CHO NGƯỜI YÊU SÁCH</span>
            <h1>Mỗi cuốn sách,<br>một thế giới mới.</h1>
            <p>
                Khám phá những trang sách truyền cảm hứng,
                mở rộng kiến thức và nuôi dưỡng trí tưởng tượng.
            </p>
            <a href="#sach-moi" class="primary-button">
                Khám phá sách <span>→</span>
            </a>
        </div>

        <div class="hero-art" aria-hidden="true">
            <div class="decor-circle"></div>
            <div class="book book-back"></div>
            <div class="book book-middle"></div>
            <div class="book book-front">
                <span>THE ART OF</span>
                <strong>READING</strong>
                <small>A WORLD OF STORIES</small>
            </div>
            <div class="hero-sparkle">✳</div>
        </div>
    </section>

    <section class="category-section section-wrap" id="danh-muc">
        <div class="section-heading">
            <div>
                <span class="eyebrow">TÌM CUỐN SÁCH CỦA BẠN</span>
                <h2>Khám phá danh mục</h2>
            </div>
            <a href="index.php" class="text-link">Tất cả sách ↗</a>
        </div>

        <div class="category-list">
            <a
                href="index.php"
                class="category-chip <?= $categoryId === null ? 'active' : '' ?>"
            >
                Tất cả sách
            </a>

            <?php foreach ($categories as $category): ?>
                <a
                    href="index.php?danh_muc=<?= (int)$category['id_danh_muc'] ?><?= $keyword !== '' ? '&q=' . urlencode($keyword) : '' ?>"
                    class="category-chip <?= $categoryId === (int)$category['id_danh_muc'] ? 'active' : '' ?>"
                >
                    <?= e($category['ten_danh_muc']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="books-section section-wrap" id="sach-moi">
        <div class="section-heading">
            <div>
                <span class="eyebrow">TỪ KỆ SÁCH CỦA CHÚNG TÔI</span>
                <h2>
                    <?= $keyword !== '' ? 'Kết quả tìm kiếm' : 'Sách dành cho bạn' ?>
                </h2>
                <p class="section-description">
                    <?php if ($keyword !== ''): ?>
                        Từ khóa: “<?= e($keyword) ?>”
                    <?php else: ?>
                        Những cuốn sách đang được cửa hàng kinh doanh.
                    <?php endif; ?>
                </p>
            </div>
            <span class="book-count"><?= count($books) ?> cuốn sách</span>
        </div>

        <?php if ($errorMessage !== ''): ?>
            <div class="empty-state">
                <h3>Chưa tải được danh sách sách</h3>
                <p><?= e($errorMessage) ?></p>
            </div>

        <?php elseif (empty($books)): ?>
            <div class="empty-state">
                <div class="empty-icon">📚</div>
                <h3>
                    <?= $keyword !== ''
                        ? 'Không tìm thấy sách phù hợp'
                        : 'Hiện chưa có sách' ?>
                </h3>
                <p>
                    <?= $keyword !== ''
                        ? 'Bạn hãy thử từ khóa khác nhé.'
                        : 'Sách sẽ xuất hiện tại đây khi người quản lý thêm sách và đặt trạng thái đang bán.' ?>
                </p>

                <?php if ($keyword !== '' || $categoryId !== null): ?>
                    <a href="index.php" class="primary-button">
                        Xem tất cả sách
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="book-grid">
                <?php foreach ($books as $book): ?>
                    <?php $imagePath = bookImage($book['anh_bia']); ?>

                    <article class="book-card">
                        <a
                            class="book-cover"
                            href="pages/book-detail.php?id=<?= (int)$book['id_sach'] ?>"
                        >
                            <?php if ($imagePath !== ''): ?>
                                <img
                                    src="<?= e($imagePath) ?>"
                                    alt="<?= e($book['ten_sach']) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                                >
                            <?php endif; ?>

                            <div
                                class="cover-placeholder"
                                <?= $imagePath !== '' ? 'style="display:none"' : '' ?>
                            >
                                <span class="placeholder-mark">B.</span>
                                <span><?= e($book['ten_sach']) ?></span>
                            </div>
                        </a>

                        <div class="book-info">
                            <span class="book-category">
                                <?= e($book['ten_danh_muc'] ?: 'Chưa phân loại') ?>
                            </span>

                            <h3>
                                <a href="pages/book-detail.php?id=<?= (int)$book['id_sach'] ?>">
                                    <?= e($book['ten_sach']) ?>
                                </a>
                            </h3>

                            <p class="publisher">
                                <?= e($book['nha_xuat_ban'] ?: 'Đang cập nhật nhà xuất bản') ?>
                            </p>

                            <div class="book-bottom">
                                <span class="book-price">
                                    <?= formatPrice($book['gia_ban']) ?>
                                </span>

                                <?php if ((int)$book['so_luong_ton'] > 0): ?>
                                    <span class="stock-status">Còn hàng</span>
                                <?php else: ?>
                                    <span class="stock-status out-stock">Hết hàng</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="reading-banner section-wrap">
        <div>
            <span class="eyebrow">DÀNH THỜI GIAN CHO BẢN THÂN</span>
            <h2>Một ngày tốt đẹp<br>bắt đầu từ một trang sách.</h2>
            <p>Chọn một cuốn sách và bắt đầu hành trình của riêng bạn.</p>
        </div>
        <a href="#sach-moi" class="banner-button">Tìm sách ngay ↗</a>
    </section>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <a href="index.php" class="logo">
            <span class="logo-icon">B</span>
            <span>BookNest<span class="logo-dot">.</span></span>
        </a>
        <p>Không gian dành cho những tâm hồn yêu sách.</p>
        <span>© <?= date('Y') ?> BookNest. All rights reserved.</span>
    </div>
</footer>

</body>
</html>
