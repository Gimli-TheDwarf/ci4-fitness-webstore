<?= $this->extend('Home') ?>

<?= $this->section('layout-content') ?>
 <div id="carouselIndicators" class="carousel slide d-flex justify-content-center rounded" style=" width: 160vh; height: auto;">
    <!-- Indicators -->
    <div id="carousel-indicator-container" class="carousel-indicators d-flex flex-row w-100">
    </div>
    
    <!-- Slides -->
    <div id="carouselSlides" class="carousel-inner">
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="bg-dark w-100 bg-gradient d-flex flex-column min-vh-25">
    <div id="tags-list" class="flex-shrink-0">
    </div>

    <div id="product-list" class="d-flex">
    </div>
</div>
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

            $.ajax({
                url: '<?= base_url('loadImages') ?>' + 'homePageImages',
                method: 'GET',
                dataType: 'JSON',

                success: function(response) 
                {
                    console.log(response.message);
                    response.data.forEach(function(image) 
                    {
                        imageArray.push('images/homePageImages/' + image);
                        //'<img src="<?= base_url('images/homePageImages/') ?>' + image + '" class="img-thumbnail m-2"/>';
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
            const baseUrl = "<?= base_url()?>";

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
                    <img src="${baseUrl}${image}" class="d-block w-100 h-auto" alt="Slide ${i + 1}">
                    <div class="carousel-caption d-block" style="top: 90%;">
                        <h5 class="text-shadow">Lave</h5>
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