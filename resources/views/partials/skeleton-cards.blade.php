@props(['count' => 6])

<div class="row g-4 skeleton-container" id="skeletonLoadingGrid" aria-hidden="true">
    @for($i = 0; $i < $count; $i++)
        <div class="col-12 col-md-6 col-lg-4 skeleton-card-col">
            <div class="card h-100 skeleton-card">
                <!-- Skeleton Preview Area -->
                <div class="skeleton-thumb skeleton-shimmer position-relative">
                    <!-- Category Badge Placeholder -->
                    <div class="position-absolute top-0 start-0 m-3 skeleton-shimmer rounded-pill" style="width: 80px; height: 26px;"></div>
                </div>

                <!-- Skeleton Card Body -->
                <div class="card-body p-4 d-flex flex-column gap-3">
                    <!-- Rating and Price Line -->
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="skeleton-shimmer rounded-pill" style="width: 70px; height: 20px;"></div>
                        <div class="skeleton-shimmer rounded-pill" style="width: 50px; height: 20px;"></div>
                    </div>

                    <!-- Title Lines -->
                    <div class="d-flex flex-column gap-2 my-1">
                        <div class="skeleton-line skeleton-shimmer" style="width: 85%; height: 16px;"></div>
                        <div class="skeleton-line skeleton-shimmer" style="width: 55%; height: 12px;"></div>
                    </div>

                    <!-- Author & Metrics Row -->
                    <div class="pt-3 border-top border-light-subtle d-flex align-items-center justify-content-between mt-auto">
                        <div class="d-flex align-items-center gap-2">
                            <div class="skeleton-avatar skeleton-shimmer"></div>
                            <div class="skeleton-line skeleton-shimmer" style="width: 80px; height: 12px;"></div>
                        </div>
                        <div class="skeleton-shimmer rounded-pill" style="width: 45px; height: 18px;"></div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
