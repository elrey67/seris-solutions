jQuery(document).ready(function($) {
  $('.seris-slider-container').each(function() {
    const $slider = $(this);
    const $wrapper = $slider.find('.seris-slider-wrapper');
    const $slides = $wrapper.children('.seris-slide');
    const $prevBtn = $slider.find('.seris-slider-prev');
    const $nextBtn = $slider.find('.seris-slider-next');
    const $pagination = $slider.find('.seris-slider-pagination');
    
    if ($slides.length <= 1) return;
    
    // Create pagination dots
    $slides.each(function(index) {
      $pagination.append(`<button class="seris-slider-dot" data-index="${index}"></button>`);
    });
    
    const $dots = $pagination.find('.seris-slider-dot');
    let currentIndex = 0;
    let isAnimating = false;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    let autoSlideInterval;
    
    // Initialize
    updatePagination();
    startAutoSlide();
    
    // Navigation functions
    function goToSlide(index) {
      if (isAnimating || index === currentIndex) return;
      
      isAnimating = true;
      currentIndex = index;
      
      $wrapper.css('transform', `translateX(-${currentIndex * 100}%)`);
      updatePagination();
      
      setTimeout(() => {
        isAnimating = false;
      }, 500);
    }
    
    function updatePagination() {
      $dots.removeClass('active').eq(currentIndex).addClass('active');
    }
    
    function startAutoSlide() {
      // Clear existing interval if any
      if (autoSlideInterval) clearInterval(autoSlideInterval);
      
      // Set new interval (5 seconds)
      autoSlideInterval = setInterval(() => {
        goToSlide((currentIndex + 1) % $slides.length);
      }, 5000);
    }
    
    function resetAutoSlide() {
      startAutoSlide();
    }
    
    // Pause auto slide on hover
    $slider.hover(
      function() {
        clearInterval(autoSlideInterval);
      },
      function() {
        resetAutoSlide();
      }
    );
    
    // Pause auto slide on drag/touch
    $wrapper.on('touchstart pointerdown', function(e) {
      clearInterval(autoSlideInterval);
      if (isAnimating) return;
      
      isDragging = true;
      startX = e.type === 'touchstart' ? e.originalEvent.touches[0].clientX : e.clientX;
      currentX = startX;
      $wrapper.addClass('grabbing');
    });
    
    // Arrow navigation
    $prevBtn.on('click', function() {
      goToSlide((currentIndex - 1 + $slides.length) % $slides.length);
      resetAutoSlide();
    });
    
    $nextBtn.on('click', function() {
      goToSlide((currentIndex + 1) % $slides.length);
      resetAutoSlide();
    });
    
    // Dot navigation
    $dots.on('click', function() {
      goToSlide(parseInt($(this).data('index')));
      resetAutoSlide();
    });
    
    // Touch/swipe handling
    $(document).on('touchmove pointermove', function(e) {
      if (!isDragging) return;
      
      e.preventDefault();
      const x = e.type === 'touchmove' ? e.originalEvent.touches[0].clientX : e.clientX;
      const diff = currentX - x;
      currentX = x;
      
      // Move the wrapper temporarily during drag
      const currentTransform = -currentIndex * 100;
      const newTransform = currentTransform + (diff / $wrapper.outerWidth()) * 100;
      $wrapper.css('transform', `translateX(${newTransform}%)`);
    });
    
    $(document).on('touchend pointerup', function(e) {
      if (!isDragging) return;
      
      isDragging = false;
      $wrapper.removeClass('grabbing');
      resetAutoSlide();
      
      const endX = e.type === 'touchend' ? e.originalEvent.changedTouches[0].clientX : e.clientX;
      const diff = startX - endX;
      const threshold = $wrapper.outerWidth() * 0.2;
      
      if (diff > threshold) {
        // Swipe left - next slide
        goToSlide((currentIndex + 1) % $slides.length);
      } else if (diff < -threshold) {
        // Swipe right - previous slide
        goToSlide((currentIndex - 1 + $slides.length) % $slides.length);
      } else {
        // Return to current slide
        goToSlide(currentIndex);
      }
    });
  });
});