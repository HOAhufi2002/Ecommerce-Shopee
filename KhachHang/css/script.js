let slideIndex = 0;
let autoSlideInterval;

function showSlides(index) {
    const slides = document.querySelectorAll('.slides img');
    const dots = document.querySelectorAll('.dot');

    if (index >= slides.length) {
        slideIndex = 0;
    } else if (index < 0) {
        slideIndex = slides.length - 1;
    }

    // Ẩn tất cả ảnh
    slides.forEach((slide) => slide.style.display = 'none');

    // Xóa trạng thái active của tất cả chấm
    dots.forEach((dot) => dot.classList.remove('active'));

    // Hiển thị ảnh hiện tại
    slides[slideIndex].style.display = 'block';

    // Kích hoạt chấm tương ứng
    dots[slideIndex].classList.add('active');

    // Thiết lập lại bộ đếm tự động
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(() => moveSlide(1), 4000);
}

function moveSlide(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n - 1);
}

// Tự động chuyển slide
window.onload = function () {
    showSlides(slideIndex);
    autoSlideInterval = setInterval(() => moveSlide(1), 4000);
}
//--------------------------