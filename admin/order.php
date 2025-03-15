<?php
include("../admin/includes/header.php");

$type = -1;

if (isset($_GET['type'])) {
    $type = $_GET['type'];
}

$orders = getAllOrder($type);

?>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Quản lý đơn hàng</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <!-- Bộ lọc đơn hàng -->
                            <div style="display: flex; align-items: center; gap: 10px; margin: 20px;">
                                <label for="orderFilter" style="font-weight: 500;">Trạng thái:</label>
                                <select id="orderFilter" class="form-control" style="width: 220px; padding: 8px; border: 2px solid black; border-radius: 6px; background-color: #f8f9fa;">
                                    <option value="-1" <?= ($type == -1) ? 'selected' : '' ?>>Tất cả</option>
                                    <option value="2" <?= ($type == 2) ? 'selected' : '' ?>>Đã đặt</option>
                                    <option value="3" <?= ($type == 3) ? 'selected' : '' ?>>Đang giao</option>
                                    <option value="4" <?= ($type == 4) ? 'selected' : '' ?>>Hoàn tất</option>
                                </select>
                            </div>



                            <!-- Bảng danh sách đơn hàng -->
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Khách hàng</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sản phẩm</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Địa chỉ</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order) { ?>
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">&nbsp &nbsp #<?= $order['id'] ?></p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?= $order['name'] ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?= $order['email'] ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <a href="./order-detail.php?id_order=<?= $order['id'] ?>">View now</a>
                                                </p>
                                                <p class="text-xs text-secondary mb-0">Quantity: <?= $order['quantity'] ?></p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?= $order['address'] ?></p>
                                                <p class="text-xs text-secondary mb-0"><?= $order['phone'] ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php  
                                                    if ($order['status'] == 2) {
                                                        echo '<span class="badge badge-sm bg-gradient-primary">Đã đặt</span>';
                                                    } elseif ($order['status'] == 3) {
                                                        echo '<span class="badge badge-sm bg-gradient-info">Đang giao</span>';
                                                    } elseif ($order['status'] == 4) {
                                                        echo '<span class="badge badge-sm bg-gradient-success">Hoàn tất</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    <?= date('d-m-Y', strtotime($order['created_at'])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript để xử lý bộ lọc -->
    <script>
        document.getElementById('orderFilter').addEventListener('change', function () {
            const selectedType = this.value;
            window.location.href = `order.php?type=${selectedType}`;
        });
    </script>
</body>

<?php include("../admin/includes/footer.php"); ?>
