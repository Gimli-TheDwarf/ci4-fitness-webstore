<?= $this->extend('Home') ?>

<?= $this->section('layout-content') ?>
<section class="homepage-hero w-100 bg-blue-gray px-3 pt-4 pt-lg-5 pb-5">
    <div class="container-xxl px-0 d-flex flex-column align-items-center">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
            <div class="text-white">
                <span class="small text-uppercase opacity-75 fw-semibold">Lave selection</span>
                <h1 class="h3 fw-semibold mb-1">Training essentials, presented clearly.</h1>
                <p class="mb-0 opacity-75">Browse focused gear and offers without visual noise.</p>
            </div>
            <a href="#product-list" class="btn btn-sm btn-outline-light fw-semibold rounded-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-grid"></i><span>View Products</span>
            </a>
        </div>

        <div id="carouselIndicators" class="carousel slide homepage-carousel w-100 overflow-hidden rounded-2 border shadow-sm bg-dark mx-auto mb-4">
            <div id="carousel-indicator-container" class="carousel-indicators d-flex flex-row w-100">
            </div>

            <div id="carouselSlides" class="carousel-inner">
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<section class="homepage-products w-100 d-flex flex-column min-vh-25 px-3 pt-5 pb-4 py-lg-5">
    <div class="container-xxl px-0 d-flex flex-column gap-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-3">
            <div>
                <span class="small text-uppercase text-orange fw-semibold">Catalog</span>
                <h2 class="h4 fw-semibold text-white mb-0">Products</h2>
            </div>
            <p class="text-white opacity-75 mb-0 small">Filter by category, compare details, then add the right quantity.</p>
        </div>

        <div id="tags-list" class="flex-shrink-0 w-100">
        </div>

        <div id="product-list" class="d-flex w-100">
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>

        $(document).ready(function() 
        {
            ImagesLoad();
        });

        function ImagesLoad()
        {
            var imageArray = [];
            const imageBaseUrl = "<?= base_url('images/homePageImages/') ?>";

            $.ajax({
                url: '<?= base_url('loadImages/homePageImages') ?>',
                method: 'GET',
                dataType: 'JSON',

                success: function(response) 
                {
                    console.log(response.message);
                    response.data.forEach(function(image) 
                    {
                        imageArray.push(imageBaseUrl + encodeURIComponent(image));
                    });
                    loadImages(imageArray);
                },

                error: function(jqXHR) 
                {
                    console.log(jqXHR.responseJSON.message);
                }
            });
        }

        function loadImages(images)
        {
            const carouselContainer  = document.getElementById("carouselSlides");
            const indicators = document.getElementById("carousel-indicator-container");

            carouselContainer.innerHTML = '';
            indicators.innerHTML = '';

            images.forEach((image, i) => 
            {
                var item = document.createElement('div');
                item.className = 'carousel-item h-100 w-100'
                item.id = `${i}_carousel_image`;
                item.classList.add('carousel-caption-container')

                var indicator = document.createElement('button');
                indicator.type = 'button';
                indicator.setAttribute('data-bs-target', '#carouselIndicators');
                indicator.setAttribute('data-bs-slide-to', `${i}`);
                indicator.setAttribute('aria-label', `Slide ${i}`);

                if (i === 0)
                {
                    item.classList.add('active');
                    indicator.setAttribute('aria-current', 'true');
                    indicator.classList.add('active');
                };

                item.innerHTML = 
                `
                    <img src="${image}" class="homepage-carousel-image d-block w-100 object-fit-cover" alt="Slide ${i + 1}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="fw-semibold text-shadow mb-1">Lave</h5>
                        <p class="mb-0 small opacity-75">Selected fitness products and practical essentials.</p>
                    </div>
                `;

                indicators.appendChild(indicator);
                carouselContainer.appendChild(item);
            });

                        
            const carousel = new bootstrap.Carousel('#carouselIndicators', 
            {
                interval: 4100,
                ride: 'carousel',
                pause: false
            });
        }
    </script>
<?= $this->endSection() ?>
