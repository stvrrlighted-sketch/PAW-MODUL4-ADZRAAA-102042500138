<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TelkomLibrary - Katalog Buku Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .navbar-brand { font-weight: 700; }
        .book-card:hover { transform: translateY(-4px); transition: transform 0.2s ease; }
        .book-cover { height: 240px; object-fit: cover; width: 100%; }
        .book-cover-placeholder {
            height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            color: #adb5bd;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('home')); ?>">
                <i class="bi bi-book me-1"></i> TelkomLibrary
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="<?php echo e(route('home')); ?>">Katalog Buku</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item me-3 text-white small">
                            <i class="bi bi-person-circle me-1"></i> <?php echo e(Auth::user()->name); ?>

                            <span class="badge bg-warning text-dark ms-1"><?php echo e(ucfirst(Auth::user()->role)); ?></span>
                        </li>
                        <?php if(Auth::user()->role === 'admin'): ?>
                            <li class="nav-item me-2">
                                <a class="btn btn-outline-light btn-sm" href="<?php echo e(route('dashboard')); ?>">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-light btn-sm" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                                    Logout
                                </button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-light btn-sm" href="<?php echo e(route('login')); ?>">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm fw-semibold text-primary" href="<?php echo e(route('register')); ?>">
                                <i class="bi bi-person-plus me-1"></i> Daftar
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        
        <div class="text-center mb-5">
            <h1 class="fw-bold">Katalog Koleksi Buku</h1>
            <p class="text-muted">Temukan dan pinjam buku referensi perkuliahan terbaikmu</p>

            
            <div class="row justify-content-center mt-4">
                <div class="col-lg-11">
                    <form action="<?php echo e(url()->current()); ?>" method="GET" class="card shadow-sm border-0 p-3 bg-light text-start">
                        <div class="row g-2 align-items-center">
                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Cari judul, penulis..." 
                                           value="<?php echo e(request('search')); ?>">
                                </div>
                            </div>

                            
                            <div class="col-md-3">
                                <select name="penerbit" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Penerbit</option>
                                    <?php $__currentLoopData = $listPenerbit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p); ?>" <?php echo e(request('penerbit') === $p ? 'selected' : ''); ?>>
                                            <?php echo e($p); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-2">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="tersedia" <?php echo e(request('status') === 'tersedia' ? 'selected' : ''); ?>>
                                        Stok Tersedia
                                    </option>
                                    <option value="habis" <?php echo e(request('status') === 'habis' ? 'selected' : ''); ?>>
                                        Stok Habis
                                    </option>
                                </select>
                            </div>

                            
                            <div class="col-md-2">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="">Urutkan: Terbaru</option>
                                    <option value="judul_asc" <?php echo e(request('sort') === 'judul_asc' ? 'selected' : ''); ?>>Judul (A - Z)</option>
                                    <option value="judul_desc" <?php echo e(request('sort') === 'judul_desc' ? 'selected' : ''); ?>>Judul (Z - A)</option>
                                    <option value="tahun_desc" <?php echo e(request('sort') === 'tahun_desc' ? 'selected' : ''); ?>>Tahun (Terbaru)</option>
                                    <option value="tahun_asc" <?php echo e(request('sort') === 'tahun_asc' ? 'selected' : ''); ?>>Tahun (Terlama)</option>
                                    <option value="stok_desc" <?php echo e(request('sort') === 'stok_desc' ? 'selected' : ''); ?>>Stok Terbanyak</option>
                                </select>
                            </div>

                            
                            <div class="col-md-1 d-flex">
                                <?php if(request('search') || request('penerbit') || request('status') || request('sort')): ?>
                                    <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-danger w-100" title="Reset Semua Filter">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-primary w-100" type="submit" title="Terapkan">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if(request('search') || request('penerbit') || request('status') || request('sort')): ?>
                <p class="text-muted mt-3 small">
                    Menampilkan hasil filter: 
                    <?php if(request('search')): ?> pencarian <strong>"<?php echo e(request('search')); ?>"</strong><?php endif; ?>
                    <?php if(request('penerbit')): ?> &bull; penerbit <strong>"<?php echo e(request('penerbit')); ?>"</strong><?php endif; ?>
                    <?php if(request('status')): ?> &bull; status <strong>"<?php echo e(request('status') === 'tersedia' ? 'Stok Tersedia' : 'Stok Habis'); ?>"</strong><?php endif; ?>
                    <?php if(request('sort')): ?> &bull; urutan <strong>"<?php echo e(request('sort')); ?>"</strong><?php endif; ?>
                    &mdash; ditemukan <strong><?php echo e($buku->total()); ?></strong> buku
                </p>
            <?php endif; ?>
        </div>

        
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $buku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 book-card">
                    <?php if($item->cover): ?>
                        <img src="<?php echo e(asset('storage/' . $item->cover)); ?>" class="card-img-top book-cover" alt="<?php echo e($item->judul); ?>">
                    <?php else: ?>
                        <div class="book-cover-placeholder">
                            <div class="text-center">
                                <i class="bi bi-book fs-1 d-block mb-1"></i>
                                <span>Tidak Ada Sampul</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold text-truncate mb-1" title="<?php echo e($item->judul); ?>"><?php echo e($item->judul); ?></h6>
                        <p class="card-text text-muted small mb-2"><?php echo e($item->penulis); ?></p>
                        <div class="mt-auto">
                            <span class="badge <?php echo e($item->stok > 0 ? 'bg-success' : 'bg-danger'); ?> mb-2">
                                <?php echo e($item->stok > 0 ? 'Tersedia (' . $item->stok . ')' : 'Stok Kosong'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                        <a href="<?php echo e(route('buku.publik.show', $item->id)); ?>" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted fs-5">Buku tidak ditemukan.</p>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-primary btn-sm">Lihat Semua Buku</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        
        <?php if($buku->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($buku->links('pagination::bootstrap-5')); ?>

            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-white py-3 border-top text-center text-muted small mt-5">
        <div class="container">
            <span>&copy; <?php echo e(date('Y')); ?> TelkomLibrary - Sistem Informasi Perpustakaan Kampus</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php /**PATH /Users/adzraaaliyyahalthaaf/Documents/MODUL4_PRAKTIKUM-PAW/resources/views/home.blade.php ENDPATH**/ ?>